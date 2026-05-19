<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';
import ModalForm from '@/Components/ModalForm.vue';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { Card, CardContent } from '@/Components/ui/card';
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select';

const props = defineProps({ schedules: Object, classSubjects: Array, timeSlots: Array });

const days = { mon: 'Monday', tue: 'Tuesday', wed: 'Wednesday', thu: 'Thursday', fri: 'Friday', sat: 'Saturday', sun: 'Sunday' };
const dayColors = { mon: 'bg-blue-50 text-blue-700', tue: 'bg-emerald-50 text-emerald-700', wed: 'bg-amber-50 text-amber-700', thu: 'bg-violet-50 text-violet-700', fri: 'bg-rose-50 text-rose-700', sat: 'bg-sky-50 text-sky-700', sun: 'bg-slate-100 text-slate-600' };

const csLabel = (cs) => `${cs.school_class?.name} - ${cs.subject?.name} (${cs.teacher?.name})`;

// ── Create ──────────────────────────────────────────────
const showCreateDialog = ref(false);
const createForm = useForm({ class_subject_id: '', time_slot_id: '', day_of_week: '', room: '' });
const submitCreate = () => {
    createForm.post('/admin/schedules', {
        onSuccess: () => { showCreateDialog.value = false; createForm.reset(); },
    });
};

// ── Edit ────────────────────────────────────────────────
const showEditDialog = ref(false);
const editingItem = ref(null);
const editForm = useForm({ class_subject_id: '', time_slot_id: '', day_of_week: '', room: '' });
const openEdit = (s) => {
    editingItem.value = s;
    editForm.class_subject_id = String(s.class_subject_id);
    editForm.time_slot_id = String(s.time_slot_id);
    editForm.day_of_week = s.day_of_week;
    editForm.room = s.room || '';
    showEditDialog.value = true;
};
const submitEdit = () => {
    editForm.put(`/admin/schedules/${editingItem.value.id}`, {
        onSuccess: () => { showEditDialog.value = false; },
    });
};

// ── Delete ───────────────────────────────────────────────
const showDeleteModal = ref(false);
const scheduleToDelete = ref(null);
const confirmDelete = (s) => { scheduleToDelete.value = s; showDeleteModal.value = true; };
const deleteSchedule = () => {
    router.delete(`/admin/schedules/${scheduleToDelete.value.id}`, {
        onFinish: () => { showDeleteModal.value = false; scheduleToDelete.value = null; },
    });
};
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Schedules</h2>
                    <p class="text-sm text-slate-500 mt-1">Weekly class schedules overview</p>
                </div>
                <Button @click="showCreateDialog = true" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Create Schedule
                </Button>
            </div>

            <FlashMessage />

            <div class="space-y-6 stagger-children">
                <div v-for="(daySchedules, day) in schedules" :key="day" class="animate-fade-in-up">
                    <div class="flex items-center gap-3 mb-3">
                        <Badge :class="dayColors[day] || 'bg-slate-100 text-slate-600'" class="border-0">{{ days[day] || day }}</Badge>
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
                                            <Button variant="ghost" size="sm" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50" @click="confirmDelete(s)">Delete</Button>
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

            <!-- Create Dialog -->
            <ModalForm v-model:open="showCreateDialog" title="Create Schedule" description="Add a new schedule entry." size="md">
                <form @submit.prevent="submitCreate" class="space-y-4 py-2">
                    <div>
                        <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Class - Subject (Teacher) *</Label>
                        <Select v-model="createForm.class_subject_id">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="cs in classSubjects" :key="cs.id" :value="String(cs.id)">{{ csLabel(cs) }}</SelectItem>
                            </SelectContent>
                        </Select>
                        <p v-if="createForm.errors.class_subject_id" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.class_subject_id }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Time Slot *</Label>
                            <Select v-model="createForm.time_slot_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Time Slot" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="ts in timeSlots" :key="ts.id" :value="String(ts.id)">{{ ts.name }} ({{ ts.start_time?.slice(0,5) }} - {{ ts.end_time?.slice(0,5) }})</SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="createForm.errors.time_slot_id" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.time_slot_id }}</p>
                        </div>
                        <div>
                            <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Day *</Label>
                            <Select v-model="createForm.day_of_week">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Day" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="mon">Monday</SelectItem>
                                    <SelectItem value="tue">Tuesday</SelectItem>
                                    <SelectItem value="wed">Wednesday</SelectItem>
                                    <SelectItem value="thu">Thursday</SelectItem>
                                    <SelectItem value="fri">Friday</SelectItem>
                                    <SelectItem value="sat">Saturday</SelectItem>
                                    <SelectItem value="sun">Sunday</SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="createForm.errors.day_of_week" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.day_of_week }}</p>
                        </div>
                    </div>
                    <div>
                        <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Room</Label>
                        <Input v-model="createForm.room" type="text" placeholder="e.g. Room 201" />
                    </div>
                    <div class="flex justify-end gap-2 pt-4">
                        <Button type="button" variant="outline" @click="showCreateDialog = false">Cancel</Button>
                        <Button type="submit" :disabled="createForm.processing" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                            {{ createForm.processing ? 'Creating...' : 'Create Schedule' }}
                        </Button>
                    </div>
                </form>
            </ModalForm>

            <!-- Edit Dialog -->
            <ModalForm v-model:open="showEditDialog" title="Edit Schedule" description="Update schedule entry." size="md">
                <form @submit.prevent="submitEdit" class="space-y-4 py-2">
                    <div>
                        <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Class - Subject (Teacher) *</Label>
                        <Select v-model="editForm.class_subject_id">
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="cs in classSubjects" :key="cs.id" :value="String(cs.id)">{{ csLabel(cs) }}</SelectItem>
                            </SelectContent>
                        </Select>
                        <p v-if="editForm.errors.class_subject_id" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.class_subject_id }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Time Slot *</Label>
                            <Select v-model="editForm.time_slot_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Time Slot" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="ts in timeSlots" :key="ts.id" :value="String(ts.id)">{{ ts.name }} ({{ ts.start_time?.slice(0,5) }} - {{ ts.end_time?.slice(0,5) }})</SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="editForm.errors.time_slot_id" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.time_slot_id }}</p>
                        </div>
                        <div>
                            <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Day *</Label>
                            <Select v-model="editForm.day_of_week">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Day" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="mon">Monday</SelectItem>
                                    <SelectItem value="tue">Tuesday</SelectItem>
                                    <SelectItem value="wed">Wednesday</SelectItem>
                                    <SelectItem value="thu">Thursday</SelectItem>
                                    <SelectItem value="fri">Friday</SelectItem>
                                    <SelectItem value="sat">Saturday</SelectItem>
                                    <SelectItem value="sun">Sunday</SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="editForm.errors.day_of_week" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.day_of_week }}</p>
                        </div>
                    </div>
                    <div>
                        <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Room</Label>
                        <Input v-model="editForm.room" type="text" placeholder="e.g. Room 201" />
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

            <Modal :show="showDeleteModal" title="Delete Schedule" message="Are you sure?" @confirm="deleteSchedule" @cancel="showDeleteModal = false" />
        </div>
    </AdminLayout>
</template>
