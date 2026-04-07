<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({ semesters: Object });

const showDeleteModal = ref(false);
const semesterToDelete = ref(null);
const confirmDelete = (s) => { semesterToDelete.value = s; showDeleteModal.value = true; };
const deleteSemester = () => {
    router.delete(`/admin/semesters/${semesterToDelete.value.id}`, {
        onFinish: () => { showDeleteModal.value = false; semesterToDelete.value = null; },
    });
};

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '-';
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Semesters</h2>
            <Link href="/admin/semesters/create" class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark">Create Semester</Link>
        </div>

        <FlashMessage />

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Academic Year</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Start Date</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">End Date</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="s in semesters.data" :key="s.id" class="border-b border-gray-100">
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ s.name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ s.academic_year }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ formatDate(s.start_date) }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ formatDate(s.end_date) }}</td>
                        <td class="px-6 py-3 text-right">
                            <Link :href="`/admin/semesters/${s.id}/edit`" class="text-sm text-blue-600 hover:text-blue-800 mr-3">Edit</Link>
                            <button @click="confirmDelete(s)" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="!semesters.data?.length">
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No semesters found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :links="semesters.links" />
        <Modal :show="showDeleteModal" title="Delete Semester" :message="`Delete ${semesterToDelete?.name}?`" @confirm="deleteSemester" @cancel="showDeleteModal = false" />
    </AdminLayout>
</template>
