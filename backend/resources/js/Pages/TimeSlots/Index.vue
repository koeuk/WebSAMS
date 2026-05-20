<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Time Slots</h2>
                    <p class="text-sm text-slate-500 mt-1">Manage class time periods</p>
                </div>
                <Button @click="openCreate" class="flex items-center gap-2">
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
                                    <Button variant="ghost" size="sm" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50" @click="router.visit(`/admin/time-slots/${ts.id}/delete`, { preserveState: true, preserveScroll: true })">Delete</Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!timeSlots?.length">
                            <TableCell colspan="5" class="p-0">
                                <Empty class="border-0 rounded-none">
                                    <EmptyHeader>
                                        <EmptyMedia variant="icon"><Clock class="w-6 h-6" /></EmptyMedia>
                                        <EmptyTitle>No time slots</EmptyTitle>
                                        <EmptyDescription>Create a time slot to define class periods.</EmptyDescription>
                                    </EmptyHeader>
                                </Empty>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <!-- Create / Edit Form -->
            <TimeSlotForm v-model:open="showForm" :time-slot="editingTimeSlot" :types="props.types" />
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import TimeSlotForm from '@/Components/Forms/TimeSlotForm.vue';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Card } from '@/Components/ui/card';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { Empty, EmptyHeader, EmptyMedia, EmptyTitle, EmptyDescription } from '@/Components/ui/empty';
import { Clock } from 'lucide-vue-next';

const props = defineProps({ timeSlots: Array, types: Array });

// ── Create / Edit ─────────────────────────────────────────────────────────────
const showForm = ref(false);
const editingTimeSlot = ref(null);
const openCreate = () => { editingTimeSlot.value = null; showForm.value = true; };
const openEdit = (ts) => { editingTimeSlot.value = ts; showForm.value = true; };

const typeBadgeClass = (type) => ({
    'bg-sky-50 text-sky-700 ring-1 ring-sky-200': type === 'morning',
    'bg-amber-50 text-amber-700 ring-1 ring-amber-200': type === 'afternoon',
    'bg-violet-50 text-violet-700 ring-1 ring-violet-200': type === 'evening',
});
</script>
