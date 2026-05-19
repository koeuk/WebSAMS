<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Student Tracking</h2>
                <p class="text-sm text-slate-500 mt-1">Monitor student attendance rates and identify at-risk students</p>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8 stagger-children">
                <Card class="stat-card stat-card-blue p-6 animate-fade-in-up">
                    <CardContent class="p-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[13px] font-medium text-slate-500">Total Students</p>
                                <p class="text-3xl font-bold text-slate-900 mt-1.5 tracking-tight">{{ summary?.totalStudents }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            </div>
                        </div>
                    </CardContent>
                </Card>
                <Card class="stat-card stat-card-rose p-6 animate-fade-in-up">
                    <CardContent class="p-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[13px] font-medium text-slate-500">Low Attendance (&lt;{{ threshold }}%)</p>
                                <p class="text-3xl font-bold text-rose-600 mt-1.5 tracking-tight">{{ summary?.lowAttendance }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                        </div>
                    </CardContent>
                </Card>
                <Card class="stat-card stat-card-emerald p-6 animate-fade-in-up">
                    <CardContent class="p-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[13px] font-medium text-slate-500">Average Attendance Rate</p>
                                <p class="text-3xl font-bold mt-1.5 tracking-tight" :class="(summary?.averageRate ?? 0) >= threshold ? 'text-emerald-600' : 'text-rose-600'">{{ summary?.averageRate }}%</p>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Filters -->
            <Card class="mb-6">
                <CardContent class="p-4">
                    <div class="flex flex-wrap gap-3 items-end">
                        <div class="flex flex-col gap-1.5">
                            <Label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Semester</Label>
                            <SelectDropdown v-model="semesterFilter" :options="semesterOptions" placeholder="All Time" @change="yearFilter = ''; applyFilters()" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Academic Year</Label>
                            <SelectDropdown v-model="yearFilter" :options="yearOptions" placeholder="All Years" @change="semesterFilter = ''; applyFilters()" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Course</Label>
                            <SelectDropdown v-model="courseFilter" :options="courseOptions" placeholder="All Courses" @change="applyFilters()" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Class</Label>
                            <SelectDropdown v-model="classFilter" :options="classOptions" placeholder="All Classes" @change="applyFilters()" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Year Level</Label>
                            <SelectDropdown v-model="yearLevelFilter" :options="yearLevelOptions" placeholder="All Years" @change="applyFilters()" />
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <Label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Threshold (%)</Label>
                            <Input v-model="thresholdFilter" type="number" min="0" max="100" class="w-24" @change="applyFilters()" />
                        </div>
                        <Button v-if="hasActiveFilters" variant="outline" size="sm" @click="clearFilters" class="flex items-center gap-1.5 self-end">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            Clear
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Student Table -->
            <Card class="overflow-hidden">
                <div class="overflow-x-auto">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>#</TableHead>
                                <TableHead>Student</TableHead>
                                <TableHead>Year</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead class="text-center">Total</TableHead>
                                <TableHead class="text-center">Present</TableHead>
                                <TableHead class="text-center">Absent</TableHead>
                                <TableHead class="text-center">Late</TableHead>
                                <TableHead class="text-center">Rate</TableHead>
                                <TableHead class="text-center">Status</TableHead>
                                <TableHead class="text-right">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(row, index) in tracking" :key="row.student?.id" :class="row.status === 'danger' ? 'bg-rose-50/50' : row.status === 'warning' ? 'bg-amber-50/50' : ''">
                                <TableCell class="text-slate-400 font-mono text-[13px]">{{ index + 1 }}</TableCell>
                                <TableCell class="font-semibold text-slate-900">{{ row.student?.name }}</TableCell>
                                <TableCell>{{ row.student?.year_level ? 'Y' + row.student.year_level : '-' }}</TableCell>
                                <TableCell>{{ row.student?.email }}</TableCell>
                                <TableCell class="text-center font-mono">{{ row.total }}</TableCell>
                                <TableCell class="text-center font-mono text-emerald-600">{{ row.present }}</TableCell>
                                <TableCell class="text-center font-mono text-rose-600">{{ row.absent }}</TableCell>
                                <TableCell class="text-center font-mono text-amber-600">{{ row.late }}</TableCell>
                                <TableCell class="text-center font-bold" :class="row.rate >= threshold ? 'text-emerald-600' : row.rate >= 60 ? 'text-amber-600' : 'text-rose-600'">{{ row.rate }}%</TableCell>
                                <TableCell class="text-center">
                                    <Badge variant="outline" :class="statusBadgeClass(row.status)">{{ statusLabel(row.status) }}</Badge>
                                </TableCell>
                                <TableCell class="text-right">
                                    <Button variant="ghost" size="sm" as-child class="text-blue-600 hover:text-blue-700 hover:bg-blue-50">
                                        <Link :href="`/admin/student-tracking/${row.student?.id}${semesterFilter ? '?semester_id=' + semesterFilter : ''}`">View Detail</Link>
                                    </Button>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="!tracking?.length">
                                <TableCell colspan="11" class="text-center py-12 text-slate-400">No attendance data found.</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </Card>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SelectDropdown from '@/Components/SelectDropdown.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Badge } from '@/Components/ui/badge';
import { Card, CardContent } from '@/Components/ui/card';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';

const props = defineProps({
    tracking: Array, semesters: Array, academicYears: Array, courses: Array, classes: Array, yearLevels: Array, filters: Object, threshold: Number, summary: Object,
});

const semesterFilter = ref(props.filters?.semester_id || '');
const yearFilter = ref(props.filters?.academic_year || '');
const courseFilter = ref(props.filters?.course_id || '');
const classFilter = ref(props.filters?.class_id || '');
const yearLevelFilter = ref(props.filters?.year_level || '');
const thresholdFilter = ref(props.threshold || 80);

const applyFilters = () => {
    router.get('/admin/student-tracking', {
        filter: { semester_id: semesterFilter.value || undefined, academic_year: yearFilter.value || undefined, course_id: courseFilter.value || undefined, class_id: classFilter.value || undefined, year_level: yearLevelFilter.value || undefined, threshold: thresholdFilter.value },
    }, { preserveState: true });
};

const hasActiveFilters = computed(() =>
    semesterFilter.value || yearFilter.value || courseFilter.value ||
    classFilter.value || yearLevelFilter.value || thresholdFilter.value != 80
);

const clearFilters = () => {
    semesterFilter.value = '';
    yearFilter.value = '';
    courseFilter.value = '';
    classFilter.value = '';
    yearLevelFilter.value = '';
    thresholdFilter.value = 80;
    router.get('/admin/student-tracking', { filter: { threshold: 80 } }, { preserveState: false });
};

const semesterOptions = computed(() => [
    { value: '', label: 'All Time' },
    ...(props.semesters?.map(s => ({ value: s.id, label: `${s.name} (${s.academic_year})` })) ?? []),
]);
const yearOptions = computed(() => [
    { value: '', label: 'All Years' },
    ...(props.academicYears?.map(y => ({ value: y, label: y })) ?? []),
]);
const courseOptions = computed(() => [
    { value: '', label: 'All Courses' },
    ...(props.courses?.map(c => ({ value: c.id, label: c.name })) ?? []),
]);
const classOptions = computed(() => [
    { value: '', label: 'All Classes' },
    ...(props.classes?.map(c => ({ value: c.id, label: c.name })) ?? []),
]);
const yearLevelOptions = computed(() => [
    { value: '', label: 'All Years' },
    ...(props.yearLevels ?? []),
]);

const statusBadgeClass = (status) => ({
    'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200': status === 'good',
    'bg-amber-50 text-amber-700 ring-1 ring-amber-200': status === 'warning',
    'bg-rose-50 text-rose-700 ring-1 ring-rose-200': status === 'danger',
});

const statusLabel = (status) => ({ good: 'Good', warning: 'At Risk', danger: 'Critical' }[status]);
</script>
