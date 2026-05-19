<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';
import ModalForm from '@/Components/ModalForm.vue';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Label } from '@/Components/ui/label';
import { Textarea } from '@/Components/ui/textarea';
import { Card, CardContent } from '@/Components/ui/card';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';

const props = defineProps({ courses: Object, filters: Object });
const search = ref(props.filters?.search || '');

const applyFilters = () => {
    router.get('/admin/courses', { search: search.value || undefined }, { preserveState: true });
};

const clearFilters = () => {
    search.value = '';
    router.get('/admin/courses', {}, { preserveState: false });
};

// Create dialog
const showCreateDialog = ref(false);
const createForm = useForm({ name: '', code: '', description: '' });
const submitCreate = () => {
    createForm.post('/admin/courses', {
        onSuccess: () => { showCreateDialog.value = false; createForm.reset(); },
    });
};

// Edit dialog
const showEditDialog = ref(false);
const editingCourse = ref(null);
const editForm = useForm({ name: '', code: '', description: '' });
const openEdit = (course) => {
    editingCourse.value = course;
    editForm.name = course.name;
    editForm.code = course.code;
    editForm.description = course.description || '';
    showEditDialog.value = true;
};
const submitEdit = () => {
    editForm.put(`/admin/courses/${editingCourse.value.id}`, {
        onSuccess: () => { showEditDialog.value = false; editingCourse.value = null; },
    });
};

// Delete modal
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
                <Button @click="showCreateDialog = true" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Create Course
                </Button>
            </div>

            <FlashMessage />

            <Card class="mb-6">
                <CardContent class="p-4">
                    <div class="flex flex-wrap gap-3 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Search</label>
                            <Input v-model="search" type="text" placeholder="Search courses..." @keyup.enter="applyFilters" />
                        </div>
                        <Button v-if="search" variant="outline" size="sm" @click="clearFilters" class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            Clear
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <Card class="overflow-hidden">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Code</TableHead>
                            <TableHead>Subjects</TableHead>
                            <TableHead>Description</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="course in courses.data" :key="course.id">
                            <TableCell class="font-semibold text-slate-900">{{ course.name }}</TableCell>
                            <TableCell>
                                <Badge variant="secondary" class="font-mono">{{ course.code }}</Badge>
                            </TableCell>
                            <TableCell class="font-mono text-[13px]">{{ course.subjects_count }}</TableCell>
                            <TableCell class="max-w-[200px] truncate">{{ course.description || '-' }}</TableCell>
                            <TableCell class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <Button variant="ghost" size="sm" class="text-blue-600 hover:text-blue-700" @click="openEdit(course)">Edit</Button>
                                    <Button variant="ghost" size="sm" @click="confirmDelete(course)" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50">Delete</Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!courses.data?.length">
                            <TableCell colspan="5" class="text-center py-12 text-slate-400">No courses found.</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <Pagination :links="courses.links" />
            <Modal :show="showDeleteModal" title="Delete Course" :message="`Delete ${courseToDelete?.name}? This will also delete all subjects under it.`" @confirm="deleteCourse" @cancel="showDeleteModal = false" />
        </div>

        <!-- Create Dialog -->
        <ModalForm v-model:open="showCreateDialog" title="Create Course" description="Add a new course to the system" size="md">
            <form @submit.prevent="submitCreate" class="space-y-4 py-2">
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                    <Input v-model="createForm.name" type="text" required />
                    <p v-if="createForm.errors.name" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.name }}</p>
                </div>
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Code *</Label>
                    <Input v-model="createForm.code" type="text" required placeholder="e.g. CS" />
                    <p v-if="createForm.errors.code" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.code }}</p>
                </div>
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Description</Label>
                    <Textarea v-model="createForm.description" rows="3" />
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <Button variant="outline" type="button" @click="showCreateDialog = false">Cancel</Button>
                    <Button type="submit" :disabled="createForm.processing">
                        {{ createForm.processing ? 'Creating...' : 'Create Course' }}
                    </Button>
                </div>
            </form>
        </ModalForm>

        <!-- Edit Dialog -->
        <ModalForm v-model:open="showEditDialog" title="Edit Course" :description="`Update ${editingCourse?.name}`" size="md">
            <form @submit.prevent="submitEdit" class="space-y-4 py-2">
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                    <Input v-model="editForm.name" type="text" required />
                    <p v-if="editForm.errors.name" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.name }}</p>
                </div>
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Code *</Label>
                    <Input v-model="editForm.code" type="text" required />
                    <p v-if="editForm.errors.code" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.code }}</p>
                </div>
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Description</Label>
                    <Textarea v-model="editForm.description" rows="3" />
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <Button variant="outline" type="button" @click="showEditDialog = false">Cancel</Button>
                    <Button type="submit" :disabled="editForm.processing">
                        {{ editForm.processing ? 'Saving...' : 'Save Changes' }}
                    </Button>
                </div>
            </form>
        </ModalForm>
    </AdminLayout>
</template>
