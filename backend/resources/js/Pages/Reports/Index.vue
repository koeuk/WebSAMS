<template>
    <AdminLayout>
        <div class="animate-fade-in max-w-6xl">
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
                                    <p v-if="form.errors.date_from" class="text-[12px] text-rose-500 mt-1">{{ form.errors.date_from }}</p>
                                </div>
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Date To') }}</Label>
                                    <DatePicker v-model="form.date_to" :placeholder="__('End date')" />
                                    <p v-if="form.errors.date_to" class="text-[12px] text-rose-500 mt-1">{{ form.errors.date_to }}</p>
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
                            :disabled="form.processing"
                            class="w-full h-12 text-[15px] gap-2 bg-gradient-to-r from-beltei to-beltei-dark hover:from-beltei-dark hover:to-beltei shadow-lg shadow-beltei/25"
                        >
                            <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                            {{ form.processing ? __('Generating...') : __('Generate Report') }}
                        </Button>
                    </form>
                </CardContent>
            </Card>

            <!-- Info strip -->
            <Card class="mt-6 animate-fade-in-up" style="animation-delay: 90ms;">
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
import { useForm, router } from '@inertiajs/vue3';
import { watch, computed, ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FilterCombobox from '@/Components/FilterCombobox.vue';
import DatePicker from '@/Components/DatePicker.vue';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Card, CardContent } from '@/Components/ui/card';
import { __ } from '@/Composables/useTranslate';

const props = defineProps({ semesters: Array, courses: Array, classes: Array, subjects: Array });

const form = useForm({
    semester_id: '', course_id: '', class_id: '', subject_id: '', status: '', date_from: '', date_to: '',
});

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
    });
};
</script>
