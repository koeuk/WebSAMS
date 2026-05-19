<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center gap-4 mb-8">
                <Link href="/admin/attendance" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                    Back
                </Link>
                <div class="h-5 w-px bg-slate-200"></div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Attendance</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Update attendance record</p>
                </div>
            </div>

            <Card class="max-w-xl">
                <CardContent class="p-8">
                    <form @submit.prevent="submit" class="space-y-5">
                        <div>
                            <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Class - Subject (Teacher)</Label>
                            <Select v-model="form.class_subject_id" required>
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="cs in classSubjects" :key="cs.id" :value="String(cs.id)">{{ csLabel(cs) }}</SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.class_subject_id" class="text-[12px] text-rose-500 mt-1">{{ form.errors.class_subject_id }}</p>
                        </div>
                        <div>
                            <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Student</Label>
                            <Select v-model="form.student_id" required>
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Student" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="s in students" :key="s.id" :value="String(s.id)">{{ s.name }} ({{ s.email }})</SelectItem>
                                </SelectContent>
                            </Select>
                            <p v-if="form.errors.student_id" class="text-[12px] text-rose-500 mt-1">{{ form.errors.student_id }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Date</Label>
                                <DatePicker v-model="form.date" placeholder="Select date" />
                            </div>
                            <div>
                                <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Time Slot</Label>
                                <Select v-model="form.time_slot_id" required>
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select Time Slot" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="ts in timeSlots" :key="ts.id" :value="String(ts.id)">{{ ts.name }} ({{ ts.start_time?.slice(0,5) }} - {{ ts.end_time?.slice(0,5) }})</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                        <div>
                            <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Status</Label>
                            <Select v-model="form.status" required>
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Status" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="s in statuses" :key="s.value" :value="s.value">{{ s.label }}</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div>
                            <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Remarks</Label>
                            <Textarea v-model="form.remarks" rows="2" placeholder="Optional" />
                        </div>
                        <div class="pt-2">
                            <Button type="submit" :disabled="form.processing" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                                {{ form.processing ? 'Saving...' : 'Save Changes' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DatePicker from '@/Components/DatePicker.vue';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Textarea } from '@/Components/ui/textarea';
import { Card, CardContent } from '@/Components/ui/card';
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select';

const props = defineProps({ record: Object, classSubjects: Array, students: Array, timeSlots: Array, statuses: Array });

const form = useForm({
    class_subject_id: props.record.class_subject_id,
    student_id: props.record.student_id,
    date: props.record.date?.split('T')[0],
    time_slot_id: props.record.time_slot_id,
    status: props.record.status,
    remarks: props.record.remarks || '',
});

const submit = () => { form.put(`/admin/attendance/${props.record.id}`); };
const csLabel = (cs) => `${cs.school_class?.name} - ${cs.subject?.name} (${cs.teacher?.name})`;
</script>
