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

const studentForm = useForm({
    school_class_id: props.schoolClass.id,
    student_id: '',
});

const enrollStudent = () => {
    studentForm.post('/admin/class-students', {
        onSuccess: () => { studentForm.student_id = ''; },
    });
};

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
        <div class="animate-fade-in">
            <div class="flex items-center gap-4 mb-8">
                <Link href="/admin/classes" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                    Back
                </Link>
                <div class="h-5 w-px bg-slate-200"></div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ schoolClass.name }}</h2>
                    <p class="text-sm text-slate-500 mt-0.5">{{ schoolClass.section ? `Section ${schoolClass.section} - ` : '' }}{{ schoolClass.academic_year }}</p>
                </div>
            </div>

            <FlashMessage />

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 stagger-children">
                <!-- Subjects & Teachers -->
                <div class="card overflow-hidden animate-fade-in-up">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                            Subjects & Teachers
                        </h3>
                    </div>

                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <form @submit.prevent="assignSubject" class="flex gap-2">
                            <select v-model="subjectForm.subject_id" required class="select-modern flex-1">
                                <option value="" disabled>Select Subject</option>
                                <option v-for="s in availableSubjects" :key="s.id" :value="s.id">{{ s.name }} ({{ s.code }})</option>
                            </select>
                            <select v-model="subjectForm.teacher_id" required class="select-modern flex-1">
                                <option value="" disabled>Select Teacher</option>
                                <option v-for="t in availableTeachers" :key="t.id" :value="t.id">{{ t.name }}</option>
                            </select>
                            <button type="submit" :disabled="subjectForm.processing" class="btn-primary whitespace-nowrap !py-2">Assign</button>
                        </form>
                    </div>

                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th class="text-left">Subject</th>
                                <th class="text-left">Teacher</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cs in schoolClass.subjects" :key="cs.id">
                                <td class="font-semibold text-slate-900">{{ cs.subject?.name }}</td>
                                <td>{{ cs.teacher?.name }}</td>
                                <td class="text-right">
                                    <button @click="confirmRemoveSubject(cs)" class="px-2.5 py-1.5 text-[12px] font-medium text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">Remove</button>
                                </td>
                            </tr>
                            <tr v-if="!schoolClass.subjects?.length">
                                <td colspan="3" class="!text-center !py-8 text-slate-400">No subjects assigned.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Enrolled Students -->
                <div class="card overflow-hidden animate-fade-in-up">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                            Enrolled Students
                            <span class="ml-auto badge bg-slate-100 text-slate-600 ring-1 ring-slate-200">{{ schoolClass.students?.length || 0 }}</span>
                        </h3>
                    </div>

                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <form @submit.prevent="enrollStudent" class="flex gap-2">
                            <select v-model="studentForm.student_id" required class="select-modern flex-1">
                                <option value="" disabled>Select Student</option>
                                <option v-for="s in availableStudents" :key="s.id" :value="s.id">{{ s.name }} ({{ s.email }})</option>
                            </select>
                            <button type="submit" :disabled="studentForm.processing" class="btn-primary whitespace-nowrap !py-2">Enroll</button>
                        </form>
                    </div>

                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th class="text-left">Name</th>
                                <th class="text-left">Email</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="student in schoolClass.students" :key="student.id">
                                <td class="font-semibold text-slate-900">{{ student.name }}</td>
                                <td>{{ student.email }}</td>
                                <td class="text-right">
                                    <button @click="confirmRemoveStudent({ id: student.pivot?.id || student.id, student })" class="px-2.5 py-1.5 text-[12px] font-medium text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">Remove</button>
                                </td>
                            </tr>
                            <tr v-if="!schoolClass.students?.length">
                                <td colspan="3" class="!text-center !py-8 text-slate-400">No students enrolled.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <Modal :show="showDeleteModal" title="Confirm Removal" :message="deleteMessage" @confirm="executeDelete" @cancel="showDeleteModal = false" />
        </div>
    </AdminLayout>
</template>
