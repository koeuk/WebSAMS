<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    courses: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');

const applyFilters = () => {
    router.get('/admin/courses', { search: search.value || undefined }, { preserveState: true });
};

const showDeleteModal = ref(false);
const courseToDelete = ref(null);

const confirmDelete = (course) => {
    courseToDelete.value = course;
    showDeleteModal.value = true;
};

const deleteCourse = () => {
    router.delete(`/admin/courses/${courseToDelete.value.id}`, {
        onFinish: () => { showDeleteModal.value = false; courseToDelete.value = null; },
    });
};
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Courses</h2>
            <Link href="/admin/courses/create" class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark">
                Create Course
            </Link>
        </div>

        <FlashMessage />

        <div class="flex gap-4 mb-4">
            <input v-model="search" type="text" placeholder="Search courses..." class="px-3 py-2 border border-gray-300 rounded-md text-sm w-64" @keyup.enter="applyFilters" />
        </div>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Code</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Subjects</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="course in courses.data" :key="course.id" class="border-b border-gray-100">
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ course.name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ course.code }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ course.subjects_count }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ course.description || '-' }}</td>
                        <td class="px-6 py-3 text-right">
                            <Link :href="`/admin/courses/${course.id}/edit`" class="text-sm text-blue-600 hover:text-blue-800 mr-3">Edit</Link>
                            <button @click="confirmDelete(course)" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="!courses.data?.length">
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No courses found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :links="courses.links" />

        <Modal :show="showDeleteModal" title="Delete Course" :message="`Delete ${courseToDelete?.name}? This will also delete all subjects under it.`" @confirm="deleteCourse" @cancel="showDeleteModal = false" />
    </AdminLayout>
</template>
