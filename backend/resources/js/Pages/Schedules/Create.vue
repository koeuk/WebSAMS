<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ classSubjects: Array });

const form = useForm({
    class_subject_id: '',
    day_of_week: '',
    start_time: '',
    end_time: '',
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Day</label>
                    <select v-model="form.day_of_week" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="" disabled>Select Day</option>
                        <option value="mon">Monday</option>
                        <option value="tue">Tuesday</option>
                        <option value="wed">Wednesday</option>
                        <option value="thu">Thursday</option>
                        <option value="fri">Friday</option>
                    </select>
                    <p v-if="form.errors.day_of_week" class="text-sm text-red-600 mt-1">{{ form.errors.day_of_week }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                        <input v-model="form.start_time" type="time" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                        <p v-if="form.errors.start_time" class="text-sm text-red-600 mt-1">{{ form.errors.start_time }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                        <input v-model="form.end_time" type="time" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                        <p v-if="form.errors.end_time" class="text-sm text-red-600 mt-1">{{ form.errors.end_time }}</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Room</label>
                    <input v-model="form.room" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g. Room 201" />
                </div>
                <button type="submit" :disabled="form.processing" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-md hover:bg-gray-800 disabled:opacity-50">
                    {{ form.processing ? 'Creating...' : 'Create Schedule' }}
                </button>
            </form>
        </div>
    </AdminLayout>
</template>
