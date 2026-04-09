<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    tracking: Array, semesters: Array, academicYears: Array, courses: Array, classes: Array, filters: Object, threshold: Number, summary: Object,
});

const semesterFilter = ref(props.filters?.semester_id || '');
const yearFilter = ref(props.filters?.academic_year || '');
const courseFilter = ref(props.filters?.course_id || '');
const classFilter = ref(props.filters?.class_id || '');
const yearLevelFilter = ref(props.filters?.year_level || '');
const thresholdFilter = ref(props.threshold || 80);

const applyFilters = () => {
    router.get('/admin/student-tracking', {
        semester_id: semesterFilter.value || undefined, academic_year: yearFilter.value || undefined, course_id: courseFilter.value || undefined, class_id: classFilter.value || undefined, year_level: yearLevelFilter.value || undefined, threshold: thresholdFilter.value,
    }, { preserveState: true });
};

const statusBadgeClass = (status) => ({
    'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200': status === 'good',
    'bg-amber-50 text-amber-700 ring-1 ring-amber-200': status === 'warning',
    'bg-rose-50 text-rose-700 ring-1 ring-rose-200': status === 'danger',
});

const statusLabel = (status) => ({ good: 'Good', warning: 'At Risk', danger: 'Critical' }[status]);
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Student Tracking</h2>
                <p class="text-sm text-slate-500 mt-1">Monitor student attendance rates and identify at-risk students</p>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8 stagger-children">
                <div class="card stat-card stat-card-blue p-6 animate-fade-in-up">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[13px] font-medium text-slate-500">Total Students</p>
                            <p class="text-3xl font-bold text-slate-900 mt-1.5 tracking-tight">{{ summary?.totalStudents }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        </div>
                    </div>
                </div>
                <div class="card stat-card stat-card-rose p-6 animate-fade-in-up">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[13px] font-medium text-slate-500">Low Attendance (&lt;{{ threshold }}%)</p>
                            <p class="text-3xl font-bold text-rose-600 mt-1.5 tracking-tight">{{ summary?.lowAttendance }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                    </div>
                </div>
                <div class="card stat-card stat-card-emerald p-6 animate-fade-in-up">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[13px] font-medium text-slate-500">Average Attendance Rate</p>
                            <p class="text-3xl font-bold mt-1.5 tracking-tight" :class="(summary?.averageRate ?? 0) >= threshold ? 'text-emerald-600' : 'text-rose-600'">{{ summary?.averageRate }}%</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card p-4 mb-6">
                <div class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Semester</label>
                        <select v-model="semesterFilter" class="select-modern" @change="yearFilter = ''; applyFilters()">
                            <option value="">All Time</option>
                            <option v-for="s in semesters" :key="s.id" :value="s.id">{{ s.name }} ({{ s.academic_year }})</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Academic Year</label>
                        <select v-model="yearFilter" class="select-modern" @change="semesterFilter = ''; applyFilters()">
                            <option value="">All Years</option>
                            <option v-for="y in academicYears" :key="y" :value="y">{{ y }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Course</label>
                        <select v-model="courseFilter" class="select-modern" @change="applyFilters()">
                            <option value="">All Courses</option>
                            <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Class</label>
                        <select v-model="classFilter" class="select-modern" @change="applyFilters()">
                            <option value="">All Classes</option>
                            <option v-for="c in classes" :key="c.id" :value="c.id">{{ c.name }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Year Level</label>
                        <select v-model="yearLevelFilter" class="select-modern" @change="applyFilters()">
                            <option value="">All Years</option>
                            <option value="1">Year 1</option><option value="2">Year 2</option><option value="3">Year 3</option><option value="4">Year 4</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Threshold (%)</label>
                        <input v-model="thresholdFilter" type="number" min="0" max="100" class="input-modern w-20" @change="applyFilters()" />
                    </div>
                </div>
            </div>

            <!-- Student Table -->
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th class="text-left">#</th>
                                <th class="text-left">Student</th>
                                <th class="text-left">Year</th>
                                <th class="text-left">Email</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Present</th>
                                <th class="text-center">Absent</th>
                                <th class="text-center">Late</th>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, index) in tracking" :key="row.student?.id" :class="row.status === 'danger' ? 'bg-rose-50/50' : row.status === 'warning' ? 'bg-amber-50/50' : ''">
                                <td class="text-slate-400 font-mono text-[13px]">{{ index + 1 }}</td>
                                <td class="font-semibold text-slate-900">{{ row.student?.name }}</td>
                                <td>{{ row.student?.year_level ? 'Y' + row.student.year_level : '-' }}</td>
                                <td>{{ row.student?.email }}</td>
                                <td class="text-center font-mono">{{ row.total }}</td>
                                <td class="text-center font-mono text-emerald-600">{{ row.present }}</td>
                                <td class="text-center font-mono text-rose-600">{{ row.absent }}</td>
                                <td class="text-center font-mono text-amber-600">{{ row.late }}</td>
                                <td class="text-center font-bold" :class="row.rate >= threshold ? 'text-emerald-600' : row.rate >= 60 ? 'text-amber-600' : 'text-rose-600'">{{ row.rate }}%</td>
                                <td class="text-center"><span class="badge" :class="statusBadgeClass(row.status)">{{ statusLabel(row.status) }}</span></td>
                                <td class="text-right">
                                    <Link :href="`/admin/student-tracking/${row.student?.id}${semesterFilter ? '?semester_id=' + semesterFilter : ''}`" class="px-2.5 py-1.5 text-[12px] font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">View Detail</Link>
                                </td>
                            </tr>
                            <tr v-if="!tracking?.length">
                                <td colspan="11" class="!text-center !py-12 text-slate-400">No attendance data found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
