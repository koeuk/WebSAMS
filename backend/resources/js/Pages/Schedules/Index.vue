<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Schedules</h2>
                    <p class="text-sm text-slate-500 mt-1">Weekly class schedules overview</p>
                </div>
                <Button @click="openCreate" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Create Schedule
                </Button>
            </div>

            <FlashMessage />

            <div class="space-y-6 stagger-children">
                <div v-for="(daySchedules, day) in schedules" :key="day" class="animate-fade-in-up">
                    <div class="flex items-center gap-3 mb-3">
                        <Badge :class="dayColors[day] || 'bg-slate-100 text-slate-600'" class="border-0">{{ dayLabels[day] || day }}</Badge>
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </div>
                    <Card class="overflow-hidden">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Time</TableHead>
                                    <TableHead>Class</TableHead>
                                    <TableHead>Subject</TableHead>
                                    <TableHead>Teacher</TableHead>
                                    <TableHead>Room</TableHead>
                                    <TableHead class="text-right">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="s in daySchedules" :key="s.id">
                                    <TableCell class="font-medium text-slate-900 whitespace-nowrap">{{ s.time_slot?.name }} ({{ s.time_slot?.start_time?.slice(0,5) }} - {{ s.time_slot?.end_time?.slice(0,5) }})</TableCell>
                                    <TableCell>{{ s.class_subject?.school_class?.name }}</TableCell>
                                    <TableCell>{{ s.class_subject?.subject?.name }}</TableCell>
                                    <TableCell>{{ s.class_subject?.teacher?.name }}</TableCell>
                                    <TableCell>{{ s.room || '-' }}</TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <Button variant="ghost" size="sm" class="text-blue-600 hover:text-blue-700 hover:bg-blue-50" @click="openEdit(s)">Edit</Button>
                                            <Button variant="ghost" size="sm" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50" @click="router.visit(`/admin/schedules/${s.id}/delete`, { preserveState: true, preserveScroll: true })">Delete</Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </Card>
                </div>
            </div>

            <Card v-if="!Object.keys(schedules || {}).length">
                <CardContent class="p-12 text-center text-slate-400">
                    No schedules created yet.
                </CardContent>
            </Card>

            <ScheduleForm v-model:open="showForm" :schedule="editingSchedule" :class-subjects="classSubjects" :time-slots="timeSlots" :days="props.days" />
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import ScheduleForm from '@/Components/Forms/ScheduleForm.vue';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { Card, CardContent } from '@/Components/ui/card';

const props = defineProps({ schedules: Object, classSubjects: Array, timeSlots: Array, days: Array });

const dayLabels = { mon: 'Monday', tue: 'Tuesday', wed: 'Wednesday', thu: 'Thursday', fri: 'Friday', sat: 'Saturday', sun: 'Sunday' };
const dayColors = { mon: 'bg-blue-50 text-blue-700', tue: 'bg-emerald-50 text-emerald-700', wed: 'bg-amber-50 text-amber-700', thu: 'bg-violet-50 text-violet-700', fri: 'bg-rose-50 text-rose-700', sat: 'bg-sky-50 text-sky-700', sun: 'bg-slate-100 text-slate-600' };

// ── Form (create & edit) ─────────────────────────────────
const showForm = ref(false);
const editingSchedule = ref(null);

const openCreate = () => { editingSchedule.value = null; showForm.value = true; };
const openEdit = (s) => { editingSchedule.value = s; showForm.value = true; };

</script>
