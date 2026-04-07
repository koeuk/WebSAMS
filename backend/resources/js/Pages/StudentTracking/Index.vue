<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    tracking: Array,
    semesters: Array,
    academicYears: Array,
    courses: Array,
    classes: Array,
    filters: Object,
    threshold: Number,
    summary: Object,
});

const semesterFilter = ref(props.filters?.semester_id || '');
const yearFilter = ref(props.filters?.academic_year || '');
const courseFilter = ref(props.filters?.course_id || '');
const classFilter = ref(props.filters?.class_id || '');
const thresholdFilter = ref(props.threshold || 80);

const applyFilters = () => {
    router.get('/admin/student-tracking', {
        semester_id: semesterFilter.value || undefined,
        academic_year: yearFilter.value || undefined,
        course_id: courseFilter.value || undefined,
        class_id: classFilter.value || undefined,
        threshold: thresholdFilter.value,
    }, { preserveState: true });
};

const statusBadge = (status) => ({
    'bg-green-100 text-green-800': status === 'good',
    'bg-yellow-100 text-yellow-800': status === 'warning',
    'bg-red-100 text-red-800': status === 'danger',
});

const statusLabel = (status) => ({
    good: 'Good',
    warning: 'At Risk',
    danger: 'Critical',
}[status]);
</script>

<template>
    <AdminLayout>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Student Tracking</h2>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <p class="text-sm text-gray-600">Total Students</p>
                <p class="text-2xl font-bold text-gray-900">{{ summary?.totalStudents }}</p>
            </div>
            <div class="bg-white rounded-lg border border-red-200 p-4">
                <p class="text-sm text-gray-600">Low Attendance (below {{ threshold }}%)</p>
                <p class="text-2xl font-bold text-red-600">{{ summary?.lowAttendance }}</p>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <p class="text-sm text-gray-600">Average Attendance Rate</p>
                <p class="text-2xl font-bold" :class="(summary?.averageRate ?? 0) >= threshold ? 'text-green-600' : 'text-red-600'">
                    {{ summary?.averageRate }}%
                </p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6">
            <div class="flex flex-wrap gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Semester</label>
                    <select v-model="semesterFilter" class="px-3 py-2 border border-gray-300 rounded-md text-sm" @change="yearFilter = ''; applyFilters()">
                        <option value="">All Time</option>
                        <option v-for="s in semesters" :key="s.id" :value="s.id">{{ s.name }} ({{ s.academic_year }})</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Academic Year</label>
                    <select v-model="yearFilter" class="px-3 py-2 border border-gray-300 rounded-md text-sm" @change="semesterFilter = ''; applyFilters()">
                        <option value="">All Years</option>
                        <option v-for="y in academicYears" :key="y" :value="y">{{ y }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Course</label>
                    <select v-model="courseFilter" class="px-3 py-2 border border-gray-300 rounded-md text-sm" @change="applyFilters()">
                        <option value="">All Courses</option>
                        <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Class</label>
                    <select v-model="classFilter" class="px-3 py-2 border border-gray-300 rounded-md text-sm" @change="applyFilters()">
                        <option value="">All Classes</option>
                        <option v-for="c in classes" :key="c.id" :value="c.id">{{ c.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Threshold (%)</label>
                    <input v-model="thresholdFilter" type="number" min="0" max="100" class="w-20 px-3 py-2 border border-gray-300 rounded-md text-sm" @change="applyFilters()" />
                </div>
            </div>
        </div>

        <!-- Student Table -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Student</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Present</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Absent</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Late</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Rate</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(row, index) in tracking" :key="row.student?.id" class="border-b border-gray-100" :class="row.status === 'danger' ? 'bg-red-50' : row.status === 'warning' ? 'bg-yellow-50' : ''">
                        <td class="px-6 py-3 text-sm text-gray-600">{{ index + 1 }}</td>
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ row.student?.name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ row.student?.email }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600 text-center">{{ row.total }}</td>
                        <td class="px-6 py-3 text-sm text-green-600 text-center">{{ row.present }}</td>
                        <td class="px-6 py-3 text-sm text-red-600 text-center">{{ row.absent }}</td>
                        <td class="px-6 py-3 text-sm text-yellow-600 text-center">{{ row.late }}</td>
                        <td class="px-6 py-3 text-sm font-bold text-center" :class="row.rate >= threshold ? 'text-green-600' : row.rate >= 60 ? 'text-yellow-600' : 'text-red-600'">
                            {{ row.rate }}%
                        </td>
                        <td class="px-6 py-3 text-center">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full" :class="statusBadge(row.status)">
                                {{ statusLabel(row.status) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right">
                            <Link :href="`/admin/student-tracking/${row.student?.id}${semesterFilter ? '?semester_id=' + semesterFilter : ''}`" class="text-sm text-blue-600 hover:text-blue-800">
                                View Detail
                            </Link>
                        </td>
                    </tr>
                    <tr v-if="!tracking?.length">
                        <td colspan="10" class="px-6 py-8 text-center text-sm text-gray-500">No attendance data found. Adjust filters or check that attendance has been recorded.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
