<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();

        $todayTotal = Attendance::where('date', $today)->count();
        $todayPresent = Attendance::where('date', $today)
            ->whereIn('status', ['present', 'late'])
            ->count();
        $todayRate = $todayTotal > 0 ? round(($todayPresent / $todayTotal) * 100) : 0;

        $recentAttendance = Attendance::with(['student', 'classSubject.subject', 'classSubject.schoolClass'])
            ->latest()
            ->take(10)
            ->get();

        // Attendance chart for the selected range
        $range = $request->input('range', 'this_week');
        [$start, $end, $granularity] = $this->resolveChartRange($range);
        $weeklyChart = $this->buildAttendanceChart($start, $end, $granularity);

        return Inertia::render('Dashboard/Index', [
            'stats' => [
                'totalStudents' => User::where('role', 'student')->count(),
                'totalTeachers' => User::where('role', 'teacher')->count(),
                'totalClasses' => SchoolClass::count(),
                'todayAttendance' => $todayRate,
            ],
            'weeklyChart' => $weeklyChart,
            'chartRange' => $range,
            'recentAttendance' => $recentAttendance,
        ]);
    }

    /**
     * Resolve a range key into [start, end, granularity].
     * Week/month ranges are bucketed by day; year/all ranges by month.
     */
    private function resolveChartRange(string $range): array
    {
        $now = now();

        return match ($range) {
            'last_week'  => [$now->copy()->subWeek()->startOfWeek(), $now->copy()->subWeek()->endOfWeek(), 'day'],
            'this_month' => [$now->copy()->startOfMonth(), $now->copy()->endOfMonth(), 'day'],
            'last_month' => [$now->copy()->subMonthNoOverflow()->startOfMonth(), $now->copy()->subMonthNoOverflow()->endOfMonth(), 'day'],
            'this_year'  => [$now->copy()->startOfYear(), $now->copy()->endOfYear(), 'month'],
            'last_year'  => [$now->copy()->subYear()->startOfYear(), $now->copy()->subYear()->endOfYear(), 'month'],
            'all'        => [
                ($min = Attendance::min('date')) ? Carbon::parse($min)->startOfMonth() : $now->copy()->startOfYear(),
                $now->copy()->endOfMonth(),
                'month',
            ],
            default      => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek(), 'day'],
        };
    }

    /**
     * Build present/absent/late counts for each period (day or month) in the range.
     */
    private function buildAttendanceChart(Carbon $start, Carbon $end, string $granularity): array
    {
        $records = Attendance::whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->get(['date', 'status']);

        $buckets = [];
        foreach ($records as $record) {
            $date = $record->date instanceof Carbon ? $record->date : Carbon::parse($record->date);
            $key = $granularity === 'month' ? $date->format('Y-m') : $date->toDateString();
            $buckets[$key] ??= ['present' => 0, 'absent' => 0, 'late' => 0, 'total' => 0];
            $buckets[$key]['total']++;
            if (in_array($record->status, ['present', 'absent', 'late'], true)) {
                $buckets[$key][$record->status]++;
            }
        }

        $chart = [];
        $cursor = $granularity === 'month' ? $start->copy()->startOfMonth() : $start->copy()->startOfDay();
        while ($cursor <= $end) {
            if ($granularity === 'month') {
                $key = $cursor->format('Y-m');
                $label = $cursor->format('M Y');
                $next = $cursor->copy()->addMonth();
            } else {
                $key = $cursor->toDateString();
                $label = $cursor->format('M d');
                $next = $cursor->copy()->addDay();
            }
            $b = $buckets[$key] ?? ['present' => 0, 'absent' => 0, 'late' => 0, 'total' => 0];
            $chart[] = [
                'date' => $label,
                'present' => $b['present'],
                'absent' => $b['absent'],
                'late' => $b['late'],
                'rate' => $b['total'] > 0 ? round(($b['present'] + $b['late']) / $b['total'] * 100) : 0,
            ];
            $cursor = $next;
        }

        return $chart;
    }
}
