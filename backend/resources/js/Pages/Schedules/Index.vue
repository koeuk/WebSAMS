<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({ schedules: Object });

const days = { mon: 'Monday', tue: 'Tuesday', wed: 'Wednesday', thu: 'Thursday', fri: 'Friday', sat: 'Saturday', sun: 'Sunday' };

const showDeleteModal = ref(false);
const scheduleToDelete = ref(null);
const confirmDelete = (s) => { scheduleToDelete.value = s; showDeleteModal.value = true; };
const deleteSchedule = () => {
    router.delete(`/admin/schedules/${scheduleToDelete.value.id}`, {
        onFinish: () => { showDeleteModal.value = false; scheduleToDelete.value = null; },
    });
};
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Schedules</h2>
            <Link href="/admin/schedules/create" class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark">Create Schedule</Link>
        </div>

        <FlashMessage />

        <div v-for="(daySchedules, day) in schedules" :key="day" class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ days[day] || day }}</h3>
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Time</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Class</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Subject</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Teacher</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Room</th>
                            <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="s in daySchedules" :key="s.id" class="border-b border-gray-100">
                            <td class="px-6 py-3 text-sm text-gray-900">{{ s.time_slot?.name }} ({{ s.time_slot?.start_time?.slice(0,5) }} - {{ s.time_slot?.end_time?.slice(0,5) }})</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ s.class_subject?.school_class?.name }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ s.class_subject?.subject?.name }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ s.class_subject?.teacher?.name }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ s.room || '-' }}</td>
                            <td class="px-6 py-3 text-right">
                                <Link :href="`/admin/schedules/${s.id}/edit`" class="text-sm text-blue-600 hover:text-blue-800 mr-3">Edit</Link>
                                <button @click="confirmDelete(s)" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-if="!Object.keys(schedules || {}).length" class="bg-white rounded-lg border border-gray-200 p-8 text-center text-sm text-gray-500">
            No schedules created yet.
        </div>

        <Modal :show="showDeleteModal" title="Delete Schedule" message="Are you sure?" @confirm="deleteSchedule" @cancel="showDeleteModal = false" />
    </AdminLayout>
</template>
