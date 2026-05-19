<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';
import ModalForm from '@/Components/ModalForm.vue';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Label } from '@/Components/ui/label';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { Card, CardContent } from '@/Components/ui/card';

const props = defineProps({ classes: Object, filters: Object });
const search = ref(props.filters?.search || '');

const applyFilters = () => {
    router.get('/admin/classes', { search: search.value || undefined }, { preserveState: true });
};

const clearFilters = () => {
    search.value = '';
    router.get('/admin/classes', {}, { preserveState: false });
};

// ── Create ──────────────────────────────────────────────
const showCreateDialog = ref(false);
const createForm = useForm({ name: '', section: '', academic_year: '' });
const submitCreate = () => {
    createForm.post('/admin/classes', {
        onSuccess: () => { showCreateDialog.value = false; createForm.reset(); },
    });
};

// ── Edit ────────────────────────────────────────────────
const showEditDialog = ref(false);
const editingItem = ref(null);
const editForm = useForm({ name: '', section: '', academic_year: '' });
const openEdit = (c) => {
    editingItem.value = c;
    editForm.name = c.name;
    editForm.section = c.section || '';
    editForm.academic_year = c.academic_year;
    showEditDialog.value = true;
};
const submitEdit = () => {
    editForm.put(`/admin/classes/${editingItem.value.id}`, {
        onSuccess: () => { showEditDialog.value = false; },
    });
};

// ── Delete ───────────────────────────────────────────────
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
                <Button @click="showCreateDialog = true" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Create Class
                </Button>
            </div>

            <FlashMessage />

            <Card class="mb-6">
                <CardContent class="p-4">
                    <div class="flex flex-wrap gap-3 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Search</label>
                            <Input v-model="search" type="text" placeholder="Search classes..." @keyup.enter="applyFilters" />
                        </div>
                        <Button v-if="search" variant="outline" @click="clearFilters" class="flex items-center gap-1.5 text-[12px]">
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
                            <TableHead>Section</TableHead>
                            <TableHead>Academic Year</TableHead>
                            <TableHead>Subjects</TableHead>
                            <TableHead>Students</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="c in classes.data" :key="c.id">
                            <TableCell class="font-semibold text-slate-900">
                                <Link :href="`/admin/classes/${c.id}`" class="hover:text-beltei transition-colors">{{ c.name }}</Link>
                            </TableCell>
                            <TableCell>{{ c.section || '-' }}</TableCell>
                            <TableCell>
                                <Badge variant="secondary">{{ c.academic_year }}</Badge>
                            </TableCell>
                            <TableCell class="font-mono text-[13px]">{{ c.subjects_count }}</TableCell>
                            <TableCell class="font-mono text-[13px]">{{ c.students_count }}</TableCell>
                            <TableCell class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <Button variant="ghost" size="sm" as-child>
                                        <Link :href="`/admin/classes/${c.id}`">View</Link>
                                    </Button>
                                    <Button variant="ghost" size="sm" class="text-blue-600 hover:text-blue-700 hover:bg-blue-50" @click="openEdit(c)">Edit</Button>
                                    <Button variant="ghost" size="sm" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50" @click="confirmDelete(c)">Delete</Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!classes.data?.length">
                            <TableCell colspan="6" class="text-center py-12 text-slate-400">No classes found.</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <Pagination :links="classes.links" />

            <!-- Create Dialog -->
            <ModalForm v-model:open="showCreateDialog" title="Create Class" description="Add a new class to the system." size="md">
                <form @submit.prevent="submitCreate" class="space-y-4 py-2">
                    <div>
                        <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Name *</Label>
                        <Input v-model="createForm.name" type="text" required placeholder="e.g. Grade 10-A" />
                        <p v-if="createForm.errors.name" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.name }}</p>
                    </div>
                    <div>
                        <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Section</Label>
                        <Input v-model="createForm.section" type="text" placeholder="e.g. A" />
                    </div>
                    <div>
                        <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Academic Year *</Label>
                        <Input v-model="createForm.academic_year" type="text" required placeholder="e.g. 2025-2026" />
                        <p v-if="createForm.errors.academic_year" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.academic_year }}</p>
                    </div>
                    <div class="flex justify-end gap-2 pt-4">
                        <Button type="button" variant="outline" @click="showCreateDialog = false">Cancel</Button>
                        <Button type="submit" :disabled="createForm.processing" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                            {{ createForm.processing ? 'Creating...' : 'Create Class' }}
                        </Button>
                    </div>
                </form>
            </ModalForm>

            <!-- Edit Dialog -->
            <ModalForm v-model:open="showEditDialog" title="Edit Class" :description="`Update ${editingItem?.name}.`" size="md">
                <form @submit.prevent="submitEdit" class="space-y-4 py-2">
                    <div>
                        <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Name *</Label>
                        <Input v-model="editForm.name" type="text" required />
                        <p v-if="editForm.errors.name" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.name }}</p>
                    </div>
                    <div>
                        <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Section</Label>
                        <Input v-model="editForm.section" type="text" />
                    </div>
                    <div>
                        <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Academic Year *</Label>
                        <Input v-model="editForm.academic_year" type="text" required />
                        <p v-if="editForm.errors.academic_year" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.academic_year }}</p>
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

            <Modal :show="showDeleteModal" title="Delete Class" :message="`Delete ${classToDelete?.name}?`" @confirm="deleteClass" @cancel="showDeleteModal = false" />
        </div>
    </AdminLayout>
</template>
