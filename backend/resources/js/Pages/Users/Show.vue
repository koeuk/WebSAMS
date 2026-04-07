<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ user: Object });

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : '-';

const statusBadge = (status) => ({
    'bg-green-100 text-green-800': status === 'active',
    'bg-gray-100 text-gray-800': status === 'inactive',
    'bg-blue-100 text-blue-800': status === 'graduated',
    'bg-red-100 text-red-800': status === 'suspended',
});

const roleBadge = (role) => ({
    'bg-purple-100 text-purple-800': role === 'admin',
    'bg-blue-100 text-blue-800': role === 'teacher',
    'bg-green-100 text-green-800': role === 'student',
});
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <Link href="/admin/users" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back</Link>
                <h2 class="text-2xl font-bold text-gray-900">{{ user.name }}</h2>
                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full" :class="roleBadge(user.role)">{{ user.role }}</span>
                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full" :class="statusBadge(user.status)">{{ user.status || 'active' }}</span>
            </div>
            <Link :href="`/admin/users/${user.id}/edit`" class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark">Edit</Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Basic Info</h3>
                <div class="space-y-3">
                    <div class="grid grid-cols-2">
                        <p class="text-sm text-gray-500">ID Number</p>
                        <p class="text-sm font-medium text-gray-900">{{ user.id_number || '-' }}</p>
                    </div>
                    <div class="grid grid-cols-2">
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="text-sm font-medium text-gray-900">{{ user.name }}</p>
                    </div>
                    <div class="grid grid-cols-2">
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="text-sm font-medium text-gray-900">{{ user.email }}</p>
                    </div>
                    <div class="grid grid-cols-2">
                        <p class="text-sm text-gray-500">Gender</p>
                        <p class="text-sm font-medium text-gray-900 capitalize">{{ user.gender || '-' }}</p>
                    </div>
                    <div class="grid grid-cols-2">
                        <p class="text-sm text-gray-500">Date of Birth</p>
                        <p class="text-sm font-medium text-gray-900">{{ formatDate(user.date_of_birth) }}</p>
                    </div>
                    <div class="grid grid-cols-2">
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="text-sm font-medium text-gray-900">{{ user.phone || '-' }}</p>
                    </div>
                    <div class="grid grid-cols-2">
                        <p class="text-sm text-gray-500">Address</p>
                        <p class="text-sm font-medium text-gray-900">{{ user.address || '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Role-specific Info -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <!-- Student Info -->
                <template v-if="user.role === 'student'">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Student Info</h3>
                    <div class="space-y-3">
                        <div class="grid grid-cols-2">
                            <p class="text-sm text-gray-500">Year Level</p>
                            <p class="text-sm font-medium text-gray-900">{{ user.year_level ? 'Year ' + user.year_level : '-' }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="text-sm text-gray-500">Enrollment Date</p>
                            <p class="text-sm font-medium text-gray-900">{{ formatDate(user.enrollment_date) }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="text-sm text-gray-500">Guardian Name</p>
                            <p class="text-sm font-medium text-gray-900">{{ user.guardian_name || '-' }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="text-sm text-gray-500">Guardian Phone</p>
                            <p class="text-sm font-medium text-gray-900">{{ user.guardian_phone || '-' }}</p>
                        </div>
                    </div>
                </template>

                <!-- Teacher Info -->
                <template v-if="user.role === 'teacher'">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Teacher Info</h3>
                    <div class="space-y-3">
                        <div class="grid grid-cols-2">
                            <p class="text-sm text-gray-500">Department</p>
                            <p class="text-sm font-medium text-gray-900">{{ user.department || '-' }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="text-sm text-gray-500">Qualification</p>
                            <p class="text-sm font-medium text-gray-900">{{ user.qualification || '-' }}</p>
                        </div>
                        <div class="grid grid-cols-2">
                            <p class="text-sm text-gray-500">Hire Date</p>
                            <p class="text-sm font-medium text-gray-900">{{ formatDate(user.hire_date) }}</p>
                        </div>
                    </div>
                </template>

                <!-- Admin Info -->
                <template v-if="user.role === 'admin'">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Admin Info</h3>
                    <p class="text-sm text-gray-500">Full system access</p>
                </template>
            </div>
        </div>
    </AdminLayout>
</template>
