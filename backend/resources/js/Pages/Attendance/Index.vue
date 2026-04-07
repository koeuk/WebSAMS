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
    'bg-green-100 text-green-800': status === 'present',
    'bg-red-100 text-red-800': status === 'absent',
    'bg-yellow-100 text-yellow-800': status === 'late',
    'bg-blue-100 text-blue-800': status === 'excused',
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
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Attendance Records</h2>
            <Link href="/admin/attendance/create" class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark">
                Create Attendance
            </Link>
        </div>

        <FlashMessage />

        <!-- Filters -->
        <div class="flex flex-wrap gap-3 mb-4">
            <select v-model="courseFilter" class="px-3 py-2 border border-gray-300 rounded-md text-sm" @change="applyFilters(true)">
                <option value="">All Courses</option>
                <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
            <select v-model="classFilter" class="px-3 py-2 border border-gray-300 rounded-md text-sm" @change="applyFilters()">
                <option value="">All Classes</option>
                <option v-for="c in classes" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
            <select v-model="subjectFilter" class="px-3 py-2 border border-gray-300 rounded-md text-sm" @change="applyFilters()">
                <option value="">All Subjects</option>
                <option v-for="s in subjects" :key="s.id" :value="s.id">{{ s.name }}</option>
            </select>
            <select v-model="statusFilter" class="px-3 py-2 border border-gray-300 rounded-md text-sm" @change="applyFilters()">
                <option value="">All Status</option>
                <option value="present">Present</option>
                <option value="absent">Absent</option>
                <option value="late">Late</option>
                <option value="excused">Excused</option>
            </select>
            <input v-model="dateFrom" type="date" class="px-3 py-2 border border-gray-300 rounded-md text-sm" @change="applyFilters()" />
            <input v-model="dateTo" type="date" class="px-3 py-2 border border-gray-300 rounded-md text-sm" @change="applyFilters()" />
        </div>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Time Slot</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Student</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Year</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Class</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Course</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Subject</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Recorded By</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="record in attendance.data" :key="record.id" class="border-b border-gray-100">
                        <td class="px-6 py-3 text-sm text-gray-900 whitespace-nowrap">{{ formatDate(record.date) }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600 whitespace-nowrap">{{ record.time_slot?.name || '-' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-900">{{ record.student?.name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ record.student?.year_level ? 'Year ' + record.student.year_level : '-' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ record.class_subject?.school_class?.name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ record.class_subject?.subject?.course?.name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ record.class_subject?.subject?.name }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full" :class="statusClass(record.status)">{{ record.status }}</span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ record.recorder?.name }}</td>
                        <td class="px-6 py-3 text-right">
                            <Link :href="`/admin/attendance/${record.id}`" class="text-sm text-gray-600 hover:text-gray-800 mr-2">View</Link>
                            <Link :href="`/admin/attendance/${record.id}/edit`" class="text-sm text-blue-600 hover:text-blue-800 mr-2">Edit</Link>
                            <button @click="confirmDelete(record)" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="!attendance.data?.length">
                        <td colspan="10" class="px-6 py-8 text-center text-sm text-gray-500">No attendance records found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :links="attendance.links" />

        <Modal :show="showDeleteModal" title="Delete Attendance" message="Are you sure you want to delete this attendance record?" @confirm="deleteRecord" @cancel="showDeleteModal = false" />
    </AdminLayout>
</template>
