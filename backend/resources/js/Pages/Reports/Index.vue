<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Reports</h2>
                <p class="text-sm text-slate-500 mt-1">Generate attendance reports with custom filters</p>
            </div>

            <Card class="max-w-xl">
                <CardContent class="p-8">
                    <form @submit.prevent="submit" class="space-y-5">
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Semester</Label>
                            <FilterCombobox v-model="form.semester_id" :options="semesterOptions" placeholder="Custom Date Range" class="w-full" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Date From</Label>
                                <DatePicker v-model="form.date_from" placeholder="Start date" />
                                <p v-if="form.errors.date_from" class="text-[12px] text-rose-500 mt-1">{{ form.errors.date_from }}</p>
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Date To</Label>
                                <DatePicker v-model="form.date_to" placeholder="End date" />
                                <p v-if="form.errors.date_to" class="text-[12px] text-rose-500 mt-1">{{ form.errors.date_to }}</p>
                            </div>
                        </div>
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Course <span class="text-slate-400">(optional)</span></Label>
                            <FilterCombobox v-model="form.course_id" :options="courseOptions" placeholder="All Courses" class="w-full" />
                        </div>
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Class <span class="text-slate-400">(optional)</span></Label>
                            <FilterCombobox v-model="form.class_id" :options="classOptions" placeholder="All Classes" class="w-full" />
                        </div>
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Subject <span class="text-slate-400">(optional)</span></Label>
                            <FilterCombobox v-model="form.subject_id" :options="subjectOptions" placeholder="All Subjects" class="w-full" />
                        </div>
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Status Filter <span class="text-slate-400">(optional)</span></Label>
                            <FilterCombobox v-model="form.status" :options="reportStatusOptions" placeholder="All Status" class="w-full" />
                        </div>
                        <div class="pt-2">
                            <Button type="submit" :disabled="form.processing">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                                {{ form.processing ? 'Generating...' : 'Generate Report' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm, router, Link } from '@inertiajs/vue3';
import { watch, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FilterCombobox from '@/Components/FilterCombobox.vue';
import DatePicker from '@/Components/DatePicker.vue';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Card, CardContent } from '@/Components/ui/card';

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

const semesterOptions = computed(() => (props.semesters || []).map(s => ({ value: s.id, label: `${s.name} (${s.academic_year})` })));
const courseOptions = computed(() => (props.courses || []).map(c => ({ value: c.id, label: c.name })));
const classOptions = computed(() => (props.classes || []).map(c => ({ value: c.id, label: c.name })));
const subjectOptions = computed(() => (props.subjects || []).map(s => ({ value: s.id, label: s.name })));
const reportStatusOptions = [
    { value: 'present', label: 'Present' },
    { value: 'absent', label: 'Absent' },
    { value: 'late', label: 'Late Only' },
    { value: 'excused', label: 'Excused' },
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
