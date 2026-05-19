<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Attendance Records</h2>
                    <p class="text-sm text-slate-500 mt-1">Track and manage student attendance</p>
                </div>
                <div class="flex items-center gap-3">
                    <Button variant="outline" as-child>
                        <Link href="/admin/bulk-attendance" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Bulk Mark
                        </Link>
                    </Button>
                    <Button as-child>
                        <Link href="/admin/attendance/create" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                            Create Attendance
                        </Link>
                    </Button>
                </div>
            </div>

            <FlashMessage />

            <!-- Filters -->
            <Card class="mb-6">
                <CardContent class="p-4">
                    <div class="flex flex-wrap gap-3 items-end">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Course</label>
                            <FilterCombobox
                                v-model="courseFilter"
                                :options="courseOptions"
                                placeholder="All Courses"
                                @update:model-value="applyFilters(true)"
                            />
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Class</label>
                            <FilterCombobox
                                v-model="classFilter"
                                :options="classOptions"
                                placeholder="All Classes"
                                @update:model-value="applyFilters()"
                            />
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Subject</label>
                            <FilterCombobox
                                v-model="subjectFilter"
                                :options="subjectOptions"
                                placeholder="All Subjects"
                                @update:model-value="applyFilters()"
                            />
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                            <FilterCombobox
                                v-model="statusFilter"
                                :options="statusOptions"
                                placeholder="All Status"
                                @update:model-value="applyFilters()"
                            />
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">From</label>
                            <DatePicker v-model="dateFrom" placeholder="Start date" @update:model-value="applyFilters()" />
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">To</label>
                            <DatePicker v-model="dateTo" placeholder="End date" @update:model-value="applyFilters()" />
                        </div>
                        <Button
                            v-if="courseFilter || classFilter || subjectFilter || statusFilter || dateFrom || dateTo"
                            variant="outline"
                            size="sm"
                            @click="clearFilters"
                            class="flex items-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            Clear
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Table -->
            <Card class="overflow-hidden">
                <div class="overflow-x-auto">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Date</TableHead>
                                <TableHead>Time Slot</TableHead>
                                <TableHead>Student</TableHead>
                                <TableHead>Year</TableHead>
                                <TableHead>Class</TableHead>
                                <TableHead>Course</TableHead>
                                <TableHead>Subject</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Recorded By</TableHead>
                                <TableHead class="text-right">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="record in attendance.data" :key="record.id">
                                <TableCell class="whitespace-nowrap font-medium text-slate-900">{{ formatDate(record.date) }}</TableCell>
                                <TableCell class="whitespace-nowrap">{{ record.time_slot?.name || '-' }}</TableCell>
                                <TableCell class="font-semibold text-slate-900">{{ record.student?.name }}</TableCell>
                                <TableCell>{{ record.student?.year_level ? 'Year ' + record.student.year_level : '-' }}</TableCell>
                                <TableCell>{{ record.class_subject?.school_class?.name }}</TableCell>
                                <TableCell>{{ record.class_subject?.subject?.course?.name }}</TableCell>
                                <TableCell>{{ record.class_subject?.subject?.name }}</TableCell>
                                <TableCell>
                                    <Badge class="capitalize" :class="statusClass(record.status)">{{ record.status }}</Badge>
                                </TableCell>
                                <TableCell>{{ record.recorder?.name }}</TableCell>
                                <TableCell class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <Button variant="ghost" size="sm" as-child>
                                            <Link :href="`/admin/attendance/${record.id}`">View</Link>
                                        </Button>
                                        <Button variant="ghost" size="sm" class="text-blue-600 hover:text-blue-700 hover:bg-blue-50" as-child>
                                            <Link :href="`/admin/attendance/${record.id}/edit`">Edit</Link>
                                        </Button>
                                        <Button variant="ghost" size="sm" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50" @click="confirmDelete(record)">Delete</Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="!attendance.data?.length">
                                <TableCell colspan="10" class="text-center py-12 text-slate-400">No attendance records found.</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </Card>

            <Pagination :links="attendance.links" />

            <Modal :show="showDeleteModal" title="Delete Attendance" message="Are you sure you want to delete this attendance record?" @confirm="deleteRecord" @cancel="showDeleteModal = false" />
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';
import FilterCombobox from '@/Components/FilterCombobox.vue';
import DatePicker from '@/Components/DatePicker.vue';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Card, CardContent } from '@/Components/ui/card';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';

const props = defineProps({
    attendance: Object,
    courses: Array,
    classes: Array,
    subjects: Array,
    timeSlots: Array,
    statuses: Array,
    filters: Object,
});

const courseFilter = ref(props.filters?.course_id || '');
const classFilter = ref(props.filters?.class_id || '');
const subjectFilter = ref(props.filters?.subject_id || '');
const statusFilter = ref(props.filters?.status || '');

const courseOptions = computed(() => (props.courses || []).map(c => ({ value: c.id, label: c.name })));
const classOptions = computed(() => (props.classes || []).map(c => ({ value: c.id, label: c.name })));
const subjectOptions = computed(() => (props.subjects || []).map(s => ({ value: s.id, label: s.name })));
const statusOptions = computed(() => props.statuses || []);
const dateFrom = ref(props.filters?.date_from || '');
const dateTo = ref(props.filters?.date_to || '');

const applyFilters = (resetSubject = false) => {
    if (resetSubject) subjectFilter.value = '';
    router.get('/admin/attendance', {
        filter: {
            course_id: courseFilter.value || undefined,
            class_id: classFilter.value || undefined,
            subject_id: subjectFilter.value || undefined,
            status: statusFilter.value || undefined,
            date_from: dateFrom.value || undefined,
            date_to: dateTo.value || undefined,
        },
    }, { preserveState: true });
};

const clearFilters = () => {
    courseFilter.value = '';
    classFilter.value = '';
    subjectFilter.value = '';
    statusFilter.value = '';
    dateFrom.value = '';
    dateTo.value = '';
    router.get('/admin/attendance', {}, { preserveState: false });
};

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};

const statusVariant = (status) => ({
    present: 'default',
    absent: 'destructive',
    late: 'secondary',
    excused: 'outline',
}[status] || 'outline');

const statusClass = (status) => ({
    'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-50': status === 'present',
    'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-50': status === 'absent',
    'bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-50': status === 'late',
    'bg-sky-50 text-sky-700 border-sky-200 hover:bg-sky-50': status === 'excused',
});

const showDeleteModal = ref(false);
const recordToDelete = ref(null);
const confirmDelete = (record) => { recordToDelete.value = record; showDeleteModal.value = true; };
const deleteRecord = () => {
    router.delete(`/admin/attendance/${recordToDelete.value.id}`, {
        onFinish: () => { showDeleteModal.value = false; recordToDelete.value = null; },
    });
};
</script>
