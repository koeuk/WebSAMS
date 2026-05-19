<script setup>
import { ref, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';
import FilterCombobox from '@/Components/FilterCombobox.vue';
import ModalForm from '@/Components/ModalForm.vue';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Label } from '@/Components/ui/label';
import { Card, CardContent } from '@/Components/ui/card';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select';

const props = defineProps({ subjects: Object, courses: Array, filters: Object });
const search = ref(props.filters?.search || '');
const courseFilter = ref(props.filters?.course_id || '');
const courseOptions = computed(() => (props.courses || []).map(c => ({ value: c.id, label: c.name })));

const applyFilters = () => {
    router.get('/admin/subjects', {
        search: search.value || undefined,
        course_id: courseFilter.value || undefined,
    }, { preserveState: true });
};

const clearFilters = () => {
    search.value = '';
    courseFilter.value = '';
    router.get('/admin/subjects', {}, { preserveState: false });
};

// Create dialog
const showCreateDialog = ref(false);
const createForm = useForm({ course_id: '', name: '', code: '' });
const submitCreate = () => {
    createForm.post('/admin/subjects', {
        onSuccess: () => { showCreateDialog.value = false; createForm.reset(); },
    });
};

// Edit dialog
const showEditDialog = ref(false);
const editingSubject = ref(null);
const editForm = useForm({ course_id: '', name: '', code: '' });
const openEdit = (subject) => {
    editingSubject.value = subject;
    editForm.course_id = String(subject.course_id);
    editForm.name = subject.name;
    editForm.code = subject.code;
    showEditDialog.value = true;
};
const submitEdit = () => {
    editForm.put(`/admin/subjects/${editingSubject.value.id}`, {
        onSuccess: () => { showEditDialog.value = false; editingSubject.value = null; },
    });
};

// Delete modal
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
                <Button @click="showCreateDialog = true" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Create Subject
                </Button>
            </div>

            <FlashMessage />

            <Card class="mb-6">
                <CardContent class="p-4">
                    <div class="flex flex-wrap gap-3 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Search</label>
                            <Input v-model="search" type="text" placeholder="Search subjects..." @keyup.enter="applyFilters" />
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Course</label>
                            <FilterCombobox v-model="courseFilter" :options="courseOptions" placeholder="All Courses" @update:model-value="applyFilters" />
                        </div>
                        <Button v-if="search || courseFilter" variant="outline" size="sm" @click="clearFilters" class="flex items-center gap-1.5 self-end">
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
                            <TableHead>Course</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="subject in subjects.data" :key="subject.id">
                            <TableCell class="font-semibold text-slate-900">{{ subject.name }}</TableCell>
                            <TableCell>
                                <Badge variant="secondary" class="font-mono">{{ subject.code }}</Badge>
                            </TableCell>
                            <TableCell>{{ subject.course?.name }}</TableCell>
                            <TableCell class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <Button variant="ghost" size="sm" class="text-blue-600 hover:text-blue-700" @click="openEdit(subject)">Edit</Button>
                                    <Button variant="ghost" size="sm" @click="confirmDelete(subject)" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50">Delete</Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!subjects.data?.length">
                            <TableCell colspan="4" class="text-center py-12 text-slate-400">No subjects found.</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <Pagination :links="subjects.links" />
            <Modal :show="showDeleteModal" title="Delete Subject" :message="`Delete ${subjectToDelete?.name}?`" @confirm="deleteSubject" @cancel="showDeleteModal = false" />
        </div>

        <!-- Create Dialog -->
        <ModalForm v-model:open="showCreateDialog" title="Create Subject" description="Add a new subject to a course" size="md">
            <form @submit.prevent="submitCreate" class="space-y-4 py-2">
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Course *</Label>
                    <Select v-model="createForm.course_id" required>
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Select a course" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="c in courses" :key="c.id" :value="String(c.id)">{{ c.name }} ({{ c.code }})</SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="createForm.errors.course_id" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.course_id }}</p>
                </div>
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                    <Input v-model="createForm.name" type="text" required />
                    <p v-if="createForm.errors.name" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.name }}</p>
                </div>
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Code *</Label>
                    <Input v-model="createForm.code" type="text" required placeholder="e.g. CS101" />
                    <p v-if="createForm.errors.code" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.code }}</p>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <Button variant="outline" type="button" @click="showCreateDialog = false">Cancel</Button>
                    <Button type="submit" :disabled="createForm.processing">
                        {{ createForm.processing ? 'Creating...' : 'Create Subject' }}
                    </Button>
                </div>
            </form>
        </ModalForm>

        <!-- Edit Dialog -->
        <ModalForm v-model:open="showEditDialog" title="Edit Subject" :description="`Update ${editingSubject?.name}`" size="md">
            <form @submit.prevent="submitEdit" class="space-y-4 py-2">
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Course *</Label>
                    <Select v-model="editForm.course_id" required>
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Select a course" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="c in courses" :key="c.id" :value="String(c.id)">{{ c.name }} ({{ c.code }})</SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="editForm.errors.course_id" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.course_id }}</p>
                </div>
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
