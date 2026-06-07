<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight bg-gradient-to-br from-slate-900 via-slate-800 to-beltei bg-clip-text text-transparent">{{ __('Dashboard') }}</h2>
                    <p class="text-sm text-slate-500 mt-1.5">{{ __("Welcome back! Here's what's happening today.") }}</p>
                </div>
                <div class="hidden sm:inline-flex items-center gap-2 glass-btn rounded-full px-4 py-2 text-[13px] font-medium text-slate-600">
                    <svg class="w-4 h-4 text-beltei-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 stagger-children">
                <Card class="stat-card stat-card-blue animate-fade-in-up">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[13px] font-medium text-slate-500">{{ __('Total Students') }}</p>
                                <p class="text-3xl font-bold text-slate-900 mt-1.5 tracking-tight">{{ stats?.totalStudents ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-white/40 ring-1 ring-inset ring-white/60 backdrop-blur-md shadow-[inset_0_1px_0_rgba(255,255,255,0.7)]">
                                <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                            </div>
                        </div>
                    </CardContent>
                </Card>
                <Card class="stat-card stat-card-emerald animate-fade-in-up">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[13px] font-medium text-slate-500">{{ __('Total Teachers') }}</p>
                                <p class="text-3xl font-bold text-slate-900 mt-1.5 tracking-tight">{{ stats?.totalTeachers ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-white/40 ring-1 ring-inset ring-white/60 backdrop-blur-md shadow-[inset_0_1px_0_rgba(255,255,255,0.7)]">
                                <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                            </div>
                        </div>
                    </CardContent>
                </Card>
                <Card class="stat-card stat-card-amber animate-fade-in-up">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[13px] font-medium text-slate-500">{{ __('Total Classes') }}</p>
                                <p class="text-3xl font-bold text-slate-900 mt-1.5 tracking-tight">{{ stats?.totalClasses ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-white/40 ring-1 ring-inset ring-white/60 backdrop-blur-md shadow-[inset_0_1px_0_rgba(255,255,255,0.7)]">
                                <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            </div>
                        </div>
                    </CardContent>
                </Card>
                <Card class="stat-card stat-card-violet animate-fade-in-up">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[13px] font-medium text-slate-500">{{ __("Today's Attendance") }}</p>
                                <p class="text-3xl font-bold mt-1.5 tracking-tight" :class="(stats?.todayAttendance ?? 0) >= 80 ? 'text-emerald-600' : 'text-rose-600'">{{ stats?.todayAttendance ?? 0 }}%</p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-white/40 ring-1 ring-inset ring-white/60 backdrop-blur-md shadow-[inset_0_1px_0_rgba(255,255,255,0.7)]">
                                <svg class="w-6 h-6 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Weekly Chart -->
            <Card class="mb-8 animate-fade-in-up" style="animation-delay: 200ms;">
                <CardContent class="p-6">
                    <div class="flex items-start justify-between mb-6 gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">{{ __('Attendance Overview') }}</h3>
                            <p class="text-[13px] text-slate-500 mt-0.5">{{ __('Showing') }}: {{ rangeLabel }}</p>
                        </div>
                        <div class="relative shrink-0">
                            <select
                                v-model="range"
                                class="appearance-none glass-btn rounded-full pl-4 pr-9 py-2 text-[13px] font-medium text-slate-700 cursor-pointer focus:outline-none"
                                :aria-label="__('Select range')"
                            >
                                <option v-for="o in rangeOptions" :key="o.value" :value="o.value">{{ o.label }}</option>
                            </select>
                            <svg class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                        </div>
                    </div>
                    <div style="height: 280px;">
                        <Bar :data="chartData" :options="chartOptions" />
                    </div>
                </CardContent>
            </Card>

            <!-- Recent Attendance -->
            <Card class="overflow-hidden animate-fade-in-up" style="animation-delay: 300ms;">
                <div class="px-6 py-5 border-b border-white/40">
                    <h3 class="text-base font-semibold text-slate-900">{{ __('Recent Attendance') }}</h3>
                    <p class="text-[13px] text-slate-500 mt-0.5">{{ __('Latest attendance records across all classes') }}</p>
                </div>
                <div class="overflow-x-auto">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>{{ __('Student') }}</TableHead>
                                <TableHead>{{ __('Class') }}</TableHead>
                                <TableHead>{{ __('Subject') }}</TableHead>
                                <TableHead>{{ __('Date') }}</TableHead>
                                <TableHead>{{ __('Status') }}</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="record in recentAttendance" :key="record.id">
                                <TableCell class="font-medium text-slate-900">{{ record.student?.name }}</TableCell>
                                <TableCell>{{ record.class_subject?.school_class?.name }}</TableCell>
                                <TableCell>{{ record.class_subject?.subject?.name }}</TableCell>
                                <TableCell>{{ formatDate(record.date) }}</TableCell>
                                <TableCell>
                                    <Badge :class="statusClass(record.status)">{{ record.status }}</Badge>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="!recentAttendance?.length">
                                <TableCell colspan="5" class="text-center py-12 text-slate-400">{{ __('No attendance records yet.') }}</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </Card>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { computed, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend } from 'chart.js';
import { Card, CardContent } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { __ } from '@/Composables/useTranslate';

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const props = defineProps({
    stats: Object,
    weeklyChart: Array,
    chartRange: String,
    recentAttendance: Array,
});

// Vertical gradient fill for bars — falls back to a solid color before the chart area exists
const barGradient = (from, to) => (ctx) => {
    const { ctx: g, chartArea } = ctx.chart;
    if (!chartArea) return from;
    const grad = g.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
    grad.addColorStop(0, from);
    grad.addColorStop(1, to);
    return grad;
};

const chartData = computed(() => ({
    labels: props.weeklyChart?.map(d => d.date) || [],
    datasets: [
        { label: __('Present'), data: props.weeklyChart?.map(d => d.present) || [], backgroundColor: barGradient('rgba(5,150,105,0.85)', 'rgba(52,211,153,0.95)'), borderRadius: 8, borderSkipped: false, maxBarThickness: 26 },
        { label: __('Absent'), data: props.weeklyChart?.map(d => d.absent) || [], backgroundColor: barGradient('rgba(225,29,72,0.85)', 'rgba(251,113,133,0.95)'), borderRadius: 8, borderSkipped: false, maxBarThickness: 26 },
        { label: __('Late'), data: props.weeklyChart?.map(d => d.late) || [], backgroundColor: barGradient('rgba(217,119,6,0.85)', 'rgba(251,191,36,0.95)'), borderRadius: 8, borderSkipped: false, maxBarThickness: 26 },
    ],
}));

// Chart range selector
const rangeOptions = [
    { value: 'this_week', label: __('This Week') },
    { value: 'last_week', label: __('Last Week') },
    { value: 'this_month', label: __('This Month') },
    { value: 'last_month', label: __('Last Month') },
    { value: 'this_year', label: __('This Year') },
    { value: 'last_year', label: __('Last Year') },
    { value: 'all', label: __('All') },
];
const range = ref(props.chartRange || 'this_week');
const rangeLabel = computed(() => rangeOptions.find(o => o.value === range.value)?.label || '');
watch(range, (val) => {
    router.get('/admin/dashboard', { range: val }, {
        preserveScroll: true,
        preserveState: true,
        only: ['weeklyChart', 'chartRange'],
    });
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
            align: 'end',
            labels: { usePointStyle: true, pointStyle: 'circle', padding: 20, font: { size: 12, family: 'Plus Jakarta Sans', weight: '600' }, color: '#475569' }
        },
        tooltip: {
            backgroundColor: 'rgba(15, 23, 42, 0.92)',
            padding: 12,
            cornerRadius: 12,
            boxPadding: 6,
            usePointStyle: true,
            titleFont: { family: 'Plus Jakarta Sans', weight: '700', size: 13 },
            bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
        },
    },
    scales: {
        x: { stacked: true, grid: { display: false }, ticks: { font: { size: 11, family: 'Plus Jakarta Sans' }, color: '#94a3b8' } },
        y: { stacked: true, grid: { color: 'rgba(148, 163, 184, 0.18)' }, border: { display: false, dash: [4, 4] }, ticks: { font: { size: 11, family: 'Plus Jakarta Sans' }, color: '#94a3b8' } },
    },
};

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '-';

const statusVariant = (status) => ({
    present: 'default',
    absent: 'destructive',
    late: 'secondary',
    excused: 'outline',
}[status] || 'secondary');

const statusClass = (status) => ({
    'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 hover:bg-emerald-50': status === 'present',
    'bg-rose-50 text-rose-700 ring-1 ring-rose-200 hover:bg-rose-50': status === 'absent',
    'bg-amber-50 text-amber-700 ring-1 ring-amber-200 hover:bg-amber-50': status === 'late',
    'bg-sky-50 text-sky-700 ring-1 ring-sky-200 hover:bg-sky-50': status === 'excused',
});
</script>
