<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { watch } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

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

const submit = () => { form.get('/admin/reports/generate'); };
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Reports</h2>
                <p class="text-sm text-slate-500 mt-1">Generate attendance reports with custom filters</p>
            </div>

            <div class="card p-8 max-w-xl">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Semester</label>
                        <select v-model="form.semester_id" class="select-modern w-full">
                            <option value="">Custom Date Range</option>
                            <option v-for="s in semesters" :key="s.id" :value="s.id">{{ s.name }} ({{ s.academic_year }})</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Date From</label>
                            <input v-model="form.date_from" type="date" :required="!form.semester_id" class="input-modern" />
                            <p v-if="form.errors.date_from" class="text-[12px] text-rose-500 mt-1">{{ form.errors.date_from }}</p>
                        </div>
                        <div>
                            <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Date To</label>
                            <input v-model="form.date_to" type="date" :required="!form.semester_id" class="input-modern" />
                            <p v-if="form.errors.date_to" class="text-[12px] text-rose-500 mt-1">{{ form.errors.date_to }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Course <span class="text-slate-400">(optional)</span></label>
                        <select v-model="form.course_id" class="select-modern w-full">
                            <option value="">All Courses</option>
                            <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Class <span class="text-slate-400">(optional)</span></label>
                        <select v-model="form.class_id" class="select-modern w-full">
                            <option value="">All Classes</option>
                            <option v-for="c in classes" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Subject <span class="text-slate-400">(optional)</span></label>
                        <select v-model="form.subject_id" class="select-modern w-full">
                            <option value="">All Subjects</option>
                            <option v-for="s in subjects" :key="s.id" :value="s.id">{{ s.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Status Filter <span class="text-slate-400">(optional)</span></label>
                        <select v-model="form.status" class="select-modern w-full">
                            <option value="">All Status</option>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late Only</option>
                            <option value="excused">Excused</option>
                        </select>
                    </div>
                    <div class="pt-2">
                        <button type="submit" :disabled="form.processing" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                            {{ form.processing ? 'Generating...' : 'Generate Report' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
