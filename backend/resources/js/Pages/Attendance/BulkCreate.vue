<script setup>
import { ref } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';

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

const statusColors = {
    present: 'bg-emerald-100 text-emerald-700 border-emerald-300',
    absent: 'bg-rose-100 text-rose-700 border-rose-300',
    late: 'bg-amber-100 text-amber-700 border-amber-300',
    excused: 'bg-sky-100 text-sky-700 border-sky-300',
};
</script>

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
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Bulk Mark Attendance</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Mark attendance for an entire class at once</p>
                </div>
            </div>

            <FlashMessage />

            <!-- Selection Controls -->
            <div class="card p-4 mb-6">
                <div class="flex flex-wrap gap-3 items-end">
                    <div class="min-w-[250px]">
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Class - Subject</label>
                        <select v-model="selectedClass" @change="loadStudents" class="select-modern w-full">
                            <option value="" disabled>Select Class - Subject</option>
                            <option v-for="cs in classSubjects" :key="cs.id" :value="cs.id">{{ csLabel(cs) }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Time Slot</label>
                        <select v-model="selectedTimeSlot" class="select-modern">
                            <option value="" disabled>Select Time Slot</option>
                            <option v-for="ts in timeSlots" :key="ts.id" :value="ts.id">{{ ts.name }} ({{ ts.start_time?.slice(0,5) }} - {{ ts.end_time?.slice(0,5) }})</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Date</label>
                        <input v-model="selectedDate" type="date" class="input-modern" />
                    </div>
                    <button v-if="students.length" @click="markAllPresent" class="btn-secondary">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Mark All Present
                    </button>
                </div>
            </div>

            <!-- Student Table -->
            <div v-if="students.length" class="card overflow-hidden">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th class="text-left">Student</th>
                            <th class="text-center">Present</th>
                            <th class="text-center">Absent</th>
                            <th class="text-center">Late</th>
                            <th class="text-center">Excused</th>
                            <th class="text-left">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="student in students" :key="student.id">
                            <td class="font-semibold text-slate-900">{{ student.name }}</td>
                            <td class="text-center"><input type="radio" :name="`s-${student.id}`" value="present" v-model="attendances[student.id].status" class="w-4 h-4 text-emerald-600 focus:ring-emerald-500" /></td>
                            <td class="text-center"><input type="radio" :name="`s-${student.id}`" value="absent" v-model="attendances[student.id].status" class="w-4 h-4 text-rose-600 focus:ring-rose-500" /></td>
                            <td class="text-center"><input type="radio" :name="`s-${student.id}`" value="late" v-model="attendances[student.id].status" class="w-4 h-4 text-amber-600 focus:ring-amber-500" /></td>
                            <td class="text-center"><input type="radio" :name="`s-${student.id}`" value="excused" v-model="attendances[student.id].status" class="w-4 h-4 text-sky-600 focus:ring-sky-500" /></td>
                            <td><input v-model="attendances[student.id].remarks" type="text" class="input-modern !py-1.5" placeholder="Optional" /></td>
                        </tr>
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                    <button @click="submit" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                        Submit Attendance
                    </button>
                </div>
            </div>

            <div v-else-if="loadingStudents" class="card p-12 text-center">
                <div class="inline-flex items-center gap-2 text-sm text-slate-400">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    Loading students...
                </div>
            </div>

            <div v-else-if="selectedClass && !loadingStudents" class="card p-12 text-center text-slate-400">
                No students found for this class.
            </div>
        </div>
    </AdminLayout>
</template>
