<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Card, CardContent } from '@/Components/ui/card';

const props = defineProps({ record: Object });

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
};

const statusClass = (status) => ({
    'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-50': status === 'present',
    'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-50': status === 'absent',
    'bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-50': status === 'late',
    'bg-sky-50 text-sky-700 border-sky-200 hover:bg-sky-50': status === 'excused',
});
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <Link href="/admin/attendance" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                        Back
                    </Link>
                    <div class="h-5 w-px bg-slate-200"></div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Attendance Detail</h2>
                        <p class="text-sm text-slate-500 mt-0.5">{{ record.student?.name }} - {{ formatDate(record.date) }}</p>
                    </div>
                </div>
                <Button as-child>
                    <Link :href="`/admin/attendance/${record.id}/edit`" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </Link>
                </Button>
            </div>

            <Card class="max-w-xl">
                <CardContent class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                            <p class="text-[13px] text-slate-500">Student</p>
                            <p class="text-[13px] font-semibold text-slate-900">{{ record.student?.name }}</p>
                        </div>
                        <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                            <p class="text-[13px] text-slate-500">Date</p>
                            <p class="text-[13px] font-semibold text-slate-900">{{ formatDate(record.date) }}</p>
                        </div>
                        <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                            <p class="text-[13px] text-slate-500">Time Slot</p>
                            <p class="text-[13px] font-semibold text-slate-900">{{ record.time_slot?.name }} ({{ record.time_slot?.start_time?.slice(0,5) }} - {{ record.time_slot?.end_time?.slice(0,5) }})</p>
                        </div>
                        <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                            <p class="text-[13px] text-slate-500">Year Level</p>
                            <p class="text-[13px] font-semibold text-slate-900">{{ record.student?.year_level ? 'Year ' + record.student.year_level : '-' }}</p>
                        </div>
                        <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                            <p class="text-[13px] text-slate-500">Class</p>
                            <p class="text-[13px] font-semibold text-slate-900">{{ record.class_subject?.school_class?.name }}</p>
                        </div>
                        <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                            <p class="text-[13px] text-slate-500">Subject</p>
                            <p class="text-[13px] font-semibold text-slate-900">{{ record.class_subject?.subject?.name }}</p>
                        </div>
                        <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                            <p class="text-[13px] text-slate-500">Course</p>
                            <p class="text-[13px] font-semibold text-slate-900">{{ record.class_subject?.subject?.course?.name }}</p>
                        </div>
                        <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                            <p class="text-[13px] text-slate-500">Status</p>
                            <Badge class="capitalize" :class="statusClass(record.status)">{{ record.status }}</Badge>
                        </div>
                        <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                            <p class="text-[13px] text-slate-500">Recorded By</p>
                            <p class="text-[13px] font-semibold text-slate-900">{{ record.recorder?.name }}</p>
                        </div>
                        <div class="flex items-center justify-between py-2.5">
                            <p class="text-[13px] text-slate-500">Remarks</p>
                            <p class="text-[13px] font-semibold text-slate-900">{{ record.remarks || 'None' }}</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>
