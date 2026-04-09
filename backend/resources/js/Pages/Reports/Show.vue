<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ studentSummary: Array, overall: Object, semester: Object, filters: Object, classes: Array, subjects: Array, courses: Array });

const className = props.classes?.find(c => c.id == props.filters?.class_id)?.name || 'All Classes';
const subjectName = props.subjects?.find(s => s.id == props.filters?.subject_id)?.name || 'All Subjects';
const courseName = props.courses?.find(c => c.id == props.filters?.course_id)?.name || 'All Courses';
const statusLabel = props.filters?.status ? props.filters.status.charAt(0).toUpperCase() + props.filters.status.slice(1) : 'All Status';

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '';
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center gap-4 mb-8">
                <Link href="/admin/reports" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                    Back
                </Link>
                <div class="h-5 w-px bg-slate-200"></div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Attendance Report</h2>
                    <p class="text-sm text-slate-500 mt-0.5">
                        <span v-if="semester">{{ semester.name }} ({{ semester.academic_year }}) | </span>
                        {{ courseName }} | {{ className }} | {{ subjectName }} | {{ statusLabel }}
                    </p>
                    <p class="text-[12px] text-slate-400 mt-0.5">{{ formatDate(filters?.date_from) }} to {{ formatDate(filters?.date_to) }}</p>
                </div>
            </div>

            <!-- Overall Summary -->
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8 stagger-children">
                <div class="card p-4 text-center animate-fade-in-up"><p class="text-[13px] text-slate-500">Total Records</p><p class="text-2xl font-bold text-slate-900 mt-1">{{ overall?.total }}</p></div>
                <div class="card p-4 text-center animate-fade-in-up"><p class="text-[13px] text-slate-500">Present</p><p class="text-2xl font-bold text-emerald-600 mt-1">{{ overall?.present }}</p></div>
                <div class="card p-4 text-center animate-fade-in-up"><p class="text-[13px] text-slate-500">Absent</p><p class="text-2xl font-bold text-rose-600 mt-1">{{ overall?.absent }}</p></div>
                <div class="card p-4 text-center animate-fade-in-up"><p class="text-[13px] text-slate-500">Late</p><p class="text-2xl font-bold text-amber-600 mt-1">{{ overall?.late }}</p></div>
                <div class="card p-4 text-center animate-fade-in-up"><p class="text-[13px] text-slate-500">Excused</p><p class="text-2xl font-bold text-sky-600 mt-1">{{ overall?.excused }}</p></div>
                <div class="card p-4 text-center animate-fade-in-up"><p class="text-[13px] text-slate-500">Attendance Rate</p><p class="text-2xl font-bold text-slate-900 mt-1">{{ overall?.rate }}%</p></div>
            </div>

            <!-- Per-Student Breakdown -->
            <div class="card overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                        Per-Student Breakdown
                    </h3>
                </div>
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th class="text-left">Student</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Present</th>
                            <th class="text-center">Absent</th>
                            <th class="text-center">Late</th>
                            <th class="text-center">Excused</th>
                            <th class="text-center">Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in studentSummary" :key="row.student?.id">
                            <td class="font-semibold text-slate-900">{{ row.student?.name }}</td>
                            <td class="text-center font-mono">{{ row.total }}</td>
                            <td class="text-center font-mono text-emerald-600">{{ row.present }}</td>
                            <td class="text-center font-mono text-rose-600">{{ row.absent }}</td>
                            <td class="text-center font-mono text-amber-600">{{ row.late }}</td>
                            <td class="text-center font-mono text-sky-600">{{ row.excused }}</td>
                            <td class="text-center font-bold" :class="row.rate >= 80 ? 'text-emerald-600' : row.rate >= 60 ? 'text-amber-600' : 'text-rose-600'">{{ row.rate }}%</td>
                        </tr>
                        <tr v-if="!studentSummary?.length">
                            <td colspan="7" class="!text-center !py-12 text-slate-400">No data found for the selected filters.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
