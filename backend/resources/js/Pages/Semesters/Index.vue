<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';
import ModalForm from '@/Components/ModalForm.vue';
import DatePicker from '@/Components/DatePicker.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';

const props = defineProps({ semesters: Object });

// ── Delete ────────────────────────────────────────────────────────────────────
const showDeleteModal = ref(false);
const semesterToDelete = ref(null);
const confirmDelete = (s) => { semesterToDelete.value = s; showDeleteModal.value = true; };
const deleteSemester = () => {
    router.delete(`/admin/semesters/${semesterToDelete.value.id}`, {
        onFinish: () => { showDeleteModal.value = false; semesterToDelete.value = null; },
    });
};

// ── Create ────────────────────────────────────────────────────────────────────
const showCreateDialog = ref(false);
const createForm = useForm({ name: '', academic_year: '', start_date: '', end_date: '' });
const submitCreate = () => {
    createForm.post('/admin/semesters', {
        onSuccess: () => { showCreateDialog.value = false; createForm.reset(); },
    });
};

// ── Edit ──────────────────────────────────────────────────────────────────────
const showEditDialog = ref(false);
const editingItem = ref(null);
const editForm = useForm({ name: '', academic_year: '', start_date: '', end_date: '' });
const openEdit = (s) => {
    editingItem.value = s;
    editForm.name = s.name;
    editForm.academic_year = s.academic_year;
    editForm.start_date = s.start_date?.split('T')[0] || '';
    editForm.end_date = s.end_date?.split('T')[0] || '';
    showEditDialog.value = true;
};
const submitEdit = () => {
    editForm.put(`/admin/semesters/${editingItem.value.id}`, {
        onSuccess: () => { showEditDialog.value = false; editingItem.value = null; },
    });
};

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '-';
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Semesters</h2>
            <Button @click="showCreateDialog = true" class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                Create Semester
            </Button>
        </div>

        <FlashMessage />

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <Table>
                <TableHeader>
                    <TableRow class="bg-gray-50">
                        <TableHead>Name</TableHead>
                        <TableHead>Academic Year</TableHead>
                        <TableHead>Start Date</TableHead>
                        <TableHead>End Date</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="s in semesters.data" :key="s.id">
                        <TableCell class="font-medium text-gray-900">{{ s.name }}</TableCell>
                        <TableCell class="text-gray-600">{{ s.academic_year }}</TableCell>
                        <TableCell class="text-gray-600">{{ formatDate(s.start_date) }}</TableCell>
                        <TableCell class="text-gray-600">{{ formatDate(s.end_date) }}</TableCell>
                        <TableCell class="text-right">
                            <Button variant="ghost" size="sm" class="text-blue-600 hover:text-blue-800 mr-1" @click="openEdit(s)">Edit</Button>
                            <Button variant="ghost" size="sm" class="text-red-600 hover:text-red-800" @click="confirmDelete(s)">Delete</Button>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="!semesters.data?.length">
                        <TableCell colspan="5" class="text-center py-8 text-gray-500">No semesters found.</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <Pagination :links="semesters.links" />

        <!-- Delete confirmation modal -->
        <Modal :show="showDeleteModal" title="Delete Semester" :message="`Delete ${semesterToDelete?.name}?`" @confirm="deleteSemester" @cancel="showDeleteModal = false" />

        <!-- Create Dialog -->
        <ModalForm v-model:open="showCreateDialog" title="Create Semester" description="Add a new academic semester." size="md">
            <form @submit.prevent="submitCreate" class="space-y-4 py-2">
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                    <Input v-model="createForm.name" type="text" required placeholder="e.g. Semester 1" />
                    <p v-if="createForm.errors.name" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.name }}</p>
                </div>
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Academic Year *</Label>
                    <Input v-model="createForm.academic_year" type="text" required placeholder="e.g. 2025-2026" />
                    <p v-if="createForm.errors.academic_year" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.academic_year }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Start Date *</Label>
                        <DatePicker v-model="createForm.start_date" placeholder="Pick start date" />
                        <p v-if="createForm.errors.start_date" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.start_date }}</p>
                    </div>
                    <div>
                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">End Date *</Label>
                        <DatePicker v-model="createForm.end_date" placeholder="Pick end date" />
                        <p v-if="createForm.errors.end_date" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.end_date }}</p>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <Button type="button" variant="outline" @click="showCreateDialog = false">Cancel</Button>
                    <Button type="submit" :disabled="createForm.processing" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                        {{ createForm.processing ? 'Creating...' : 'Create Semester' }}
                    </Button>
                </div>
            </form>
        </ModalForm>

        <!-- Edit Dialog -->
        <ModalForm v-model:open="showEditDialog" title="Edit Semester" description="Update semester details." size="md">
            <form @submit.prevent="submitEdit" class="space-y-4 py-2">
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                    <Input v-model="editForm.name" type="text" required />
                    <p v-if="editForm.errors.name" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.name }}</p>
                </div>
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Academic Year *</Label>
                    <Input v-model="editForm.academic_year" type="text" required />
                    <p v-if="editForm.errors.academic_year" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.academic_year }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Start Date *</Label>
                        <DatePicker v-model="editForm.start_date" placeholder="Pick start date" />
                        <p v-if="editForm.errors.start_date" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.start_date }}</p>
                    </div>
                    <div>
                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">End Date *</Label>
                        <DatePicker v-model="editForm.end_date" placeholder="Pick end date" />
                        <p v-if="editForm.errors.end_date" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.end_date }}</p>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <Button type="button" variant="outline" @click="showEditDialog = false">Cancel</Button>
                    <Button type="submit" :disabled="editForm.processing" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                        {{ editForm.processing ? 'Saving...' : 'Save Changes' }}
                    </Button>
                </div>
            </form>
        </ModalForm>
    </AdminLayout>
</template>
