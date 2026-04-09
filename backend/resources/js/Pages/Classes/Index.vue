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
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Classes</h2>
                    <p class="text-sm text-slate-500 mt-1">Manage classes and sections</p>
                </div>
                <Link href="/admin/classes/create" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Create Class
                </Link>
            </div>

            <FlashMessage />

            <div class="card p-4 mb-6">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Search</label>
                    <input v-model="search" type="text" placeholder="Search classes..." class="input-modern" @keyup.enter="applyFilters" />
                </div>
            </div>

            <div class="card overflow-hidden">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th class="text-left">Name</th>
                            <th class="text-left">Section</th>
                            <th class="text-left">Academic Year</th>
                            <th class="text-left">Subjects</th>
                            <th class="text-left">Students</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="c in classes.data" :key="c.id">
                            <td class="font-semibold text-slate-900">
                                <Link :href="`/admin/classes/${c.id}`" class="hover:text-beltei transition-colors">{{ c.name }}</Link>
                            </td>
                            <td>{{ c.section || '-' }}</td>
                            <td><span class="badge bg-slate-100 text-slate-600 ring-1 ring-slate-200">{{ c.academic_year }}</span></td>
                            <td class="font-mono text-[13px]">{{ c.subjects_count }}</td>
                            <td class="font-mono text-[13px]">{{ c.students_count }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <Link :href="`/admin/classes/${c.id}`" class="px-2.5 py-1.5 text-[12px] font-medium text-slate-500 hover:text-slate-700 hover:bg-slate-50 rounded-lg transition-colors">View</Link>
                                    <Link :href="`/admin/classes/${c.id}/edit`" class="px-2.5 py-1.5 text-[12px] font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">Edit</Link>
                                    <button @click="confirmDelete(c)" class="px-2.5 py-1.5 text-[12px] font-medium text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!classes.data?.length">
                            <td colspan="6" class="!text-center !py-12 text-slate-400">No classes found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination :links="classes.links" />
            <Modal :show="showDeleteModal" title="Delete Class" :message="`Delete ${classToDelete?.name}?`" @confirm="deleteClass" @cancel="showDeleteModal = false" />
        </div>
    </AdminLayout>
</template>
