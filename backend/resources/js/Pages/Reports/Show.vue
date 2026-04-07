<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    studentSummary: Array,
    overall: Object,
    filters: Object,
    classes: Array,
    subjects: Array,
});

const className = props.classes?.find(c => c.id == props.filters?.class_id)?.name || 'All Classes';
const subjectName = props.subjects?.find(s => s.id == props.filters?.subject_id)?.name || 'All Subjects';
</script>

<template>
    <AdminLayout>
        <div class="flex items-center gap-4 mb-6">
            <Link href="/admin/reports" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back</Link>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Attendance Report</h2>
                <p class="text-sm text-gray-600">{{ className }} | {{ subjectName }} | {{ filters?.date_from }} to {{ filters?.date_to }}</p>
            </div>
        </div>

        <!-- Overall Summary -->
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
            <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
                <p class="text-sm text-gray-600">Total Records</p>
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
                <p class="text-sm text-gray-600">Attendance Rate</p>
                <p class="text-2xl font-bold text-gray-900">{{ overall?.rate }}%</p>
            </div>
        </div>

        <!-- Per-Student Breakdown -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Per-Student Breakdown</h3>
            </div>
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Student</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Present</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Absent</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Late</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Excused</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="row in studentSummary" :key="row.student?.id" class="border-b border-gray-100">
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ row.student?.name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600 text-center">{{ row.total }}</td>
                        <td class="px-6 py-3 text-sm text-green-600 text-center">{{ row.present }}</td>
                        <td class="px-6 py-3 text-sm text-red-600 text-center">{{ row.absent }}</td>
                        <td class="px-6 py-3 text-sm text-yellow-600 text-center">{{ row.late }}</td>
                        <td class="px-6 py-3 text-sm text-blue-600 text-center">{{ row.excused }}</td>
                        <td class="px-6 py-3 text-sm font-medium text-center" :class="row.rate >= 80 ? 'text-green-600' : row.rate >= 60 ? 'text-yellow-600' : 'text-red-600'">
                            {{ row.rate }}%
                        </td>
                    </tr>
                    <tr v-if="!studentSummary?.length">
                        <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">No data found for the selected filters.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
