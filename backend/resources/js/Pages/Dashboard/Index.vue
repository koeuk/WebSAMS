<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
    stats: Object,
    recentAttendance: Array,
});
</script>

<template>
    <AdminLayout>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Dashboard</h2>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <p class="text-sm font-medium text-gray-600">Total Students</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats?.totalStudents ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <p class="text-sm font-medium text-gray-600">Total Teachers</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats?.totalTeachers ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <p class="text-sm font-medium text-gray-600">Total Classes</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats?.totalClasses ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <p class="text-sm font-medium text-gray-600">Today's Attendance</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats?.todayAttendance ?? 0 }}%</p>
            </div>
        </div>

        <!-- Recent Attendance -->
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Attendance</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Student</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Class</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Subject</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="record in recentAttendance"
                            :key="record.id"
                            class="border-b border-gray-100"
                        >
                            <td class="px-6 py-3 text-sm text-gray-900">{{ record.student?.name }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ record.class_subject?.school_class?.name }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ record.class_subject?.subject?.name }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ record.date }}</td>
                            <td class="px-6 py-3">
                                <span
                                    class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                                    :class="{
                                        'bg-green-100 text-green-800': record.status === 'present',
                                        'bg-red-100 text-red-800': record.status === 'absent',
                                        'bg-yellow-100 text-yellow-800': record.status === 'late',
                                        'bg-blue-100 text-blue-800': record.status === 'excused',
                                    }"
                                >
                                    {{ record.status }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="!recentAttendance?.length">
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                No attendance records yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
