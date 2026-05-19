<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Semesters</h2>
            <Button @click="openCreate" class="flex items-center gap-2">
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

        <!-- Create / Edit Form -->
        <SemesterForm v-model:open="showForm" :semester="editingSemester" />
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';
import SemesterForm from '@/Components/Forms/SemesterForm.vue';
import { Button } from '@/Components/ui/button';
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

// ── Create / Edit ─────────────────────────────────────────────────────────────
const showForm = ref(false);
const editingSemester = ref(null);
const openCreate = () => { editingSemester.value = null; showForm.value = true; };
const openEdit = (s) => { editingSemester.value = s; showForm.value = true; };

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '-';
</script>
