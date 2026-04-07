<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ classSubjects: Array, timeSlots: Array });

const form = useForm({
    class_subject_id: '',
    time_slot_id: '',
    day_of_week: '',
    room: '',
});

const submit = () => { form.post('/admin/schedules'); };
const csLabel = (cs) => `${cs.school_class?.name} - ${cs.subject?.name} (${cs.teacher?.name})`;
</script>

<template>
    <AdminLayout>
        <div class="flex items-center gap-4 mb-6">
            <Link href="/admin/schedules" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back</Link>
            <h2 class="text-2xl font-bold text-gray-900">Create Schedule</h2>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 max-w-xl">
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Class - Subject (Teacher)</label>
                    <select v-model="form.class_subject_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="" disabled>Select</option>
                        <option v-for="cs in classSubjects" :key="cs.id" :value="cs.id">{{ csLabel(cs) }}</option>
                    </select>
                    <p v-if="form.errors.class_subject_id" class="text-sm text-red-600 mt-1">{{ form.errors.class_subject_id }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time Slot</label>
                    <select v-model="form.time_slot_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="" disabled>Select Time Slot</option>
                        <option v-for="ts in timeSlots" :key="ts.id" :value="ts.id">{{ ts.name }} ({{ ts.start_time?.slice(0,5) }} - {{ ts.end_time?.slice(0,5) }})</option>
                    </select>
                    <p v-if="form.errors.time_slot_id" class="text-sm text-red-600 mt-1">{{ form.errors.time_slot_id }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Day</label>
                    <select v-model="form.day_of_week" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="" disabled>Select Day</option>
                        <option value="mon">Monday</option>
                        <option value="tue">Tuesday</option>
                        <option value="wed">Wednesday</option>
                        <option value="thu">Thursday</option>
                        <option value="fri">Friday</option>
                        <option value="sat">Saturday</option>
                        <option value="sun">Sunday</option>
                    </select>
                    <p v-if="form.errors.day_of_week" class="text-sm text-red-600 mt-1">{{ form.errors.day_of_week }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Room</label>
                    <input v-model="form.room" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g. Room 201" />
                </div>
                <button type="submit" :disabled="form.processing" class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark disabled:opacity-50">
                    {{ form.processing ? 'Creating...' : 'Create Schedule' }}
                </button>
            </form>
        </div>
    </AdminLayout>
</template>
