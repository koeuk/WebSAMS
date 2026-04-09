<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ student: Object, bySubject: Array, overall: Object, recentRecords: Array, semester: Object, semesters: Array, filters: Object });

const semesterFilter = ref(props.filters?.semester_id || '');
const changeSemester = () => {
    router.get(`/admin/student-tracking/${props.student.id}`, { semester_id: semesterFilter.value || undefined }, { preserveState: true });
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
                <div class="flex items-center gap-4">
                    <Link href="/admin/student-tracking" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                        Back
                    </Link>
                    <div class="h-5 w-px bg-slate-200"></div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ student.name }}</h2>
                        <p class="text-sm text-slate-500 mt-0.5">{{ student.email }} | {{ student.phone || 'No phone' }}</p>
                    </div>
                </div>
                <div>
                    <select v-model="semesterFilter" class="select-modern" @change="changeSemester">
                        <option value="">All Time</option>
                        <option v-for="s in semesters" :key="s.id" :value="s.id">{{ s.name }} ({{ s.academic_year }})</option>
                    </select>
                </div>
            </div>

            <p v-if="semester" class="text-sm text-slate-500 mb-6">Showing data for: <span class="font-medium text-slate-700">{{ semester.name }} ({{ semester.academic_year }})</span></p>

            <!-- Overall Summary -->
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8 stagger-children">
                <div class="card p-4 text-center animate-fade-in-up"><p class="text-[13px] text-slate-500">Total</p><p class="text-2xl font-bold text-slate-900 mt-1">{{ overall?.total }}</p></div>
                <div class="card p-4 text-center animate-fade-in-up"><p class="text-[13px] text-slate-500">Present</p><p class="text-2xl font-bold text-emerald-600 mt-1">{{ overall?.present }}</p></div>
                <div class="card p-4 text-center animate-fade-in-up"><p class="text-[13px] text-slate-500">Absent</p><p class="text-2xl font-bold text-rose-600 mt-1">{{ overall?.absent }}</p></div>
                <div class="card p-4 text-center animate-fade-in-up"><p class="text-[13px] text-slate-500">Late</p><p class="text-2xl font-bold text-amber-600 mt-1">{{ overall?.late }}</p></div>
                <div class="card p-4 text-center animate-fade-in-up"><p class="text-[13px] text-slate-500">Excused</p><p class="text-2xl font-bold text-sky-600 mt-1">{{ overall?.excused }}</p></div>
                <div class="card p-4 text-center animate-fade-in-up"><p class="text-[13px] text-slate-500">Rate</p><p class="text-2xl font-bold mt-1" :class="(overall?.rate ?? 0) >= 80 ? 'text-emerald-600' : (overall?.rate ?? 0) >= 60 ? 'text-amber-600' : 'text-rose-600'">{{ overall?.rate }}%</p></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- By Subject -->
                <div class="card overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                            Attendance by Subject
                        </h3>
                    </div>
                    <table class="modern-table">
                        <thead><tr><th class="text-left">Subject</th><th class="text-left">Course</th><th class="text-center">Present</th><th class="text-center">Absent</th><th class="text-center">Late</th><th class="text-center">Rate</th></tr></thead>
                        <tbody>
                            <tr v-for="row in bySubject" :key="row.subject">
                                <td class="font-semibold text-slate-900">{{ row.subject }}</td>
                                <td>{{ row.course }}</td>
                                <td class="text-center font-mono text-emerald-600">{{ row.present }}</td>
                                <td class="text-center font-mono text-rose-600">{{ row.absent }}</td>
                                <td class="text-center font-mono text-amber-600">{{ row.late }}</td>
                                <td class="text-center font-bold" :class="row.rate >= 80 ? 'text-emerald-600' : row.rate >= 60 ? 'text-amber-600' : 'text-rose-600'">{{ row.rate }}%</td>
                            </tr>
                            <tr v-if="!bySubject?.length"><td colspan="6" class="!text-center !py-8 text-slate-400">No data.</td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- Enrolled Classes -->
                <div class="card overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                            Enrolled Classes
                        </h3>
                    </div>
                    <table class="modern-table">
                        <thead><tr><th class="text-left">Class</th><th class="text-left">Section</th><th class="text-left">Year</th></tr></thead>
                        <tbody>
                            <tr v-for="c in student.enrolled_classes" :key="c.id">
                                <td class="font-semibold text-slate-900">{{ c.name }}</td>
                                <td>{{ c.section || '-' }}</td>
                                <td>{{ c.academic_year }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Records -->
            <div class="card overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-sky-400"></div>
                        Recent Attendance Records
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="modern-table">
                        <thead><tr><th class="text-left">Date</th><th class="text-left">Student</th><th class="text-left">Academic Year</th><th class="text-left">Semester</th><th class="text-left">Course</th><th class="text-left">Subject</th><th class="text-left">Class</th><th class="text-left">Status</th><th class="text-left">Remarks</th></tr></thead>
                        <tbody>
                            <tr v-for="r in recentRecords" :key="r.id">
                                <td class="whitespace-nowrap font-medium text-slate-900">{{ formatDate(r.date) }}</td>
                                <td class="font-semibold text-slate-900">{{ r.student_name }}</td>
                                <td>{{ r.academic_year || '-' }}</td>
                                <td>{{ r.semester || '-' }}</td>
                                <td>{{ r.course }}</td>
                                <td>{{ r.subject }}</td>
                                <td>{{ r.class }}</td>
                                <td><span class="badge" :class="statusClass(r.status)">{{ r.status }}</span></td>
                                <td>{{ r.remarks || '-' }}</td>
                            </tr>
                            <tr v-if="!recentRecords?.length"><td colspan="9" class="!text-center !py-8 text-slate-400">No records.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
