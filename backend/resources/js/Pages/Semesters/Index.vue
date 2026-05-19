<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';
import { Button } from '@/Components/ui/button';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';

const props = defineProps({ semesters: Object });

const showDeleteModal = ref(false);
const semesterToDelete = ref(null);
const confirmDelete = (s) => { semesterToDelete.value = s; showDeleteModal.value = true; };
const deleteSemester = () => {
    router.delete(`/admin/semesters/${semesterToDelete.value.id}`, {
        onFinish: () => { showDeleteModal.value = false; semesterToDelete.value = null; },
    });
};

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '-';
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Semesters</h2>
            <Button as-child>
                <Link href="/admin/semesters/create">Create Semester</Link>
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
                            <Button variant="ghost" size="sm" as-child class="text-blue-600 hover:text-blue-800 mr-1">
                                <Link :href="`/admin/semesters/${s.id}/edit`">Edit</Link>
                            </Button>
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
        <Modal :show="showDeleteModal" title="Delete Semester" :message="`Delete ${semesterToDelete?.name}?`" @confirm="deleteSemester" @cancel="showDeleteModal = false" />
    </AdminLayout>
</template>
