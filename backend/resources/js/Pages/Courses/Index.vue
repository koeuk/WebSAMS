<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({ courses: Object, filters: Object });
const search = ref(props.filters?.search || '');

const applyFilters = () => {
    router.get('/admin/courses', { search: search.value || undefined }, { preserveState: true });
};

const showDeleteModal = ref(false);
const courseToDelete = ref(null);
const confirmDelete = (course) => { courseToDelete.value = course; showDeleteModal.value = true; };
const deleteCourse = () => {
    router.delete(`/admin/courses/${courseToDelete.value.id}`, {
        onFinish: () => { showDeleteModal.value = false; courseToDelete.value = null; },
    });
};
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Courses</h2>
                    <p class="text-sm text-slate-500 mt-1">Manage academic courses and programs</p>
                </div>
                <Link href="/admin/courses/create" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Create Course
                </Link>
            </div>

            <FlashMessage />

            <div class="card p-4 mb-6">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Search</label>
                    <input v-model="search" type="text" placeholder="Search courses..." class="input-modern" @keyup.enter="applyFilters" />
                </div>
            </div>

            <div class="card overflow-hidden">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th class="text-left">Name</th>
                            <th class="text-left">Code</th>
                            <th class="text-left">Subjects</th>
                            <th class="text-left">Description</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="course in courses.data" :key="course.id">
                            <td class="font-semibold text-slate-900">{{ course.name }}</td>
                            <td><span class="badge bg-slate-100 text-slate-600 ring-1 ring-slate-200 font-mono">{{ course.code }}</span></td>
                            <td class="font-mono text-[13px]">{{ course.subjects_count }}</td>
                            <td class="max-w-[200px] truncate">{{ course.description || '-' }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <Link :href="`/admin/courses/${course.id}/edit`" class="px-2.5 py-1.5 text-[12px] font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">Edit</Link>
                                    <button @click="confirmDelete(course)" class="px-2.5 py-1.5 text-[12px] font-medium text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!courses.data?.length">
                            <td colspan="5" class="!text-center !py-12 text-slate-400">No courses found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination :links="courses.links" />
            <Modal :show="showDeleteModal" title="Delete Course" :message="`Delete ${courseToDelete?.name}? This will also delete all subjects under it.`" @confirm="deleteCourse" @cancel="showDeleteModal = false" />
        </div>
    </AdminLayout>
</template>
