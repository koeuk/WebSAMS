<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <!-- Header -->
            <div class="flex flex-wrap items-start gap-4 mb-8">
                <Link href="/admin/reports" class="inline-flex items-center gap-1.5 glass-btn rounded-full px-3.5 py-2 text-[13px] font-medium text-slate-600 hover:text-slate-900 mt-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                    {{ __('Back') }}
                </Link>
                <div class="flex-1 min-w-0">
                    <h2 class="text-3xl font-bold tracking-tight bg-gradient-to-br from-slate-900 via-slate-800 to-beltei bg-clip-text text-transparent">{{ __('Attendance Report') }}</h2>
                    <div class="flex flex-wrap items-center gap-2 mt-3">
                        <span v-if="semester" class="inline-flex items-center gap-1.5 rounded-full bg-beltei-gold/12 ring-1 ring-beltei-gold/25 px-3 py-1 text-[12px] font-medium text-beltei-gold-dark">
                            {{ semester.name }} ({{ semester.academic_year }})
                        </span>
                        <span v-for="c in scopeChips" :key="c" class="inline-flex items-center rounded-full bg-white/55 ring-1 ring-white/60 px-3 py-1 text-[12px] font-medium text-slate-600 backdrop-blur-sm">{{ c }}</span>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/55 ring-1 ring-white/60 px-3 py-1 text-[12px] font-medium text-slate-600 backdrop-blur-sm">
                            <svg class="w-3.5 h-3.5 text-beltei-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            {{ formatDate(filters?.date_from) }} → {{ formatDate(filters?.date_to) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- SECTION 1 — Summary cards -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8 stagger-children">
                <Card class="stat-card stat-card-slate animate-fade-in-up">
                    <CardContent class="p-5 text-center">
                        <p class="text-[12px] font-medium text-slate-500">{{ __('Total Records') }}</p>
                        <p class="text-3xl font-bold text-slate-900 mt-1.5 tracking-tight">{{ overall?.total ?? 0 }}</p>
                    </CardContent>
                </Card>
                <Card class="stat-card stat-card-emerald animate-fade-in-up">
                    <CardContent class="p-5 text-center">
                        <p class="text-[12px] font-medium text-slate-500">{{ __('Present') }}</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-1.5 tracking-tight">{{ overall?.present ?? 0 }}</p>
                    </CardContent>
                </Card>
                <Card class="stat-card stat-card-rose animate-fade-in-up">
                    <CardContent class="p-5 text-center">
                        <p class="text-[12px] font-medium text-slate-500">{{ __('Absent') }}</p>
                        <p class="text-3xl font-bold text-rose-600 mt-1.5 tracking-tight">{{ overall?.absent ?? 0 }}</p>
                    </CardContent>
                </Card>
                <Card class="stat-card stat-card-amber animate-fade-in-up">
                    <CardContent class="p-5 text-center">
                        <p class="text-[12px] font-medium text-slate-500">{{ __('Late') }}</p>
                        <p class="text-3xl font-bold text-amber-600 mt-1.5 tracking-tight">{{ overall?.late ?? 0 }}</p>
                    </CardContent>
                </Card>
                <Card class="stat-card stat-card-sky animate-fade-in-up">
                    <CardContent class="p-5 text-center">
                        <p class="text-[12px] font-medium text-slate-500">{{ __('Excused') }}</p>
                        <p class="text-3xl font-bold text-sky-600 mt-1.5 tracking-tight">{{ overall?.excused ?? 0 }}</p>
                    </CardContent>
                </Card>
                <Card class="stat-card stat-card-violet animate-fade-in-up">
                    <CardContent class="p-5 text-center">
                        <p class="text-[12px] font-medium text-slate-500">{{ __('Attendance Rate') }}</p>
                        <p class="text-3xl font-bold mt-1.5 tracking-tight" :class="(overall?.rate ?? 0) >= 80 ? 'text-emerald-600' : (overall?.rate ?? 0) >= 60 ? 'text-amber-600' : 'text-rose-600'">{{ overall?.rate ?? 0 }}%</p>
                    </CardContent>
                </Card>
            </div>

            <!-- SECTION 2 — Graph -->
            <template v-if="hasData">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                    <h3 class="text-[12px] font-semibold uppercase tracking-wider text-slate-500">{{ __('Overview') }}</h3>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-8">
                    <!-- Doughnut composition -->
                    <Card class="lg:col-span-2 animate-fade-in-up">
                        <CardContent class="p-6">
                            <h4 class="text-sm font-semibold text-slate-900 mb-4">{{ __('Attendance Composition') }}</h4>
                            <div class="relative" style="height: 210px;">
                                <Doughnut :data="compositionData" :options="compositionOptions" :plugins="[centerTextPlugin]" />
                            </div>
                            <div class="mt-5 space-y-2.5">
                                <div v-for="c in composition" :key="c.label" class="flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-2.5 h-2.5 rounded-full ring-2 ring-white/70" :style="{ background: c.color }"></span>
                                        <span class="text-[13px] text-slate-600">{{ c.label }}</span>
                                    </div>
                                    <div class="text-[13px]"><span class="font-semibold text-slate-900">{{ c.value }}</span> <span class="text-slate-400">({{ c.pct }}%)</span></div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                    <!-- Per-student rate bars -->
                    <Card class="lg:col-span-3 animate-fade-in-up" style="animation-delay: 80ms;">
                        <CardContent class="p-6">
                            <h4 class="text-sm font-semibold text-slate-900 mb-4">{{ __('Attendance Rate by Student') }}</h4>
                            <div :style="{ height: barHeight + 'px' }">
                                <Bar :data="studentRateData" :options="studentRateOptions" />
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </template>
            <Card v-else class="mb-8 animate-fade-in-up">
                <CardContent class="py-16 text-center">
                    <div class="w-14 h-14 mx-auto rounded-2xl flex items-center justify-center bg-white/40 ring-1 ring-inset ring-white/60 backdrop-blur-md">
                        <svg class="w-7 h-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </div>
                    <p class="mt-4 text-sm font-medium text-slate-600">{{ __('No attendance records for this period.') }}</p>
                    <p class="text-[13px] text-slate-400 mt-1">{{ __('Try a wider date range or different filters.') }}</p>
                </CardContent>
            </Card>

            <!-- SECTION 3 — Per-student list -->
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                <h3 class="text-[12px] font-semibold uppercase tracking-wider text-slate-500">{{ __('Per-Student Breakdown') }}</h3>
            </div>
            <Card class="overflow-hidden">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>{{ __('Student') }}</TableHead>
                            <TableHead class="text-center">{{ __('Total') }}</TableHead>
                            <TableHead class="text-center">{{ __('Present') }}</TableHead>
                            <TableHead class="text-center">{{ __('Absent') }}</TableHead>
                            <TableHead class="text-center">{{ __('Late') }}</TableHead>
                            <TableHead class="text-center">{{ __('Excused') }}</TableHead>
                            <TableHead class="text-center">{{ __('Rate') }}</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="row in studentSummary" :key="row.student?.id">
                            <TableCell class="font-semibold text-slate-900">{{ row.student?.name }}</TableCell>
                            <TableCell class="text-center font-mono">{{ row.total }}</TableCell>
                            <TableCell class="text-center font-mono text-emerald-600">{{ row.present }}</TableCell>
                            <TableCell class="text-center font-mono text-rose-600">{{ row.absent }}</TableCell>
                            <TableCell class="text-center font-mono text-amber-600">{{ row.late }}</TableCell>
                            <TableCell class="text-center font-mono text-sky-600">{{ row.excused }}</TableCell>
                            <TableCell class="text-center">
                                <span class="inline-flex items-center justify-center min-w-[3rem] rounded-full px-2.5 py-1 text-[12px] font-bold ring-1"
                                    :class="row.rate >= 80 ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : row.rate >= 60 ? 'bg-amber-50 text-amber-700 ring-amber-200' : 'bg-rose-50 text-rose-700 ring-rose-200'">
                                    {{ row.rate }}%
                                </span>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!studentSummary?.length">
                            <TableCell colspan="7" class="text-center py-12 text-slate-400">{{ __('No data found for the selected filters.') }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Card, CardContent } from '@/Components/ui/card';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { Doughnut, Bar } from 'vue-chartjs';
import { Chart as ChartJS, ArcElement, BarElement, CategoryScale, LinearScale, Tooltip, Legend } from 'chart.js';
import { __ } from '@/Composables/useTranslate';

ChartJS.register(ArcElement, BarElement, CategoryScale, LinearScale, Tooltip, Legend);

const props = defineProps({ studentSummary: Array, overall: Object, semester: Object, filters: Object, classes: Array, subjects: Array, courses: Array });

const className = props.classes?.find(c => c.id == props.filters?.class_id)?.name || __('All Classes');
const subjectName = props.subjects?.find(s => s.id == props.filters?.subject_id)?.name || __('All Subjects');
const courseName = props.courses?.find(c => c.id == props.filters?.course_id)?.name || __('All Courses');
const statusLabel = props.filters?.status ? props.filters.status.charAt(0).toUpperCase() + props.filters.status.slice(1) : __('All Status');
const scopeChips = [courseName, className, subjectName, statusLabel];

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '';

const hasData = computed(() => (props.overall?.total ?? 0) > 0);

const glassTooltip = {
    backgroundColor: 'rgba(15, 23, 42, 0.92)',
    padding: 12, cornerRadius: 12, boxPadding: 6, usePointStyle: true,
    titleFont: { family: 'Plus Jakarta Sans', weight: '700', size: 13 },
    bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
};

// --- Composition (doughnut) ---
const composition = computed(() => {
    const o = props.overall || {};
    const total = o.total || 0;
    const pct = (n) => (total ? Math.round((n / total) * 100) : 0);
    return [
        { label: __('Present'), value: o.present || 0, pct: pct(o.present || 0), color: '#10b981' },
        { label: __('Late'),    value: o.late || 0,    pct: pct(o.late || 0),    color: '#f59e0b' },
        { label: __('Absent'),  value: o.absent || 0,  pct: pct(o.absent || 0),  color: '#f43f5e' },
        { label: __('Excused'), value: o.excused || 0, pct: pct(o.excused || 0), color: '#0ea5e9' },
    ];
});

const compositionData = computed(() => ({
    labels: composition.value.map(c => c.label),
    datasets: [{
        data: composition.value.map(c => c.value),
        backgroundColor: composition.value.map(c => c.color),
        borderColor: 'rgba(255, 255, 255, 0.75)',
        borderWidth: 2,
        hoverOffset: 6,
    }],
}));

const compositionOptions = computed(() => ({
    responsive: true,
    maintainAspectRatio: false,
    cutout: '68%',
    plugins: {
        legend: { display: false },
        tooltip: glassTooltip,
        centerText: { rate: props.overall?.rate ?? 0 },
    },
}));

const centerTextPlugin = {
    id: 'centerText',
    afterDatasetsDraw(chart, args, opts) {
        const arc = chart.getDatasetMeta(0)?.data?.[0];
        if (!arc) return;
        const ctx = chart.ctx;
        ctx.save();
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillStyle = '#0f172a';
        ctx.font = "700 26px 'Plus Jakarta Sans', sans-serif";
        ctx.fillText(`${opts.rate ?? 0}%`, arc.x, arc.y - 6);
        ctx.fillStyle = '#94a3b8';
        ctx.font = "600 10px 'Plus Jakarta Sans', sans-serif";
        ctx.fillText(__('RATE'), arc.x, arc.y + 14);
        ctx.restore();
    },
};

// --- Per-student attendance rate (horizontal bars) ---
const studentRateData = computed(() => ({
    labels: (props.studentSummary || []).map(r => r.student?.name ?? '—'),
    datasets: [{
        label: __('Attendance Rate'),
        data: (props.studentSummary || []).map(r => r.rate),
        backgroundColor: (ctx) => {
            const v = ctx.raw ?? 0;
            return v >= 80 ? '#10b981' : v >= 60 ? '#f59e0b' : '#f43f5e';
        },
        borderRadius: 6,
        borderSkipped: false,
        maxBarThickness: 22,
    }],
}));

const barHeight = computed(() => Math.max(210, (props.studentSummary?.length || 0) * 40 + 24));

const studentRateOptions = {
    indexAxis: 'y',
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: { ...glassTooltip, callbacks: { label: (c) => ` ${c.raw}%` } },
    },
    scales: {
        x: { min: 0, max: 100, grid: { color: 'rgba(148, 163, 184, 0.18)' }, border: { display: false }, ticks: { callback: (v) => v + '%', font: { size: 11, family: 'Plus Jakarta Sans' }, color: '#94a3b8' } },
        y: { grid: { display: false }, border: { display: false }, ticks: { font: { size: 12, family: 'Plus Jakarta Sans' }, color: '#475569' } },
    },
};
</script>
