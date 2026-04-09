<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({ subjects: Object, courses: Array, filters: Object });
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
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Subjects</h2>
                    <p class="text-sm text-slate-500 mt-1">Manage subjects across courses</p>
                </div>
                <Link href="/admin/subjects/create" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Create Subject
                </Link>
            </div>

            <FlashMessage />

            <div class="card p-4 mb-6">
                <div class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Search</label>
                        <input v-model="search" type="text" placeholder="Search subjects..." class="input-modern" @keyup.enter="applyFilters" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Course</label>
                        <select v-model="courseFilter" class="select-modern" @change="applyFilters">
                            <option value="">All Courses</option>
                            <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card overflow-hidden">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th class="text-left">Name</th>
                            <th class="text-left">Code</th>
                            <th class="text-left">Course</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="subject in subjects.data" :key="subject.id">
                            <td class="font-semibold text-slate-900">{{ subject.name }}</td>
                            <td><span class="badge bg-slate-100 text-slate-600 ring-1 ring-slate-200 font-mono">{{ subject.code }}</span></td>
                            <td>{{ subject.course?.name }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <Link :href="`/admin/subjects/${subject.id}/edit`" class="px-2.5 py-1.5 text-[12px] font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">Edit</Link>
                                    <button @click="confirmDelete(subject)" class="px-2.5 py-1.5 text-[12px] font-medium text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!subjects.data?.length">
                            <td colspan="4" class="!text-center !py-12 text-slate-400">No subjects found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination :links="subjects.links" />
            <Modal :show="showDeleteModal" title="Delete Subject" :message="`Delete ${subjectToDelete?.name}?`" @confirm="deleteSubject" @cancel="showDeleteModal = false" />
        </div>
    </AdminLayout>
</template>
