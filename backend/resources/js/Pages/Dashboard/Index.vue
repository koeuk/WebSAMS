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
        { label: 'Present', data: props.weeklyChart?.map(d => d.present) || [], backgroundColor: '#10b981', borderRadius: 6, borderSkipped: false },
        { label: 'Absent', data: props.weeklyChart?.map(d => d.absent) || [], backgroundColor: '#f43f5e', borderRadius: 6, borderSkipped: false },
        { label: 'Late', data: props.weeklyChart?.map(d => d.late) || [], backgroundColor: '#f59e0b', borderRadius: 6, borderSkipped: false },
    ],
};

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
            align: 'end',
            labels: { usePointStyle: true, pointStyle: 'circle', padding: 20, font: { size: 12, family: 'Plus Jakarta Sans', weight: '500' } }
        },
    },
    scales: {
        x: { stacked: true, grid: { display: false }, ticks: { font: { size: 11, family: 'Plus Jakarta Sans' } } },
        y: { stacked: true, grid: { color: '#f1f5f9' }, border: { dash: [4, 4] }, ticks: { font: { size: 11, family: 'Plus Jakarta Sans' } } },
    },
};

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '-';

const statusClass = (status) => ({
    'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200': status === 'present',
    'bg-rose-50 text-rose-700 ring-1 ring-rose-200': status === 'absent',
    'bg-amber-50 text-amber-700 ring-1 ring-amber-200': status === 'late',
    'bg-sky-50 text-sky-700 ring-1 ring-sky-200': status === 'excused',
});
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard</h2>
                    <p class="text-sm text-slate-500 mt-1">Welcome back! Here's what's happening today.</p>
                </div>
                <div class="text-sm text-slate-400 font-medium">
                    {{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 stagger-children">
                <div class="card stat-card stat-card-blue p-6 animate-fade-in-up">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[13px] font-medium text-slate-500">Total Students</p>
                            <p class="text-3xl font-bold text-slate-900 mt-1.5 tracking-tight">{{ stats?.totalStudents ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                        </div>
                    </div>
                </div>
                <div class="card stat-card stat-card-emerald p-6 animate-fade-in-up">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[13px] font-medium text-slate-500">Total Teachers</p>
                            <p class="text-3xl font-bold text-slate-900 mt-1.5 tracking-tight">{{ stats?.totalTeachers ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="card stat-card stat-card-amber p-6 animate-fade-in-up">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[13px] font-medium text-slate-500">Total Classes</p>
                            <p class="text-3xl font-bold text-slate-900 mt-1.5 tracking-tight">{{ stats?.totalClasses ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </div>
                    </div>
                </div>
                <div class="card stat-card stat-card-violet p-6 animate-fade-in-up">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[13px] font-medium text-slate-500">Today's Attendance</p>
                            <p class="text-3xl font-bold mt-1.5 tracking-tight" :class="(stats?.todayAttendance ?? 0) >= 80 ? 'text-emerald-600' : 'text-rose-600'">{{ stats?.todayAttendance ?? 0 }}%</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-violet-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weekly Chart -->
            <div class="card p-6 mb-8 animate-fade-in-up" style="animation-delay: 200ms;">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Weekly Attendance</h3>
                        <p class="text-[13px] text-slate-500 mt-0.5">Attendance overview for the past week</p>
                    </div>
                </div>
                <div style="height: 280px;">
                    <Bar :data="chartData" :options="chartOptions" />
                </div>
            </div>

            <!-- Recent Attendance -->
            <div class="card overflow-hidden animate-fade-in-up" style="animation-delay: 300ms;">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h3 class="text-base font-semibold text-slate-900">Recent Attendance</h3>
                    <p class="text-[13px] text-slate-500 mt-0.5">Latest attendance records across all classes</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th class="text-left">Student</th>
                                <th class="text-left">Class</th>
                                <th class="text-left">Subject</th>
                                <th class="text-left">Date</th>
                                <th class="text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="record in recentAttendance" :key="record.id">
                                <td class="font-medium text-slate-900">{{ record.student?.name }}</td>
                                <td>{{ record.class_subject?.school_class?.name }}</td>
                                <td>{{ record.class_subject?.subject?.name }}</td>
                                <td>{{ formatDate(record.date) }}</td>
                                <td>
                                    <span class="badge" :class="statusClass(record.status)">{{ record.status }}</span>
                                </td>
                            </tr>
                            <tr v-if="!recentAttendance?.length">
                                <td colspan="5" class="!text-center !py-12 text-slate-400">No attendance records yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
