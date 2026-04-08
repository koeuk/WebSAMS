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

const typeBadge = (type) => ({
    'bg-blue-100 text-blue-800': type === 'morning',
    'bg-orange-100 text-orange-800': type === 'afternoon',
    'bg-purple-100 text-purple-800': type === 'evening',
});
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Time Slots</h2>
            <Link href="/admin/time-slots/create" class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark">Create Time Slot</Link>
        </div>

        <FlashMessage />

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Start</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">End</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ts in timeSlots" :key="ts.id" class="border-b border-gray-100">
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ ts.name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ ts.start_time?.slice(0,5) }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ ts.end_time?.slice(0,5) }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full" :class="typeBadge(ts.type)">{{ ts.type }}</span>
                        </td>
                        <td class="px-6 py-3 text-right">
                            <Link :href="`/admin/time-slots/${ts.id}/edit`" class="text-sm text-blue-600 hover:text-blue-800 mr-3">Edit</Link>
                            <button @click="confirmDelete(ts)" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="!timeSlots?.length">
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No time slots.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Modal :show="showDeleteModal" title="Delete Time Slot" :message="`Delete ${toDelete?.name}?`" @confirm="deleteSlot" @cancel="showDeleteModal = false" />
    </AdminLayout>
</template>
