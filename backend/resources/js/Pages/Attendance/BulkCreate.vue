<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center gap-4 mb-8">
                <Link href="/admin/attendance" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                    {{ __('Back') }}
                </Link>
                <div class="h-5 w-px bg-slate-200"></div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('Bulk Mark Attendance') }}</h2>
                    <p class="text-sm text-slate-500 mt-0.5">{{ __('Mark attendance for an entire class at once') }}</p>
                </div>
            </div>


            <!-- Selection Controls -->
            <Card class="mb-6">
                <CardContent class="p-4">
                    <div class="flex flex-wrap gap-3 items-end">
                        <div class="min-w-[250px]">
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Class - Subject') }}</label>
                            <Select v-model="selectedClass" @update:model-value="loadStudents">
                                <SelectTrigger class="w-full">
                                    <SelectValue :placeholder="__('Select Class - Subject')" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="cs in classSubjects" :key="cs.id" :value="String(cs.id)">{{ csLabel(cs) }}</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Time Slot') }}</label>
                            <Select v-model="selectedTimeSlot">
                                <SelectTrigger class="w-[220px]">
                                    <SelectValue :placeholder="__('Select Time Slot')" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="ts in timeSlots" :key="ts.id" :value="String(ts.id)">{{ ts.name }} ({{ ts.start_time?.slice(0,5) }} - {{ ts.end_time?.slice(0,5) }})</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Date') }}</label>
                            <DatePicker v-model="selectedDate" :placeholder="__('Select date')" />
                        </div>
                        <Button v-if="students.length" variant="outline" @click="markAllPresent" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('Mark All Present') }}
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Student Table -->
            <Card v-if="students.length" class="overflow-hidden">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>{{ __('Student') }}</TableHead>
                            <TableHead>{{ __('Status') }}</TableHead>
                            <TableHead>{{ __('Remarks') }}</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="student in students" :key="student.id">
                            <TableCell class="font-semibold text-slate-900">{{ student.name }}</TableCell>
                            <TableCell>
                                <RadioGroup v-model="attendances[student.id].status" class="flex gap-4">
                                    <div v-for="s in statusList" :key="s.value" class="flex items-center gap-1.5">
                                        <RadioGroupItem :value="s.value" :id="`${s.value}-${student.id}`" :class="s.radioClass" />
                                        <Label :for="`${s.value}-${student.id}`" class="text-[13px] cursor-pointer" :class="s.labelClass">{{ s.label }}</Label>
                                    </div>
                                </RadioGroup>
                            </TableCell>
                            <TableCell><Input v-model="attendances[student.id].remarks" type="text" class="py-1.5 h-auto" :placeholder="__('Optional')" /></TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    <Button @click="submit" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                        {{ __('Submit Attendance') }}
                    </Button>
                </div>
            </Card>

            <Card v-else-if="loadingStudents">
                <CardContent class="p-12 text-center">
                    <div class="inline-flex items-center gap-2 text-sm text-slate-400">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        {{ __('Loading students...') }}
                    </div>
                </CardContent>
            </Card>

            <Card v-else-if="selectedClass && !loadingStudents">
                <CardContent class="p-12 text-center text-slate-400">
                    {{ __('No students found for this class.') }}
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DatePicker from '@/Components/DatePicker.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Card, CardContent } from '@/Components/ui/card';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select';
import { RadioGroup, RadioGroupItem } from '@/Components/ui/radio-group';
import { __ } from '@/Composables/useTranslate';

const props = defineProps({ classSubjects: Array, timeSlots: Array });

const selectedClass = ref('');
const selectedTimeSlot = ref('');
const selectedDate = ref(new Date().toISOString().split('T')[0]);
const students = ref([]);
const attendances = ref({});
const loadingStudents = ref(false);

const csLabel = (cs) => `${cs.school_class?.name} - ${cs.subject?.name} (${cs.teacher?.name})`;

const loadStudents = async () => {
    if (!selectedClass.value) return;
    loadingStudents.value = true;
    try {
        const res = await fetch(`/admin/bulk-attendance/students?class_subject_id=${selectedClass.value}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        });
        students.value = await res.json();
        attendances.value = {};
        students.value.forEach(s => {
            attendances.value[s.id] = { status: 'present', remarks: '' };
        });
    } catch {}
    loadingStudents.value = false;
};

const markAllPresent = () => {
    Object.keys(attendances.value).forEach(id => { attendances.value[id].status = 'present'; });
};

const form = useForm({});

const submit = () => {
    const data = {
        class_subject_id: selectedClass.value,
        date: selectedDate.value,
        time_slot_id: selectedTimeSlot.value,
        attendances: Object.entries(attendances.value).map(([studentId, data]) => ({
            student_id: Number(studentId),
            status: data.status,
            remarks: data.remarks || null,
        })),
    };
    router.post('/admin/bulk-attendance', data);
};

const statusList = [
    { value: 'present', label: __('Present'), radioClass: 'border-emerald-500 text-emerald-500', labelClass: 'text-emerald-700' },
    { value: 'absent',  label: __('Absent'),  radioClass: 'border-rose-500 text-rose-500',       labelClass: 'text-rose-700' },
    { value: 'late',    label: __('Late'),    radioClass: 'border-amber-500 text-amber-500',      labelClass: 'text-amber-700' },
    { value: 'excused', label: __('Excused'), radioClass: 'border-sky-500 text-sky-500',          labelClass: 'text-sky-700' },
];
</script>
