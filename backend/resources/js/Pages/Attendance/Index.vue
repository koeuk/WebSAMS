<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';

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
                        <select v-model="courseFilter" class="select-modern" @change="applyFilters(true)">
                            <option value="">All Courses</option>
                            <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Class</label>
                        <select v-model="classFilter" class="select-modern" @change="applyFilters()">
                            <option value="">All Classes</option>
                            <option v-for="c in classes" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Subject</label>
                        <select v-model="subjectFilter" class="select-modern" @change="applyFilters()">
                            <option value="">All Subjects</option>
                            <option v-for="s in subjects" :key="s.id" :value="s.id">{{ s.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                        <select v-model="statusFilter" class="select-modern" @change="applyFilters()">
                            <option value="">All Status</option>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                            <option value="excused">Excused</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">From</label>
                        <input v-model="dateFrom" type="date" class="input-modern" @change="applyFilters()" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">To</label>
                        <input v-model="dateTo" type="date" class="input-modern" @change="applyFilters()" />
                    </div>
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
