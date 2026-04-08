<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend } from 'chart.js';

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const props = defineProps({
    stats: Object,
    weeklyChart: Array,
    recentAttendance: Array,
});

const chartData = {
    labels: props.weeklyChart?.map(d => d.date) || [],
    datasets: [
        { label: 'Present', data: props.weeklyChart?.map(d => d.present) || [], backgroundColor: '#22c55e' },
        { label: 'Absent', data: props.weeklyChart?.map(d => d.absent) || [], backgroundColor: '#ef4444' },
        { label: 'Late', data: props.weeklyChart?.map(d => d.late) || [], backgroundColor: '#eab308' },
    ],
};

const chartOptions = {
    responsive: true,
    plugins: { legend: { position: 'top' } },
    scales: { x: { stacked: true }, y: { stacked: true } },
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

        <!-- Weekly Chart -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Weekly Attendance</h3>
            <Bar :data="chartData" :options="chartOptions" style="max-height: 300px;" />
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
                        <tr v-for="record in recentAttendance" :key="record.id" class="border-b border-gray-100">
                            <td class="px-6 py-3 text-sm text-gray-900">{{ record.student?.name }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ record.class_subject?.school_class?.name }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ record.class_subject?.subject?.name }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ formatDate(record.date) }}</td>
                            <td class="px-6 py-3">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full" :class="statusClass(record.status)">{{ record.status }}</span>
                            </td>
                        </tr>
                        <tr v-if="!recentAttendance?.length">
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No attendance records yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
