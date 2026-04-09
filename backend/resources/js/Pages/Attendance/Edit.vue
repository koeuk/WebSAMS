<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ record: Object, classSubjects: Array, students: Array, timeSlots: Array });

const form = useForm({
    class_subject_id: props.record.class_subject_id,
    student_id: props.record.student_id,
    date: props.record.date?.split('T')[0],
    time_slot_id: props.record.time_slot_id,
    status: props.record.status,
    remarks: props.record.remarks || '',
});

const submit = () => { form.put(`/admin/attendance/${props.record.id}`); };
const csLabel = (cs) => `${cs.school_class?.name} - ${cs.subject?.name} (${cs.teacher?.name})`;
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center gap-4 mb-8">
                <Link href="/admin/attendance" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                    Back
                </Link>
                <div class="h-5 w-px bg-slate-200"></div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Attendance</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Update attendance record</p>
                </div>
            </div>

            <div class="card p-8 max-w-xl">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Class - Subject (Teacher)</label>
                        <select v-model="form.class_subject_id" required class="select-modern w-full">
                            <option v-for="cs in classSubjects" :key="cs.id" :value="cs.id">{{ csLabel(cs) }}</option>
                        </select>
                        <p v-if="form.errors.class_subject_id" class="text-[12px] text-rose-500 mt-1">{{ form.errors.class_subject_id }}</p>
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Student</label>
                        <select v-model="form.student_id" required class="select-modern w-full">
                            <option v-for="s in students" :key="s.id" :value="s.id">{{ s.name }} ({{ s.email }})</option>
                        </select>
                        <p v-if="form.errors.student_id" class="text-[12px] text-rose-500 mt-1">{{ form.errors.student_id }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Date</label>
                            <input v-model="form.date" type="date" required class="input-modern" />
                        </div>
                        <div>
                            <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Time Slot</label>
                            <select v-model="form.time_slot_id" required class="select-modern w-full">
                                <option v-for="ts in timeSlots" :key="ts.id" :value="ts.id">{{ ts.name }} ({{ ts.start_time?.slice(0,5) }} - {{ ts.end_time?.slice(0,5) }})</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Status</label>
                        <select v-model="form.status" required class="select-modern w-full">
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                            <option value="excused">Excused</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Remarks</label>
                        <textarea v-model="form.remarks" rows="2" class="input-modern" placeholder="Optional" />
                    </div>
                    <div class="pt-2">
                        <button type="submit" :disabled="form.processing" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                            {{ form.processing ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
