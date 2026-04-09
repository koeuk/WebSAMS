<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({ schedules: Object });

const days = { mon: 'Monday', tue: 'Tuesday', wed: 'Wednesday', thu: 'Thursday', fri: 'Friday', sat: 'Saturday', sun: 'Sunday' };
const dayColors = { mon: 'bg-blue-50 text-blue-700', tue: 'bg-emerald-50 text-emerald-700', wed: 'bg-amber-50 text-amber-700', thu: 'bg-violet-50 text-violet-700', fri: 'bg-rose-50 text-rose-700', sat: 'bg-sky-50 text-sky-700', sun: 'bg-slate-100 text-slate-600' };

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
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Schedules</h2>
                    <p class="text-sm text-slate-500 mt-1">Weekly class schedules overview</p>
                </div>
                <Link href="/admin/schedules/create" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Create Schedule
                </Link>
            </div>

            <FlashMessage />

            <div class="space-y-6 stagger-children">
                <div v-for="(daySchedules, day) in schedules" :key="day" class="animate-fade-in-up">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="badge" :class="dayColors[day] || 'bg-slate-100 text-slate-600'">{{ days[day] || day }}</span>
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </div>
                    <div class="card overflow-hidden">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th class="text-left">Time</th>
                                    <th class="text-left">Class</th>
                                    <th class="text-left">Subject</th>
                                    <th class="text-left">Teacher</th>
                                    <th class="text-left">Room</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="s in daySchedules" :key="s.id">
                                    <td class="font-medium text-slate-900 whitespace-nowrap">{{ s.time_slot?.name }} ({{ s.time_slot?.start_time?.slice(0,5) }} - {{ s.time_slot?.end_time?.slice(0,5) }})</td>
                                    <td>{{ s.class_subject?.school_class?.name }}</td>
                                    <td>{{ s.class_subject?.subject?.name }}</td>
                                    <td>{{ s.class_subject?.teacher?.name }}</td>
                                    <td>{{ s.room || '-' }}</td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <Link :href="`/admin/schedules/${s.id}/edit`" class="px-2.5 py-1.5 text-[12px] font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">Edit</Link>
                                            <button @click="confirmDelete(s)" class="px-2.5 py-1.5 text-[12px] font-medium text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div v-if="!Object.keys(schedules || {}).length" class="card p-12 text-center text-slate-400">
                No schedules created yet.
            </div>

            <Modal :show="showDeleteModal" title="Delete Schedule" message="Are you sure?" @confirm="deleteSchedule" @cancel="showDeleteModal = false" />
        </div>
    </AdminLayout>
</template>
