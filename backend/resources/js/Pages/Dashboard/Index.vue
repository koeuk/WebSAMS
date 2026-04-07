<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
    stats: Object,
});
</script>

<template>
    <AdminLayout>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Dashboard</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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
    </AdminLayout>
</template>
