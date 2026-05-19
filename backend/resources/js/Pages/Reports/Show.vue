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
                <Card class="animate-fade-in-up">
                    <CardContent class="p-4 text-center">
                        <p class="text-[13px] text-slate-500">Total Records</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">{{ overall?.total }}</p>
                    </CardContent>
                </Card>
                <Card class="animate-fade-in-up">
                    <CardContent class="p-4 text-center">
                        <p class="text-[13px] text-slate-500">Present</p>
                        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ overall?.present }}</p>
                    </CardContent>
                </Card>
                <Card class="animate-fade-in-up">
                    <CardContent class="p-4 text-center">
                        <p class="text-[13px] text-slate-500">Absent</p>
                        <p class="text-2xl font-bold text-rose-600 mt-1">{{ overall?.absent }}</p>
                    </CardContent>
                </Card>
                <Card class="animate-fade-in-up">
                    <CardContent class="p-4 text-center">
                        <p class="text-[13px] text-slate-500">Late</p>
                        <p class="text-2xl font-bold text-amber-600 mt-1">{{ overall?.late }}</p>
                    </CardContent>
                </Card>
                <Card class="animate-fade-in-up">
                    <CardContent class="p-4 text-center">
                        <p class="text-[13px] text-slate-500">Excused</p>
                        <p class="text-2xl font-bold text-sky-600 mt-1">{{ overall?.excused }}</p>
                    </CardContent>
                </Card>
                <Card class="animate-fade-in-up">
                    <CardContent class="p-4 text-center">
                        <p class="text-[13px] text-slate-500">Attendance Rate</p>
                        <p class="text-2xl font-bold text-slate-900 mt-1">{{ overall?.rate }}%</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Per-Student Breakdown -->
            <Card class="overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                        Per-Student Breakdown
                    </h3>
                </div>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Student</TableHead>
                            <TableHead class="text-center">Total</TableHead>
                            <TableHead class="text-center">Present</TableHead>
                            <TableHead class="text-center">Absent</TableHead>
                            <TableHead class="text-center">Late</TableHead>
                            <TableHead class="text-center">Excused</TableHead>
                            <TableHead class="text-center">Rate</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="row in studentSummary" :key="row.student?.id">
                            <TableCell class="font-semibold text-slate-900">{{ row.student?.name }}</TableCell>
                            <TableCell class="text-center font-mono">{{ row.total }}</TableCell>
                            <TableCell class="text-center font-mono text-emerald-600">{{ row.present }}</TableCell>
                            <TableCell class="text-center font-mono text-rose-600">{{ row.absent }}</TableCell>
                            <TableCell class="text-center font-mono text-amber-600">{{ row.late }}</TableCell>
                            <TableCell class="text-center font-mono text-sky-600">{{ row.excused }}</TableCell>
                            <TableCell class="text-center font-bold" :class="row.rate >= 80 ? 'text-emerald-600' : row.rate >= 60 ? 'text-amber-600' : 'text-rose-600'">{{ row.rate }}%</TableCell>
                        </TableRow>
                        <TableRow v-if="!studentSummary?.length">
                            <TableCell colspan="7" class="text-center py-12 text-slate-400">No data found for the selected filters.</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>
        </div>
    </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Card, CardContent } from '@/Components/ui/card';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';

const props = defineProps({ studentSummary: Array, overall: Object, semester: Object, filters: Object, classes: Array, subjects: Array, courses: Array });

const className = props.classes?.find(c => c.id == props.filters?.class_id)?.name || 'All Classes';
const subjectName = props.subjects?.find(s => s.id == props.filters?.subject_id)?.name || 'All Subjects';
const courseName = props.courses?.find(c => c.id == props.filters?.course_id)?.name || 'All Courses';
const statusLabel = props.filters?.status ? props.filters.status.charAt(0).toUpperCase() + props.filters.status.slice(1) : 'All Status';

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '';
</script>
