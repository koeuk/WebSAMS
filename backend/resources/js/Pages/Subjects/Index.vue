<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    subjects: Object,
    courses: Array,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const courseFilter = ref(props.filters?.course_id || '');

const applyFilters = () => {
    router.get('/admin/subjects', {
        search: search.value || undefined,
        course_id: courseFilter.value || undefined,
    }, { preserveState: true });
};

const showDeleteModal = ref(false);
const subjectToDelete = ref(null);

const confirmDelete = (subject) => { subjectToDelete.value = subject; showDeleteModal.value = true; };
const deleteSubject = () => {
    router.delete(`/admin/subjects/${subjectToDelete.value.id}`, {
        onFinish: () => { showDeleteModal.value = false; subjectToDelete.value = null; },
    });
};
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Subjects</h2>
            <Link href="/admin/subjects/create" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-md hover:bg-gray-800">Create Subject</Link>
        </div>

        <FlashMessage />

        <div class="flex gap-4 mb-4">
            <input v-model="search" type="text" placeholder="Search subjects..." class="px-3 py-2 border border-gray-300 rounded-md text-sm w-64" @keyup.enter="applyFilters" />
            <select v-model="courseFilter" class="px-3 py-2 border border-gray-300 rounded-md text-sm" @change="applyFilters">
                <option value="">All Courses</option>
                <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Code</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Course</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="subject in subjects.data" :key="subject.id" class="border-b border-gray-100">
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ subject.name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ subject.code }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ subject.course?.name }}</td>
                        <td class="px-6 py-3 text-right">
                            <Link :href="`/admin/subjects/${subject.id}/edit`" class="text-sm text-blue-600 hover:text-blue-800 mr-3">Edit</Link>
                            <button @click="confirmDelete(subject)" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="!subjects.data?.length">
                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">No subjects found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :links="subjects.links" />
        <Modal :show="showDeleteModal" title="Delete Subject" :message="`Delete ${subjectToDelete?.name}?`" @confirm="deleteSubject" @cancel="showDeleteModal = false" />
    </AdminLayout>
</template>
