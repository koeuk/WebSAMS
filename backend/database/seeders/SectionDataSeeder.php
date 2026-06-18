<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\ClassSubject;
use App\Models\ExcuseRequest;
use App\Models\Notification;
use App\Models\Schedule;
use App\Models\SchoolClass;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Database\Seeder;

class SectionDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSchedules();
        $this->seedAttendances();
        $this->seedAnnouncements();
        $this->seedNotifications();
        $this->seedExcuseRequests();
    }

    private function seedSchedules(): void
    {
        $timeSlots = TimeSlot::orderBy('id')->get();
        $days = ['mon', 'tue', 'wed', 'thu', 'fri'];

        ClassSubject::orderBy('id')->get()->each(function (ClassSubject $classSubject, int $index) use ($timeSlots, $days) {
            if ($timeSlots->isEmpty()) {
                return;
            }

            // Group section determines the daily cohort slot
            $sectionIndex = $index % 5;
            $slot = $timeSlots[$sectionIndex % $timeSlots->count()];

            foreach ($days as $dayIndex => $day) {
                Schedule::updateOrCreate(
                    [
                        'class_subject_id' => $classSubject->id,
                        'time_slot_id' => $slot->id,
                        'day_of_week' => $day,
                    ],
                    ['room' => 'Room ' . (201 + $index)],
                );
            }
        });
    }

    private function getDatesForWeekday(string $startDateStr, string $endDateStr, string $dayOfWeek): array
    {
        $dates = [];
        $start = \Carbon\Carbon::parse($startDateStr);
        $end = \Carbon\Carbon::parse($endDateStr);
        $today = now();

        // Adjust start date to the first occurrence of the weekday
        while (strtolower(substr($start->format('D'), 0, 3)) !== strtolower($dayOfWeek)) {
            $start->addDay();
        }

        // Loop weekly
        while ($start->lte($end) && $start->lte($today)) {
            $dates[] = $start->toDateString();
            $start->addWeek();
        }

        return $dates;
    }

    private function seedAttendances(): void
    {
        $semesters = \App\Models\Semester::all();
        $attendancesToInsert = [];

        ClassSubject::with(['schoolClass.students', 'schedules'])->orderBy('id')->get()->each(
            function (ClassSubject $classSubject) use ($semesters, &$attendancesToInsert) {
                $schoolClass = $classSubject->schoolClass;
                if (!$schoolClass) {
                    return;
                }

                $students = $schoolClass->students ?? collect();
                $academicYear = $schoolClass->academic_year;

                // Get semesters for this class's academic year
                $classSemesters = $semesters->where('academic_year', $academicYear);

                foreach ($classSubject->schedules as $schedule) {
                    foreach ($classSemesters as $semester) {
                        $dates = $this->getDatesForWeekday(
                            $semester->start_date->toDateString(),
                            $semester->end_date->toDateString(),
                            $schedule->day_of_week
                        );

                        foreach ($dates as $date) {
                            foreach ($students as $studentIndex => $student) {
                                $rand = rand(1, 100);
                                if ($studentIndex === 3) { // 75% rate (warning)
                                    $status = $rand <= 72 ? 'present' : ($rand <= 76 ? 'late' : ($rand <= 95 ? 'absent' : 'excused'));
                                } elseif ($studentIndex === 4) { // 55% rate (danger)
                                    $status = $rand <= 50 ? 'present' : ($rand <= 56 ? 'late' : ($rand <= 92 ? 'absent' : 'excused'));
                                } else { // 90%+ rate (good)
                                    $status = $rand <= 90 ? 'present' : ($rand <= 94 ? 'late' : ($rand <= 98 ? 'absent' : 'excused'));
                                }

                                $attendancesToInsert[] = [
                                    'class_subject_id' => $classSubject->id,
                                    'student_id' => $student->id,
                                    'date' => $date,
                                    'time_slot_id' => $schedule->time_slot_id,
                                    'status' => $status,
                                    'remarks' => null,
                                    'recorded_by' => $classSubject->teacher_id,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];

                                // Insert in chunks of 2000 to prevent memory limits
                                if (count($attendancesToInsert) >= 2000) {
                                    Attendance::insert($attendancesToInsert);
                                    $attendancesToInsert = [];
                                }
                            }
                        }
                    }
                }
            }
        );

        // Insert remaining
        if (!empty($attendancesToInsert)) {
            Attendance::insert($attendancesToInsert);
        }
    }

    private function seedAnnouncements(): void
    {
        $admin = User::where('role', 'admin')->first();
        $firstClass = SchoolClass::orderBy('academic_year')->orderBy('section')->first();

        if (!$admin) {
            return;
        }

        $items = [
            [
                'audience' => 'all',
                'class_id' => null,
                'title' => $this->translations('Welcome to the new academic dataset', 'សូមស្វាគមន៍មកកាន់ទិន្នន័យឆ្នាំសិក្សាថ្មី', '欢迎使用新的学年数据'),
                'body' => $this->translations('Courses, classes, schedules, and attendance records are ready for review.', 'វគ្គសិក្សា ថ្នាក់ កាលវិភាគ និងកំណត់ត្រាវត្តមាន ត្រូវបានរៀបចំរួចរាល់សម្រាប់ពិនិត្យ។', '课程、班级、课表和考勤记录已准备好查看。'),
            ],
            [
                'audience' => 'students',
                'class_id' => $firstClass?->id,
                'title' => $this->translations('Attendance reminder', 'ការរំលឹកអំពីវត្តមាន', '考勤提醒'),
                'body' => $this->translations('Please check your attendance and submit excuse requests when needed.', 'សូមពិនិត្យវត្តមានរបស់អ្នក ហើយដាក់សំណើសុំអនុញ្ញាតពេលចាំបាច់។', '请查看你的考勤，需要时提交请假申请。'),
            ],
            [
                'audience' => 'teachers',
                'class_id' => null,
                'title' => $this->translations('Schedule data is ready', 'ទិន្នន័យកាលវិភាគរួចរាល់', '课表数据已准备好'),
                'body' => $this->translations('Teachers can review assigned classes and attendance records.', 'គ្រូអាចពិនិត្យថ្នាក់ដែលបានចាត់តាំង និងកំណត់ត្រាវត្តមាន។', '教师可以查看已分配的班级和考勤记录。'),
            ],
        ];

        foreach ($items as $item) {
            $announcement = Announcement::where('audience', $item['audience'])
                ->where('class_id', $item['class_id'])
                ->first() ?? new Announcement(['audience' => $item['audience'], 'class_id' => $item['class_id']]);

            $announcement->fill([
                'author_id' => $admin->id,
                'title' => $item['title'],
                'body' => $item['body'],
                'published_at' => now(),
            ])->save();
        }
    }

    private function seedNotifications(): void
    {
        User::whereIn('role', ['admin', 'teacher', 'student'])
            ->orderBy('id')
            ->limit(30)
            ->get()
            ->each(function (User $user, int $index) {
                Notification::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'title' => 'Academic data ready',
                        'message' => 'Your schedules and attendance data are available.',
                    ],
                    [
                        'type' => $user->role === 'student' ? 'absence' : 'general',
                        'is_read' => $index % 3 === 0,
                    ],
                );
            });
    }

    private function seedExcuseRequests(): void
    {
        $reviewer = User::where('role', 'teacher')->first();

        Attendance::whereIn('status', ['absent', 'late', 'excused'])
            ->orderBy('id')
            ->limit(60)
            ->get()
            ->each(function (Attendance $attendance, int $index) use ($reviewer) {
                ExcuseRequest::updateOrCreate(
                    ['attendance_id' => $attendance->id],
                    [
                        'student_id' => $attendance->student_id,
                        'reason' => $index % 2 === 0 ? 'មានធុរៈគ្រួសារ' : 'ឈឺ និងបានជូនដំណឹងគ្រូ',
                        'status' => ['pending', 'approved', 'rejected'][$index % 3],
                        'reviewed_by' => $index % 3 === 0 ? null : $reviewer?->id,
                        'reviewed_at' => $index % 3 === 0 ? null : now()->subDays($index % 5),
                        'reviewer_note' => $index % 3 === 0 ? null : 'បានពិនិត្យរួច',
                    ],
                );
            });
    }

    private function translations(string $en, string $km, string $zh): array
    {
        return [
            'en' => $en,
            'km' => $km,
            'zh' => $zh,
        ];
    }
}