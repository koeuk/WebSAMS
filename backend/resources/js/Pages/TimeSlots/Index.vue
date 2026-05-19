<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { Card } from '@/Components/ui/card';

const props = defineProps({ timeSlots: Array });

const showDeleteModal = ref(false);
const toDelete = ref(null);
const confirmDelete = (ts) => { toDelete.value = ts; showDeleteModal.value = true; };
const deleteSlot = () => {
    router.delete(`/admin/time-slots/${toDelete.value.id}`, {
        onFinish: () => { showDeleteModal.value = false; toDelete.value = null; },
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
                <Button as-child>
                    <Link href="/admin/time-slots/create" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                        Create Time Slot
                    </Link>
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
                                    <Button variant="ghost" size="sm" as-child class="text-blue-600 hover:text-blue-700 hover:bg-blue-50">
                                        <Link :href="`/admin/time-slots/${ts.id}/edit`">Edit</Link>
                                    </Button>
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

            <Modal :show="showDeleteModal" title="Delete Time Slot" :message="`Delete ${toDelete?.name}?`" @confirm="deleteSlot" @cancel="showDeleteModal = false" />
        </div>
    </AdminLayout>
</template>
