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
</script>

<template>
    <AdminLayout>
        <div class="flex items-center gap-4 mb-6">
            <Link href="/admin/attendance" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back</Link>
            <h2 class="text-2xl font-bold text-gray-900">Bulk Mark Attendance</h2>
        </div>

        <FlashMessage />

        <div class="flex flex-wrap gap-4 mb-6">
            <select v-model="selectedClass" @change="loadStudents" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                <option value="" disabled>Select Class - Subject</option>
                <option v-for="cs in classSubjects" :key="cs.id" :value="cs.id">{{ csLabel(cs) }}</option>
            </select>
            <select v-model="selectedTimeSlot" class="px-3 py-2 border border-gray-300 rounded-md text-sm">
                <option value="" disabled>Select Time Slot</option>
                <option v-for="ts in timeSlots" :key="ts.id" :value="ts.id">{{ ts.name }} ({{ ts.start_time?.slice(0,5) }} - {{ ts.end_time?.slice(0,5) }})</option>
            </select>
            <input v-model="selectedDate" type="date" class="px-3 py-2 border border-gray-300 rounded-md text-sm" />
            <button v-if="students.length" @click="markAllPresent" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                Mark All Present
            </button>
        </div>

        <div v-if="students.length" class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Student</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Present</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Absent</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Late</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Excused</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="student in students" :key="student.id" class="border-b border-gray-100">
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ student.name }}</td>
                        <td class="px-6 py-3 text-center"><input type="radio" :name="`s-${student.id}`" value="present" v-model="attendances[student.id].status" /></td>
                        <td class="px-6 py-3 text-center"><input type="radio" :name="`s-${student.id}`" value="absent" v-model="attendances[student.id].status" /></td>
                        <td class="px-6 py-3 text-center"><input type="radio" :name="`s-${student.id}`" value="late" v-model="attendances[student.id].status" /></td>
                        <td class="px-6 py-3 text-center"><input type="radio" :name="`s-${student.id}`" value="excused" v-model="attendances[student.id].status" /></td>
                        <td class="px-6 py-3"><input v-model="attendances[student.id].remarks" type="text" class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="Optional" /></td>
                    </tr>
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200">
                <button @click="submit" class="px-6 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark">
                    Submit Attendance
                </button>
            </div>
        </div>

        <div v-else-if="selectedClass && !loadingStudents" class="bg-white rounded-lg border border-gray-200 p-8 text-center text-sm text-gray-500">
            No students found.
        </div>
    </AdminLayout>
</template>
