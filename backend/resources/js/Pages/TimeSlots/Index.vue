<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({ timeSlots: Array });

const showDeleteModal = ref(false);
const toDelete = ref(null);
const confirmDelete = (ts) => { toDelete.value = ts; showDeleteModal.value = true; };
const deleteSlot = () => {
    router.delete(`/admin/time-slots/${toDelete.value.id}`, {
        onFinish: () => { showDeleteModal.value = false; toDelete.value = null; },
    });
};

const typeBadgeClass = (type) => ({
    'bg-sky-50 text-sky-700 ring-1 ring-sky-200': type === 'morning',
    'bg-amber-50 text-amber-700 ring-1 ring-amber-200': type === 'afternoon',
    'bg-violet-50 text-violet-700 ring-1 ring-violet-200': type === 'evening',
});
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Time Slots</h2>
                    <p class="text-sm text-slate-500 mt-1">Manage class time periods</p>
                </div>
                <Link href="/admin/time-slots/create" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Create Time Slot
                </Link>
            </div>

            <FlashMessage />

            <div class="card overflow-hidden">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th class="text-left">Name</th>
                            <th class="text-left">Start</th>
                            <th class="text-left">End</th>
                            <th class="text-left">Type</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ts in timeSlots" :key="ts.id">
                            <td class="font-semibold text-slate-900">{{ ts.name }}</td>
                            <td class="font-mono text-[13px]">{{ ts.start_time?.slice(0,5) }}</td>
                            <td class="font-mono text-[13px]">{{ ts.end_time?.slice(0,5) }}</td>
                            <td><span class="badge" :class="typeBadgeClass(ts.type)">{{ ts.type }}</span></td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <Link :href="`/admin/time-slots/${ts.id}/edit`" class="px-2.5 py-1.5 text-[12px] font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">Edit</Link>
                                    <button @click="confirmDelete(ts)" class="px-2.5 py-1.5 text-[12px] font-medium text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!timeSlots?.length">
                            <td colspan="5" class="!text-center !py-12 text-slate-400">No time slots.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Modal :show="showDeleteModal" title="Delete Time Slot" :message="`Delete ${toDelete?.name}?`" @confirm="deleteSlot" @cancel="showDeleteModal = false" />
        </div>
    </AdminLayout>
</template>
