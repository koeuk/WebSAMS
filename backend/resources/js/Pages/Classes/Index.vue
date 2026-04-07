<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({ classes: Object, filters: Object });
const search = ref(props.filters?.search || '');

const applyFilters = () => {
    router.get('/admin/classes', { search: search.value || undefined }, { preserveState: true });
};

const showDeleteModal = ref(false);
const classToDelete = ref(null);
const confirmDelete = (c) => { classToDelete.value = c; showDeleteModal.value = true; };
const deleteClass = () => {
    router.delete(`/admin/classes/${classToDelete.value.id}`, {
        onFinish: () => { showDeleteModal.value = false; classToDelete.value = null; },
    });
};
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Classes</h2>
            <Link href="/admin/classes/create" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-md hover:bg-gray-800">Create Class</Link>
        </div>

        <FlashMessage />

        <div class="flex gap-4 mb-4">
            <input v-model="search" type="text" placeholder="Search classes..." class="px-3 py-2 border border-gray-300 rounded-md text-sm w-64" @keyup.enter="applyFilters" />
        </div>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Section</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Academic Year</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Subjects</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Students</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="c in classes.data" :key="c.id" class="border-b border-gray-100">
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">
                            <Link :href="`/admin/classes/${c.id}`" class="text-blue-600 hover:text-blue-800">{{ c.name }}</Link>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ c.section || '-' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ c.academic_year }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ c.subjects_count }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ c.students_count }}</td>
                        <td class="px-6 py-3 text-right">
                            <Link :href="`/admin/classes/${c.id}`" class="text-sm text-gray-600 hover:text-gray-800 mr-3">View</Link>
                            <Link :href="`/admin/classes/${c.id}/edit`" class="text-sm text-blue-600 hover:text-blue-800 mr-3">Edit</Link>
                            <button @click="confirmDelete(c)" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="!classes.data?.length">
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">No classes found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :links="classes.links" />
        <Modal :show="showDeleteModal" title="Delete Class" :message="`Delete ${classToDelete?.name}?`" @confirm="deleteClass" @cancel="showDeleteModal = false" />
    </AdminLayout>
</template>
