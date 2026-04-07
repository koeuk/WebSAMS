<script setup>
import { ref } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    schoolClass: Object,
    availableSubjects: Array,
    availableTeachers: Array,
    availableStudents: Array,
});

// Assign subject form
const subjectForm = useForm({
    school_class_id: props.schoolClass.id,
    subject_id: '',
    teacher_id: '',
});

const assignSubject = () => {
    subjectForm.post('/admin/class-subjects', {
        onSuccess: () => { subjectForm.subject_id = ''; subjectForm.teacher_id = ''; },
    });
};

// Enroll student form
const studentForm = useForm({
    school_class_id: props.schoolClass.id,
    student_id: '',
});

const enrollStudent = () => {
    studentForm.post('/admin/class-students', {
        onSuccess: () => { studentForm.student_id = ''; },
    });
};

// Delete modals
const showDeleteModal = ref(false);
const deleteUrl = ref('');
const deleteMessage = ref('');

const confirmRemoveSubject = (cs) => {
    deleteUrl.value = `/admin/class-subjects/${cs.id}`;
    deleteMessage.value = `Remove ${cs.subject?.name} (${cs.teacher?.name})?`;
    showDeleteModal.value = true;
};

const confirmRemoveStudent = (cs) => {
    deleteUrl.value = `/admin/class-students/${cs.id}`;
    deleteMessage.value = `Remove ${cs.student?.name} from this class?`;
    showDeleteModal.value = true;
};

const executeDelete = () => {
    router.delete(deleteUrl.value, {
        onFinish: () => { showDeleteModal.value = false; },
    });
};
</script>

<template>
    <AdminLayout>
        <div class="flex items-center gap-4 mb-6">
            <Link href="/admin/classes" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back</Link>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ schoolClass.name }}</h2>
                <p class="text-sm text-gray-600">{{ schoolClass.section ? `Section ${schoolClass.section} - ` : '' }}{{ schoolClass.academic_year }}</p>
            </div>
        </div>

        <FlashMessage />

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Subjects & Teachers -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Subjects & Teachers</h3>
                </div>

                <!-- Assign form -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <form @submit.prevent="assignSubject" class="flex gap-2">
                        <select v-model="subjectForm.subject_id" required class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm">
                            <option value="" disabled>Select Subject</option>
                            <option v-for="s in availableSubjects" :key="s.id" :value="s.id">{{ s.name }} ({{ s.code }})</option>
                        </select>
                        <select v-model="subjectForm.teacher_id" required class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm">
                            <option value="" disabled>Select Teacher</option>
                            <option v-for="t in availableTeachers" :key="t.id" :value="t.id">{{ t.name }}</option>
                        </select>
                        <button type="submit" :disabled="subjectForm.processing" class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark disabled:opacity-50 whitespace-nowrap">
                            Assign
                        </button>
                    </form>
                </div>

                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Subject</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Teacher</th>
                            <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="cs in schoolClass.subjects" :key="cs.id" class="border-b border-gray-100">
                            <td class="px-6 py-3 text-sm text-gray-900">{{ cs.subject?.name }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ cs.teacher?.name }}</td>
                            <td class="px-6 py-3 text-right">
                                <button @click="confirmRemoveSubject(cs)" class="text-sm text-red-600 hover:text-red-800">Remove</button>
                            </td>
                        </tr>
                        <tr v-if="!schoolClass.subjects?.length">
                            <td colspan="3" class="px-6 py-6 text-center text-sm text-gray-500">No subjects assigned.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Enrolled Students -->
            <div class="bg-white rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Enrolled Students ({{ schoolClass.students?.length || 0 }})</h3>
                </div>

                <!-- Enroll form -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <form @submit.prevent="enrollStudent" class="flex gap-2">
                        <select v-model="studentForm.student_id" required class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm">
                            <option value="" disabled>Select Student</option>
                            <option v-for="s in availableStudents" :key="s.id" :value="s.id">{{ s.name }} ({{ s.email }})</option>
                        </select>
                        <button type="submit" :disabled="studentForm.processing" class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark disabled:opacity-50 whitespace-nowrap">
                            Enroll
                        </button>
                    </form>
                </div>

                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="student in schoolClass.students" :key="student.id" class="border-b border-gray-100">
                            <td class="px-6 py-3 text-sm text-gray-900">{{ student.name }}</td>
                            <td class="px-6 py-3 text-sm text-gray-600">{{ student.email }}</td>
                            <td class="px-6 py-3 text-right">
                                <button @click="confirmRemoveStudent({ id: student.pivot?.id || student.id, student })" class="text-sm text-red-600 hover:text-red-800">Remove</button>
                            </td>
                        </tr>
                        <tr v-if="!schoolClass.students?.length">
                            <td colspan="3" class="px-6 py-6 text-center text-sm text-gray-500">No students enrolled.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Modal :show="showDeleteModal" title="Confirm Removal" :message="deleteMessage" @confirm="executeDelete" @cancel="showDeleteModal = false" />
    </AdminLayout>
</template>
