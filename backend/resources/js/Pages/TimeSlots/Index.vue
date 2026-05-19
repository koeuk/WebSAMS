<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';
import ModalForm from '@/Components/ModalForm.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Badge } from '@/Components/ui/badge';
import { Card } from '@/Components/ui/card';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select';

const props = defineProps({ timeSlots: Array });

// ── Delete ────────────────────────────────────────────────────────────────────
const showDeleteModal = ref(false);
const toDelete = ref(null);
const confirmDelete = (ts) => { toDelete.value = ts; showDeleteModal.value = true; };
const deleteSlot = () => {
    router.delete(`/admin/time-slots/${toDelete.value.id}`, {
        onFinish: () => { showDeleteModal.value = false; toDelete.value = null; },
    });
};

// ── Create ────────────────────────────────────────────────────────────────────
const showCreateDialog = ref(false);
const createForm = useForm({ name: '', start_time: '', end_time: '', type: 'morning' });
const submitCreate = () => {
    createForm.post('/admin/time-slots', {
        onSuccess: () => { showCreateDialog.value = false; createForm.reset(); createForm.type = 'morning'; },
    });
};

// ── Edit ──────────────────────────────────────────────────────────────────────
const showEditDialog = ref(false);
const editingItem = ref(null);
const editForm = useForm({ name: '', start_time: '', end_time: '', type: 'morning' });
const openEdit = (ts) => {
    editingItem.value = ts;
    editForm.name = ts.name;
    editForm.start_time = ts.start_time?.slice(0, 5) || '';
    editForm.end_time = ts.end_time?.slice(0, 5) || '';
    editForm.type = ts.type;
    showEditDialog.value = true;
};
const submitEdit = () => {
    editForm.put(`/admin/time-slots/${editingItem.value.id}`, {
        onSuccess: () => { showEditDialog.value = false; editingItem.value = null; },
    });
};

const typeBadgeClass = (type) => ({
    'bg-sky-50 text-sky-700 ring-1 ring-sky-200': type === 'morning',
    'bg-amber-50 text-amber-700 ring-1 ring-amber-200': type === 'afternoon',
    'bg-violet-50 text-violet-700 ring-1 ring-violet-200': type === 'evening',
});
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Time Slots</h2>
                    <p class="text-sm text-slate-500 mt-1">Manage class time periods</p>
                </div>
                <Button @click="showCreateDialog = true" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Create Time Slot
                </Button>
            </div>

            <FlashMessage />

            <Card class="overflow-hidden">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Start</TableHead>
                            <TableHead>End</TableHead>
                            <TableHead>Type</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="ts in timeSlots" :key="ts.id">
                            <TableCell class="font-semibold text-slate-900">{{ ts.name }}</TableCell>
                            <TableCell class="font-mono text-[13px]">{{ ts.start_time?.slice(0,5) }}</TableCell>
                            <TableCell class="font-mono text-[13px]">{{ ts.end_time?.slice(0,5) }}</TableCell>
                            <TableCell>
                                <Badge variant="outline" :class="typeBadgeClass(ts.type)">{{ ts.type }}</Badge>
                            </TableCell>
                            <TableCell class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <Button variant="ghost" size="sm" class="text-blue-600 hover:text-blue-700 hover:bg-blue-50" @click="openEdit(ts)">Edit</Button>
                                    <Button variant="ghost" size="sm" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50" @click="confirmDelete(ts)">Delete</Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!timeSlots?.length">
                            <TableCell colspan="5" class="text-center py-12 text-slate-400">No time slots.</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <!-- Delete confirmation modal -->
            <Modal :show="showDeleteModal" title="Delete Time Slot" :message="`Delete ${toDelete?.name}?`" @confirm="deleteSlot" @cancel="showDeleteModal = false" />

            <!-- Create Dialog -->
            <ModalForm v-model:open="showCreateDialog" title="Create Time Slot" description="Add a new class time period." size="md">
                <form @submit.prevent="submitCreate" class="space-y-4 py-2">
                    <div>
                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                        <Input v-model="createForm.name" type="text" required placeholder="e.g. Morning 1" />
                        <p v-if="createForm.errors.name" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.name }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Start Time *</Label>
                            <Input v-model="createForm.start_time" type="time" required />
                            <p v-if="createForm.errors.start_time" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.start_time }}</p>
                        </div>
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">End Time *</Label>
                            <Input v-model="createForm.end_time" type="time" required />
                            <p v-if="createForm.errors.end_time" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.end_time }}</p>
                        </div>
                    </div>
                    <div>
                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Type</Label>
                        <Select v-model="createForm.type">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select type..." />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="morning">Morning</SelectItem>
                                <SelectItem value="afternoon">Afternoon</SelectItem>
                                <SelectItem value="evening">Evening</SelectItem>
                            </SelectContent>
                        </Select>
                        <p v-if="createForm.errors.type" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.type }}</p>
                    </div>
                    <div class="flex justify-end gap-2 pt-4">
                        <Button type="button" variant="outline" @click="showCreateDialog = false">Cancel</Button>
                        <Button type="submit" :disabled="createForm.processing" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                            {{ createForm.processing ? 'Creating...' : 'Create Time Slot' }}
                        </Button>
                    </div>
                </form>
            </ModalForm>

            <!-- Edit Dialog -->
            <ModalForm v-model:open="showEditDialog" title="Edit Time Slot" description="Update time slot details." size="md">
                <form @submit.prevent="submitEdit" class="space-y-4 py-2">
                    <div>
                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                        <Input v-model="editForm.name" type="text" required />
                        <p v-if="editForm.errors.name" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.name }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Start Time *</Label>
                            <Input v-model="editForm.start_time" type="time" required />
                            <p v-if="editForm.errors.start_time" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.start_time }}</p>
                        </div>
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">End Time *</Label>
                            <Input v-model="editForm.end_time" type="time" required />
                            <p v-if="editForm.errors.end_time" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.end_time }}</p>
                        </div>
                    </div>
                    <div>
                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Type</Label>
                        <Select v-model="editForm.type">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select type..." />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="morning">Morning</SelectItem>
                                <SelectItem value="afternoon">Afternoon</SelectItem>
                                <SelectItem value="evening">Evening</SelectItem>
                            </SelectContent>
                        </Select>
                        <p v-if="editForm.errors.type" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.type }}</p>
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
        </div>
    </AdminLayout>
</template>
