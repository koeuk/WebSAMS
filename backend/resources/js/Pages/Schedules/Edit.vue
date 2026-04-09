<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

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

            <div class="card p-8 max-w-xl">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Class - Subject (Teacher) *</label>
                        <select v-model="form.class_subject_id" required class="select-modern w-full">
                            <option v-for="cs in classSubjects" :key="cs.id" :value="cs.id">{{ csLabel(cs) }}</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Time Slot *</label>
                            <select v-model="form.time_slot_id" required class="select-modern w-full">
                                <option v-for="ts in timeSlots" :key="ts.id" :value="ts.id">{{ ts.name }} ({{ ts.start_time?.slice(0,5) }} - {{ ts.end_time?.slice(0,5) }})</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Day *</label>
                            <select v-model="form.day_of_week" required class="select-modern w-full">
                                <option value="mon">Monday</option>
                                <option value="tue">Tuesday</option>
                                <option value="wed">Wednesday</option>
                                <option value="thu">Thursday</option>
                                <option value="fri">Friday</option>
                                <option value="sat">Saturday</option>
                                <option value="sun">Sunday</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Room</label>
                        <input v-model="form.room" type="text" class="input-modern" />
                    </div>
                    <div class="pt-2">
                        <button type="submit" :disabled="form.processing" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                            {{ form.processing ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
