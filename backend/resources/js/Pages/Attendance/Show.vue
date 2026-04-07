<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ record: Object });

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
};

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
                <Link href="/admin/attendance" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back</Link>
                <h2 class="text-2xl font-bold text-gray-900">Attendance Detail</h2>
            </div>
            <Link :href="`/admin/attendance/${record.id}/edit`" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-md hover:bg-gray-800">
                Edit
            </Link>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 max-w-xl">
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Student</p>
                        <p class="text-sm font-medium text-gray-900">{{ record.student?.name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Date</p>
                        <p class="text-sm font-medium text-gray-900">{{ formatDate(record.date) }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Class</p>
                        <p class="text-sm font-medium text-gray-900">{{ record.class_subject?.school_class?.name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Subject</p>
                        <p class="text-sm font-medium text-gray-900">{{ record.class_subject?.subject?.name }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Course</p>
                        <p class="text-sm font-medium text-gray-900">{{ record.class_subject?.subject?.course?.name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full" :class="statusClass(record.status)">{{ record.status }}</span>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Recorded By</p>
                        <p class="text-sm font-medium text-gray-900">{{ record.recorder?.name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Remarks</p>
                        <p class="text-sm font-medium text-gray-900">{{ record.remarks || 'None' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
