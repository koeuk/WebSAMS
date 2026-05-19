<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Card, CardContent } from '@/Components/ui/card';
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select';

const props = defineProps({ classSubjects: Array, timeSlots: Array });

const form = useForm({ class_subject_id: '', time_slot_id: '', day_of_week: '', room: '' });
const submit = () => { form.post('/admin/schedules'); };
const csLabel = (cs) => `${cs.school_class?.name} - ${cs.subject?.name} (${cs.teacher?.name})`;
</script>

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
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Create Schedule</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Add a new schedule entry</p>
                </div>
            </div>

            <Card class="max-w-xl">
                <CardContent class="p-8">
                    <form @submit.prevent="submit" class="space-y-5">
                        <div>
                            <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Class - Subject (Teacher) *</Label>
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
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Time Slot *</Label>
                                <Select v-model="form.time_slot_id" required>
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select Time Slot" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="ts in timeSlots" :key="ts.id" :value="String(ts.id)">{{ ts.name }} ({{ ts.start_time?.slice(0,5) }} - {{ ts.end_time?.slice(0,5) }})</SelectItem>
                                    </SelectContent>
                                </Select>
                                <p v-if="form.errors.time_slot_id" class="text-[12px] text-rose-500 mt-1">{{ form.errors.time_slot_id }}</p>
                            </div>
                            <div>
                                <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Day *</Label>
                                <Select v-model="form.day_of_week" required>
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
                                <p v-if="form.errors.day_of_week" class="text-[12px] text-rose-500 mt-1">{{ form.errors.day_of_week }}</p>
                            </div>
                        </div>
                        <div>
                            <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Room</Label>
                            <Input v-model="form.room" type="text" placeholder="e.g. Room 201" />
                        </div>
                        <div class="pt-2">
                            <Button type="submit" :disabled="form.processing" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                                {{ form.processing ? 'Creating...' : 'Create Schedule' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>
