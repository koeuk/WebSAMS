<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <!-- Header -->
            <div class="flex items-start gap-4 mb-6">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-white/40 ring-1 ring-inset ring-white/60 backdrop-blur-md shadow-[inset_0_1px_0_rgba(255,255,255,0.7)] shrink-0">
                    <svg class="w-6 h-6 text-beltei-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold tracking-tight bg-gradient-to-br from-slate-900 via-slate-800 to-beltei bg-clip-text text-transparent">{{ __('Reports') }}</h2>
                    <p class="text-sm text-slate-500 mt-1">{{ __('Generate attendance reports with custom filters') }}</p>
                </div>
            </div>

            <!-- ONE filter card -->
            <Card class="animate-fade-in-up">
                <CardContent class="p-7">
                    <form @submit.prevent="submit" class="space-y-7">
                        <!-- Quick range -->
                        <div class="flex flex-wrap items-center gap-2.5">
                            <span class="text-[13px] font-medium text-slate-500 mr-1">{{ __('Quick range') }}</span>
                            <button
                                v-for="p in presets"
                                :key="p.key"
                                type="button"
                                @click="applyPreset(p.key)"
                                class="px-4 py-2 rounded-full text-[13px] font-medium transition-all duration-200 cursor-pointer"
                                :class="activePreset === p.key
                                    ? 'bg-gradient-to-r from-beltei to-beltei-dark text-white shadow-md shadow-beltei/25'
                                    : 'glass-btn text-slate-600 hover:text-slate-900'"
                            >
                                {{ p.label }}
                            </button>
                        </div>

                        <div class="h-px bg-gradient-to-r from-transparent via-slate-200/70 to-transparent"></div>

                        <!-- Time period -->
                        <section>
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-4 h-4 text-beltei-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <h3 class="text-[12px] font-semibold uppercase tracking-wider text-slate-500">{{ __('Time Period') }}</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Semester') }}</Label>
                                    <FilterCombobox v-model="form.semester_id" :options="semesterOptions" :placeholder="__('Custom Date Range')" class="w-full" />
                                </div>
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Date From') }}</Label>
                                    <DatePicker v-model="form.date_from" :placeholder="__('Start date')" />
                                    <p v-if="errors.date_from" class="text-[12px] text-rose-500 mt-1">{{ errors.date_from }}</p>
                                </div>
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Date To') }}</Label>
                                    <DatePicker v-model="form.date_to" :placeholder="__('End date')" />
                                    <p v-if="errors.date_to" class="text-[12px] text-rose-500 mt-1">{{ errors.date_to }}</p>
                                </div>
                            </div>
                        </section>

                        <div class="h-px bg-gradient-to-r from-transparent via-slate-200/70 to-transparent"></div>

                        <!-- Filters -->
                        <section>
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-4 h-4 text-beltei-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                                <h3 class="text-[12px] font-semibold uppercase tracking-wider text-slate-500">{{ __('Filters') }} <span class="font-normal normal-case tracking-normal text-slate-400">— {{ __('optional') }}</span></h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Course') }}</Label>
                                    <FilterCombobox v-model="form.course_id" :options="courseOptions" :placeholder="__('All Courses')" class="w-full" />
                                </div>
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Class') }}</Label>
                                    <FilterCombobox v-model="form.class_id" :options="classOptions" :placeholder="__('All Classes')" class="w-full" />
                                </div>
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Subject') }}</Label>
                                    <FilterCombobox v-model="form.subject_id" :options="subjectOptions" :placeholder="__('All Subjects')" class="w-full" />
                                </div>
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Status Filter') }}</Label>
                                    <FilterCombobox v-model="form.status" :options="reportStatusOptions" :placeholder="__('All Status')" class="w-full" />
                                </div>
                            </div>
                        </section>

                        <Button
                            type="submit"
                            :disabled="generating"
                            class="w-full h-12 text-[15px] gap-2 bg-gradient-to-r from-beltei to-beltei-dark hover:from-beltei-dark hover:to-beltei shadow-lg shadow-beltei/25"
                        >
                            <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                            {{ generating ? __('Generating...') : __('Generate Report') }}
                        </Button>
                    </form>
                </CardContent>
            </Card>

            <!-- ===================== RESULTS (below filter) ===================== -->
            <div v-if="hasReport" id="report-results" class="mt-8 scroll-mt-24">
                <!-- Results header + scope chips -->
                <div class="flex flex-wrap items-center gap-2 mb-5">
                    <h3 class="text-lg font-bold text-slate-900 mr-1">{{ __('Report Results') }}</h3>
                    <span v-if="semester" class="inline-flex items-center gap-1.5 rounded-full bg-beltei-gold/12 ring-1 ring-beltei-gold/25 px-3 py-1 text-[12px] font-medium text-beltei-gold-dark">
                        {{ semester.name }} ({{ semester.academic_year }})
                    </span>
                    <span v-for="c in scopeChips" :key="c" class="inline-flex items-center rounded-full bg-white/55 ring-1 ring-white/60 px-3 py-1 text-[12px] font-medium text-slate-600 backdrop-blur-sm">{{ c }}</span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/55 ring-1 ring-white/60 px-3 py-1 text-[12px] font-medium text-slate-600 backdrop-blur-sm">
                        <svg class="w-3.5 h-3.5 text-beltei-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ formatDate(filters?.date_from) }} → {{ formatDate(filters?.date_to) }}
                    </span>
                </div>

                <!-- Summary cards -->
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

                <!-- Graph -->
                <template v-if="hasData">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                        <h3 class="text-[12px] font-semibold uppercase tracking-wider text-slate-500">{{ __('Overview') }}</h3>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-8">
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

                <!-- Per-student list -->
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

            <!-- Info strip (only before a report is generated) -->
            <Card v-else class="mt-6 animate-fade-in-up" style="animation-delay: 90ms;">
                <CardContent class="p-7">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2">
                                <svg class="w-[18px] h-[18px] text-beltei-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15l2 2 4-4"/></svg>
                                {{ __("What you'll get") }}
                            </h3>
                            <p class="text-[13px] text-slate-500 mt-1.5 leading-relaxed">{{ __('A complete attendance breakdown for the period and filters you choose.') }}</p>
                            <div class="mt-4 grid grid-cols-2 gap-y-2.5 gap-x-3">
                                <div v-for="m in metricLegend" :key="m.label" class="flex items-center gap-2.5">
                                    <span class="w-2.5 h-2.5 rounded-full ring-2 ring-white/70" :style="{ background: m.color }"></span>
                                    <span class="text-[13px] text-slate-600">{{ m.label }}</span>
                                </div>
                            </div>
                        </div>

                        <ul class="space-y-3 md:border-l md:border-slate-200/70 md:pl-7">
                            <li v-for="f in features" :key="f" class="flex gap-2.5 text-[13px] text-slate-600 leading-snug">
                                <svg class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
                                {{ f }}
                            </li>
                        </ul>

                        <div class="md:border-l md:border-slate-200/70 md:pl-7 flex items-start">
                            <div class="rounded-xl bg-beltei-gold/10 ring-1 ring-beltei-gold/20 px-4 py-3">
                                <p class="text-[12px] text-slate-600 leading-relaxed">
                                    <span class="font-semibold text-beltei-gold-dark">{{ __('Tip') }}:</span>
                                    {{ __('Leave the optional filters empty to include all courses, classes and students.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm, router, usePage } from '@inertiajs/vue3';
import { watch, computed, ref, nextTick } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FilterCombobox from '@/Components/FilterCombobox.vue';
import DatePicker from '@/Components/DatePicker.vue';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Card, CardContent } from '@/Components/ui/card';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { Doughnut, Bar } from 'vue-chartjs';
import { Chart as ChartJS, ArcElement, BarElement, CategoryScale, LinearScale, Tooltip, Legend } from 'chart.js';
import { __ } from '@/Composables/useTranslate';

ChartJS.register(ArcElement, BarElement, CategoryScale, LinearScale, Tooltip, Legend);

const props = defineProps({
    semesters: Array, courses: Array, classes: Array, subjects: Array,
    studentSummary: Array, overall: Object, semester: Object, filters: Object,
});

const form = useForm({
    semester_id: '', course_id: '', class_id: '', subject_id: '', status: '', date_from: '', date_to: '',
});

const page = usePage();
const errors = computed(() => page.props.errors || {});
const generating = ref(false);

// Keep the filter card in sync with the generated report (so re-generating is easy)
if (props.filters) {
    form.semester_id = props.filters.semester_id || '';
    form.course_id = props.filters.course_id || '';
    form.class_id = props.filters.class_id || '';
    form.subject_id = props.filters.subject_id || '';
    form.status = props.filters.status || '';
    form.date_from = props.filters.date_from || '';
    form.date_to = props.filters.date_to || '';
}

watch(() => form.semester_id, (id) => {
    if (id) {
        const sem = props.semesters.find(s => s.id == id);
        if (sem) { form.date_from = sem.start_date?.split('T')[0]; form.date_to = sem.end_date?.split('T')[0]; }
    }
});

// --- Quick range presets ---
const activePreset = ref('');
const toYmd = (d) => `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;

const presets = [
    { key: 'today', label: __('Today') },
    { key: 'week', label: __('This Week') },
    { key: 'month', label: __('This Month') },
    { key: 'semester', label: __('This Semester') },
];

const applyPreset = (key) => {
    const now = new Date();
    if (key === 'semester') {
        if (props.semesters?.length) form.semester_id = props.semesters[0].id; // watcher fills the dates
        activePreset.value = key;
        return;
    }
    form.semester_id = '';
    if (key === 'today') {
        form.date_from = toYmd(now);
        form.date_to = toYmd(now);
    } else if (key === 'week') {
        const d = new Date(now);
        const dow = (d.getDay() + 6) % 7; // Monday = 0
        d.setDate(d.getDate() - dow);
        form.date_from = toYmd(d);
        form.date_to = toYmd(now);
    } else if (key === 'month') {
        form.date_from = toYmd(new Date(now.getFullYear(), now.getMonth(), 1));
        form.date_to = toYmd(now);
    }
    activePreset.value = key;
};

const metricLegend = [
    { label: __('Present'), color: '#10b981' },
    { label: __('Late'), color: '#f59e0b' },
    { label: __('Absent'), color: '#f43f5e' },
    { label: __('Excused'), color: '#0ea5e9' },
];

const features = [
    __('Overall totals & attendance rate'),
    __('Per-student breakdown table'),
    __('Filter by course, class, subject or status'),
];

const semesterOptions = computed(() => (props.semesters || []).map(s => ({ value: s.id, label: `${s.name} (${s.academic_year})` })));
const courseOptions = computed(() => (props.courses || []).map(c => ({ value: c.id, label: c.name })));
const classOptions = computed(() => (props.classes || []).map(c => ({ value: c.id, label: c.name })));
const subjectOptions = computed(() => (props.subjects || []).map(s => ({ value: s.id, label: s.name })));
const reportStatusOptions = [
    { value: 'present', label: __('Present') },
    { value: 'absent', label: __('Absent') },
    { value: 'late', label: __('Late Only') },
    { value: 'excused', label: __('Excused') },
];

const submit = () => {
    router.get('/admin/reports/generate', {
        filter: {
            semester_id: form.semester_id || undefined,
            course_id: form.course_id || undefined,
            class_id: form.class_id || undefined,
            subject_id: form.subject_id || undefined,
            status: form.status || undefined,
            date_from: form.date_from || undefined,
            date_to: form.date_to || undefined,
        },
    }, {
        preserveScroll: true,
        preserveState: true,
        onStart: () => { generating.value = true; },
        onFinish: () => { generating.value = false; },
        onSuccess: () => nextTick(() => {
            document.getElementById('report-results')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }),
    });
};

// ===================== Results (rendered below the filter card) =====================
const hasReport = computed(() => !!props.overall);
const hasData = computed(() => (props.overall?.total ?? 0) > 0);

const className = computed(() => props.classes?.find(c => c.id == props.filters?.class_id)?.name || __('All Classes'));
const subjectName = computed(() => props.subjects?.find(s => s.id == props.filters?.subject_id)?.name || __('All Subjects'));
const courseName = computed(() => props.courses?.find(c => c.id == props.filters?.course_id)?.name || __('All Courses'));
const statusLabel = computed(() => props.filters?.status ? props.filters.status.charAt(0).toUpperCase() + props.filters.status.slice(1) : __('All Status'));
const scopeChips = computed(() => [courseName.value, className.value, subjectName.value, statusLabel.value]);

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '';

const glassTooltip = {
    backgroundColor: 'rgba(15, 23, 42, 0.92)',
    padding: 12, cornerRadius: 12, boxPadding: 6, usePointStyle: true,
    titleFont: { family: 'Plus Jakarta Sans', weight: '700', size: 13 },
    bodyFont: { family: 'Plus Jakarta Sans', size: 12 },
};

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
