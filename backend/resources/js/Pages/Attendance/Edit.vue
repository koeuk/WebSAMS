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
        <div class="flex items-center gap-4 mb-6">
            <Link href="/admin/attendance" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back</Link>
            <h2 class="text-2xl font-bold text-gray-900">Edit Attendance</h2>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 max-w-xl">
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Class - Subject (Teacher)</label>
                    <select v-model="form.class_subject_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option v-for="cs in classSubjects" :key="cs.id" :value="cs.id">{{ csLabel(cs) }}</option>
                    </select>
                    <p v-if="form.errors.class_subject_id" class="text-sm text-red-600 mt-1">{{ form.errors.class_subject_id }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Student</label>
                    <select v-model="form.student_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option v-for="s in students" :key="s.id" :value="s.id">{{ s.name }} ({{ s.email }})</option>
                    </select>
                    <p v-if="form.errors.student_id" class="text-sm text-red-600 mt-1">{{ form.errors.student_id }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input v-model="form.date" type="date" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time Slot</label>
                    <select v-model="form.time_slot_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option v-for="ts in timeSlots" :key="ts.id" :value="ts.id">{{ ts.name }} ({{ ts.start_time?.slice(0,5) }} - {{ ts.end_time?.slice(0,5) }})</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select v-model="form.status" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="present">Present</option>
                        <option value="absent">Absent</option>
                        <option value="late">Late</option>
                        <option value="excused">Excused</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                    <textarea v-model="form.remarks" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="Optional" />
                </div>
                <button type="submit" :disabled="form.processing" class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark disabled:opacity-50">
                    {{ form.processing ? 'Saving...' : 'Save Changes' }}
                </button>
            </form>
        </div>
    </AdminLayout>
</template>
