<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FilterCombobox from '@/Components/FilterCombobox.vue';
import { Badge } from '@/Components/ui/badge';
import { Card, CardContent } from '@/Components/ui/card';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';

const props = defineProps({ student: Object, bySubject: Array, overall: Object, recentRecords: Array, semester: Object, semesters: Array, filters: Object });

const semesterFilter = ref(props.filters?.semester_id || '');
const semesterOptions = computed(() => (props.semesters || []).map(s => ({ value: s.id, label: `${s.name} (${s.academic_year})` })));

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
                    <FilterCombobox v-model="semesterFilter" :options="semesterOptions" placeholder="All Time" @update:model-value="changeSemester" />
                </div>
            </div>

            <p v-if="semester" class="text-sm text-slate-500 mb-6">Showing data for: <span class="font-medium text-slate-700">{{ semester.name }} ({{ semester.academic_year }})</span></p>

            <!-- Overall Summary -->
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-8 stagger-children">
                <Card class="animate-fade-in-up">
                    <CardContent class="p-4 text-center">
                        <p class="text-[13px] text-slate-500">Total</p>
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
                        <p class="text-[13px] text-slate-500">Rate</p>
                        <p class="text-2xl font-bold mt-1" :class="(overall?.rate ?? 0) >= 80 ? 'text-emerald-600' : (overall?.rate ?? 0) >= 60 ? 'text-amber-600' : 'text-rose-600'">{{ overall?.rate }}%</p>
                    </CardContent>
                </Card>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- By Subject -->
                <Card class="overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                            Attendance by Subject
                        </h3>
                    </div>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Subject</TableHead>
                                <TableHead>Course</TableHead>
                                <TableHead class="text-center">Present</TableHead>
                                <TableHead class="text-center">Absent</TableHead>
                                <TableHead class="text-center">Late</TableHead>
                                <TableHead class="text-center">Rate</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="row in bySubject" :key="row.subject">
                                <TableCell class="font-semibold text-slate-900">{{ row.subject }}</TableCell>
                                <TableCell>{{ row.course }}</TableCell>
                                <TableCell class="text-center font-mono text-emerald-600">{{ row.present }}</TableCell>
                                <TableCell class="text-center font-mono text-rose-600">{{ row.absent }}</TableCell>
                                <TableCell class="text-center font-mono text-amber-600">{{ row.late }}</TableCell>
                                <TableCell class="text-center font-bold" :class="row.rate >= 80 ? 'text-emerald-600' : row.rate >= 60 ? 'text-amber-600' : 'text-rose-600'">{{ row.rate }}%</TableCell>
                            </TableRow>
                            <TableRow v-if="!bySubject?.length">
                                <TableCell colspan="6" class="text-center py-8 text-slate-400">No data.</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </Card>

                <!-- Enrolled Classes -->
                <Card class="overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                            Enrolled Classes
                        </h3>
                    </div>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Class</TableHead>
                                <TableHead>Section</TableHead>
                                <TableHead>Year</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="c in student.enrolled_classes" :key="c.id">
                                <TableCell class="font-semibold text-slate-900">{{ c.name }}</TableCell>
                                <TableCell>{{ c.section || '-' }}</TableCell>
                                <TableCell>{{ c.academic_year }}</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </Card>
            </div>

            <!-- Recent Records -->
            <Card class="overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-sky-400"></div>
                        Recent Attendance Records
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Date</TableHead>
                                <TableHead>Student</TableHead>
                                <TableHead>Academic Year</TableHead>
                                <TableHead>Semester</TableHead>
                                <TableHead>Course</TableHead>
                                <TableHead>Subject</TableHead>
                                <TableHead>Class</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Remarks</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="r in recentRecords" :key="r.id">
                                <TableCell class="whitespace-nowrap font-medium text-slate-900">{{ formatDate(r.date) }}</TableCell>
                                <TableCell class="font-semibold text-slate-900">{{ r.student_name }}</TableCell>
                                <TableCell>{{ r.academic_year || '-' }}</TableCell>
                                <TableCell>{{ r.semester || '-' }}</TableCell>
                                <TableCell>{{ r.course }}</TableCell>
                                <TableCell>{{ r.subject }}</TableCell>
                                <TableCell>{{ r.class }}</TableCell>
                                <TableCell>
                                    <Badge variant="outline" :class="statusClass(r.status)">{{ r.status }}</Badge>
                                </TableCell>
                                <TableCell>{{ r.remarks || '-' }}</TableCell>
                            </TableRow>
                            <TableRow v-if="!recentRecords?.length">
                                <TableCell colspan="9" class="text-center py-8 text-slate-400">No records.</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </Card>
        </div>
    </AdminLayout>
</template>
