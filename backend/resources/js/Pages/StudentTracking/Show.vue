<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    student: Object,
    bySubject: Array,
    overall: Object,
    recentRecords: Array,
    semester: Object,
    semesters: Array,
    filters: Object,
});

const semesterFilter = ref(props.filters?.semester_id || '');

const changeSemester = () => {
    router.get(`/admin/student-tracking/${props.student.id}`, {
        semester_id: semesterFilter.value || undefined,
    }, { preserveState: true });
};

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '-';

const statusClass = (status) => ({
    'bg-green-100 text-green-800': status === 'present',
    'bg-red-100 text-red-800': status === 'absent',
    'bg-yellow-100 text-yellow-800': status === 'late',
    'bg-blue-100 text-blue-800': status === 'excused',
});
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <Link href="/admin/student-tracking" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back</Link>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ student.name }}</h2>
                    <p class="text-sm text-gray-600">{{ student.email }} | {{ student.phone || 'No phone' }}</p>
                </div>
            </div>
            <select v-model="semesterFilter" class="px-3 py-2 border border-gray-300 rounded-md text-sm" @change="changeSemester">
                <option value="">All Time</option>
                <option v-for="s in semesters" :key="s.id" :value="s.id">{{ s.name }} ({{ s.academic_year }})</option>
            </select>
        </div>

        <p v-if="semester" class="text-sm text-gray-500 mb-4">Showing data for: {{ semester.name }} ({{ semester.academic_year }})</p>

        <!-- Overall Summary -->
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
            <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
                <p class="text-sm text-gray-600">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ overall?.total }}</p>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
                <p class="text-sm text-gray-600">Present</p>
                <p class="text-2xl font-bold text-green-600">{{ overall?.present }}</p>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
                <p class="text-sm text-gray-600">Absent</p>
                <p class="text-2xl font-bold text-red-600">{{ overall?.absent }}</p>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
                <p class="text-sm text-gray-600">Late</p>
                <p class="text-2xl font-bold text-yellow-600">{{ overall?.late }}</p>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
                <p class="text-sm text-gray-600">Excused</p>
                <p class="text-2xl font-bold text-blue-600">{{ overall?.excused }}</p>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
                <p class="text-sm text-gray-600">Rate</p>
                <p class="text-2xl font-bold" :class="(overall?.rate ?? 0) >= 80 ? 'text-green-600' : (overall?.rate ?? 0) >= 60 ? 'text-yellow-600' : 'text-red-600'">
                    {{ overall?.rate }}%
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- By Subject -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Attendance by Subject</h3>
                </div>
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Subject</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Course</th>
                            <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Present</th>
                            <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Absent</th>
                            <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Late</th>
                            <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in bySubject" :key="row.subject" class="border-b border-gray-100">
                            <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ row.subject }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ row.course }}</td>
                            <td class="px-6 py-3 text-sm text-green-600 text-center">{{ row.present }}</td>
                            <td class="px-6 py-3 text-sm text-red-600 text-center">{{ row.absent }}</td>
                            <td class="px-6 py-3 text-sm text-yellow-600 text-center">{{ row.late }}</td>
                            <td class="px-6 py-3 text-sm font-bold text-center" :class="row.rate >= 80 ? 'text-green-600' : row.rate >= 60 ? 'text-yellow-600' : 'text-red-600'">
                                {{ row.rate }}%
                            </td>
                        </tr>
                        <tr v-if="!bySubject?.length">
                            <td colspan="6" class="px-6 py-6 text-center text-sm text-gray-500">No data.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Enrolled Classes -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Enrolled Classes</h3>
                </div>
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Class</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Section</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="c in student.enrolled_classes" :key="c.id" class="border-b border-gray-100">
                            <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ c.name }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ c.section || '-' }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ c.academic_year }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Records -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Attendance Records</h3>
            </div>
            <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Student</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Academic Year</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Semester</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Course</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Subject</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Class</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="r in recentRecords" :key="r.id" class="border-b border-gray-100">
                        <td class="px-6 py-3 text-sm text-gray-900 whitespace-nowrap">{{ formatDate(r.date) }}</td>
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ r.student_name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ r.academic_year || '-' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ r.semester || '-' }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ r.course }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ r.subject }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ r.class }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full" :class="statusClass(r.status)">{{ r.status }}</span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ r.remarks || '-' }}</td>
                    </tr>
                    <tr v-if="!recentRecords?.length">
                        <td colspan="9" class="px-6 py-6 text-center text-sm text-gray-500">No records.</td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </AdminLayout>
</template>
