<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ schedule: Object, classSubjects: Array, timeSlots: Array });

const form = useForm({
    class_subject_id: props.schedule.class_subject_id,
    time_slot_id: props.schedule.time_slot_id,
    day_of_week: props.schedule.day_of_week,
    room: props.schedule.room || '',
});

const submit = () => { form.put(`/admin/schedules/${props.schedule.id}`); };
const csLabel = (cs) => `${cs.school_class?.name} - ${cs.subject?.name} (${cs.teacher?.name})`;
</script>

<template>
    <AdminLayout>
        <div class="flex items-center gap-4 mb-6">
            <Link href="/admin/schedules" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back</Link>
            <h2 class="text-2xl font-bold text-gray-900">Edit Schedule</h2>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 max-w-xl">
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Class - Subject (Teacher)</label>
                    <select v-model="form.class_subject_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option v-for="cs in classSubjects" :key="cs.id" :value="cs.id">{{ csLabel(cs) }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time Slot</label>
                    <select v-model="form.time_slot_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option v-for="ts in timeSlots" :key="ts.id" :value="ts.id">{{ ts.name }} ({{ ts.start_time?.slice(0,5) }} - {{ ts.end_time?.slice(0,5) }})</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Day</label>
                    <select v-model="form.day_of_week" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="mon">Monday</option>
                        <option value="tue">Tuesday</option>
                        <option value="wed">Wednesday</option>
                        <option value="thu">Thursday</option>
                        <option value="fri">Friday</option>
                        <option value="sat">Saturday</option>
                        <option value="sun">Sunday</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Room</label>
                    <input v-model="form.room" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                </div>
                <button type="submit" :disabled="form.processing" class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark disabled:opacity-50">
                    {{ form.processing ? 'Saving...' : 'Save Changes' }}
                </button>
            </form>
        </div>
    </AdminLayout>
</template>
