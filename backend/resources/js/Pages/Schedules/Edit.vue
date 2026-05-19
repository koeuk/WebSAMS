<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center gap-4 mb-8">
                <Link href="/admin/schedules" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                    Back
                </Link>
                <div class="h-5 w-px bg-slate-200"></div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Schedule</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Update schedule entry</p>
                </div>
            </div>

            <Card class="max-w-xl">
                <CardContent class="p-8">
                    <form @submit.prevent="submit" class="space-y-5">
                        <div>
                            <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Class - Subject (Teacher) *</Label>
                            <Select v-model="form.class_subject_id">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="cs in classSubjects" :key="cs.id" :value="String(cs.id)">{{ csLabel(cs) }}</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Time Slot *</Label>
                                <Select v-model="form.time_slot_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select Time Slot" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="ts in timeSlots" :key="ts.id" :value="String(ts.id)">{{ ts.name }} ({{ ts.start_time?.slice(0,5) }} - {{ ts.end_time?.slice(0,5) }})</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div>
                                <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Day *</Label>
                                <Select v-model="form.day_of_week">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select Day" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="mon">Monday</SelectItem>
                                        <SelectItem value="tue">Tuesday</SelectItem>
                                        <SelectItem value="wed">Wednesday</SelectItem>
                                        <SelectItem value="thu">Thursday</SelectItem>
                                        <SelectItem value="fri">Friday</SelectItem>
                                        <SelectItem value="sat">Saturday</SelectItem>
                                        <SelectItem value="sun">Sunday</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                        <div>
                            <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Room</Label>
                            <Input v-model="form.room" type="text" />
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
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Card, CardContent } from '@/Components/ui/card';
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select';

const props = defineProps({ schedule: Object, classSubjects: Array, timeSlots: Array });

const form = useForm({
    class_subject_id: props.schedule.class_subject_id,
    time_slot_id: props.schedule.time_slot_id,
    day_of_week: props.schedule.day_of_week,
    room: props.schedule.room || '',
});

const submit = () => { form.put(`/admin/schedules/${props.schedule.id}`); };
const csLabel = (cs) => `${cs.school_class?.name} - ${cs.subject?.name} (${cs.teacher?.name})`;
</script>
