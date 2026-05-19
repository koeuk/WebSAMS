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
                <Card class="overflow-hidden animate-fade-in-up">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                            Subjects & Teachers
                        </h3>
                    </div>

                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <form @submit.prevent="assignSubject" class="flex gap-2">
                            <Select v-model="subjectForm.subject_id" required class="flex-1">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Subject" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="s in availableSubjects" :key="s.id" :value="String(s.id)">{{ s.name }} ({{ s.code }})</SelectItem>
                                </SelectContent>
                            </Select>
                            <Select v-model="subjectForm.teacher_id" required class="flex-1">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Teacher" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="t in availableTeachers" :key="t.id" :value="String(t.id)">{{ t.name }}</SelectItem>
                                </SelectContent>
                            </Select>
                            <Button type="submit" :disabled="subjectForm.processing" class="whitespace-nowrap">Assign</Button>
                        </form>
                    </div>

                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Subject</TableHead>
                                <TableHead>Teacher</TableHead>
                                <TableHead class="text-right">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="cs in schoolClass.subjects" :key="cs.id">
                                <TableCell class="font-semibold text-slate-900">{{ cs.subject?.name }}</TableCell>
                                <TableCell>{{ cs.teacher?.name }}</TableCell>
                                <TableCell class="text-right">
                                    <Button variant="ghost" size="sm" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50" @click="confirmRemoveSubject(cs)">Remove</Button>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="!schoolClass.subjects?.length">
                                <TableCell colspan="3" class="text-center py-8 text-slate-400">No subjects assigned.</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </Card>

                <!-- Enrolled Students -->
                <Card class="overflow-hidden animate-fade-in-up">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                            Enrolled Students
                            <Badge variant="secondary" class="ml-auto">{{ schoolClass.students?.length || 0 }}</Badge>
                        </h3>
                    </div>

                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <form @submit.prevent="enrollStudent" class="flex gap-2">
                            <Select v-model="studentForm.student_id" required class="flex-1">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Student" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="s in availableStudents" :key="s.id" :value="String(s.id)">{{ s.name }} ({{ s.email }})</SelectItem>
                                </SelectContent>
                            </Select>
                            <Button type="submit" :disabled="studentForm.processing" class="whitespace-nowrap">Enroll</Button>
                        </form>
                    </div>

                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Name</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead class="text-right">Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="student in schoolClass.students" :key="student.id">
                                <TableCell class="font-semibold text-slate-900">{{ student.name }}</TableCell>
                                <TableCell>{{ student.email }}</TableCell>
                                <TableCell class="text-right">
                                    <Button variant="ghost" size="sm" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50" @click="confirmRemoveStudent({ id: student.pivot?.id || student.id, student })">Remove</Button>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="!schoolClass.students?.length">
                                <TableCell colspan="3" class="text-center py-8 text-slate-400">No students enrolled.</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </Card>
            </div>

            <Modal :show="showDeleteModal" title="Confirm Removal" :message="deleteMessage" @confirm="executeDelete" @cancel="showDeleteModal = false" />
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { Card, CardContent } from '@/Components/ui/card';
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select';

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
