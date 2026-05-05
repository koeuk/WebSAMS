<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';
import FilterCombobox from '@/Components/FilterCombobox.vue';
import DatePicker from '@/Components/DatePicker.vue';

const props = defineProps({
    attendance: Object,
    courses: Array,
    classes: Array,
    subjects: Array,
    timeSlots: Array,
    filters: Object,
});

const courseFilter = ref(props.filters?.course_id || '');
const classFilter = ref(props.filters?.class_id || '');
const subjectFilter = ref(props.filters?.subject_id || '');
const statusFilter = ref(props.filters?.status || '');

const courseOptions = computed(() => (props.courses || []).map(c => ({ value: c.id, label: c.name })));
const classOptions = computed(() => (props.classes || []).map(c => ({ value: c.id, label: c.name })));
const subjectOptions = computed(() => (props.subjects || []).map(s => ({ value: s.id, label: s.name })));
const statusOptions = [
    { value: 'present', label: 'Present' },
    { value: 'absent', label: 'Absent' },
    { value: 'late', label: 'Late' },
    { value: 'excused', label: 'Excused' },
];
const dateFrom = ref(props.filters?.date_from || '');
const dateTo = ref(props.filters?.date_to || '');

const applyFilters = (resetSubject = false) => {
    if (resetSubject) subjectFilter.value = '';
    router.get('/admin/attendance', {
        course_id: courseFilter.value || undefined,
        class_id: classFilter.value || undefined,
        subject_id: subjectFilter.value || undefined,
        status: statusFilter.value || undefined,
        date_from: dateFrom.value || undefined,
        date_to: dateTo.value || undefined,
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

const statusClass = (status) => ({
    'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200': status === 'present',
    'bg-rose-50 text-rose-700 ring-1 ring-rose-200': status === 'absent',
    'bg-amber-50 text-amber-700 ring-1 ring-amber-200': status === 'late',
    'bg-sky-50 text-sky-700 ring-1 ring-sky-200': status === 'excused',
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

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Attendance Records</h2>
                    <p class="text-sm text-slate-500 mt-1">Track and manage student attendance</p>
                </div>
                <div class="flex items-center gap-3">
                    <Link href="/admin/bulk-attendance" class="btn-secondary">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Bulk Mark
                    </Link>
                    <Link href="/admin/attendance/create" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                        Create Attendance
                    </Link>
                </div>
            </div>

            <FlashMessage />

            <!-- Filters -->
            <div class="card p-4 mb-6">
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
                    <button
                        v-if="courseFilter || classFilter || subjectFilter || statusFilter || dateFrom || dateTo"
                        @click="clearFilters"
                        class="flex items-center gap-1.5 px-3 py-2 text-[12px] font-medium text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg border border-slate-200 transition-colors"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        Clear
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th class="text-left">Date</th>
                                <th class="text-left">Time Slot</th>
                                <th class="text-left">Student</th>
                                <th class="text-left">Year</th>
                                <th class="text-left">Class</th>
                                <th class="text-left">Course</th>
                                <th class="text-left">Subject</th>
                                <th class="text-left">Status</th>
                                <th class="text-left">Recorded By</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="record in attendance.data" :key="record.id">
                                <td class="whitespace-nowrap font-medium text-slate-900">{{ formatDate(record.date) }}</td>
                                <td class="whitespace-nowrap">{{ record.time_slot?.name || '-' }}</td>
                                <td class="font-semibold text-slate-900">{{ record.student?.name }}</td>
                                <td>{{ record.student?.year_level ? 'Year ' + record.student.year_level : '-' }}</td>
                                <td>{{ record.class_subject?.school_class?.name }}</td>
                                <td>{{ record.class_subject?.subject?.course?.name }}</td>
                                <td>{{ record.class_subject?.subject?.name }}</td>
                                <td><span class="badge" :class="statusClass(record.status)">{{ record.status }}</span></td>
                                <td>{{ record.recorder?.name }}</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <Link :href="`/admin/attendance/${record.id}`" class="px-2.5 py-1.5 text-[12px] font-medium text-slate-500 hover:text-slate-700 hover:bg-slate-50 rounded-lg transition-colors">View</Link>
                                        <Link :href="`/admin/attendance/${record.id}/edit`" class="px-2.5 py-1.5 text-[12px] font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">Edit</Link>
                                        <button @click="confirmDelete(record)" class="px-2.5 py-1.5 text-[12px] font-medium text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!attendance.data?.length">
                                <td colspan="10" class="!text-center !py-12 text-slate-400">No attendance records found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <Pagination :links="attendance.links" />

            <Modal :show="showDeleteModal" title="Delete Attendance" message="Are you sure you want to delete this attendance record?" @confirm="deleteRecord" @cancel="showDeleteModal = false" />
        </div>
    </AdminLayout>
</template>
