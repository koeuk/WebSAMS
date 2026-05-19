<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DatePicker from '@/Components/DatePicker.vue';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Textarea } from '@/Components/ui/textarea';
import { Card, CardContent } from '@/Components/ui/card';
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select';

const photoPreview = ref(null);
const handlePhoto = (e) => {
    const file = e.target.files[0];
    form.profile_photo = file;
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => { photoPreview.value = e.target.result; };
        reader.readAsDataURL(file);
    }
};

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: 'student',
    phone: '',
    year_level: '',
    id_number: '',
    gender: '',
    date_of_birth: '',
    address: '',
    status: 'active',
    guardian_name: '',
    guardian_phone: '',
    enrollment_date: '',
    department: '',
    qualification: '',
    hire_date: '',
    profile_photo: null,
});

const submit = () => {
    form.post('/admin/users');
};
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center gap-4 mb-8">
                <Link href="/admin/users" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                    Back
                </Link>
                <div class="h-5 w-px bg-slate-200"></div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Create User</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Add a new user to the system</p>
                </div>
            </div>

            <Card class="max-w-2xl">
                <CardContent class="p-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                                Basic Info
                            </h3>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                                        <Input v-model="form.name" type="text" required />
                                        <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Email *</Label>
                                        <Input v-model="form.email" type="email" required />
                                        <p v-if="form.errors.email" class="text-[12px] text-rose-500 mt-1">{{ form.errors.email }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Password *</Label>
                                        <Input v-model="form.password" type="password" required />
                                        <p v-if="form.errors.password" class="text-[12px] text-rose-500 mt-1">{{ form.errors.password }}</p>
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Role *</Label>
                                        <Select v-model="form.role">
                                            <SelectTrigger class="w-full">
                                                <SelectValue placeholder="Select role" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="admin">Admin</SelectItem>
                                                <SelectItem value="teacher">Teacher</SelectItem>
                                                <SelectItem value="student">Student</SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">ID Number</Label>
                                        <Input v-model="form.id_number" type="text" :placeholder="form.role === 'student' ? 'STU-2025-001' : 'TCH-001'" />
                                        <p v-if="form.errors.id_number" class="text-[12px] text-rose-500 mt-1">{{ form.errors.id_number }}</p>
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Gender</Label>
                                        <Select v-model="form.gender">
                                            <SelectTrigger class="w-full">
                                                <SelectValue placeholder="Select" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="male">Male</SelectItem>
                                                <SelectItem value="female">Female</SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Status</Label>
                                        <Select v-model="form.status">
                                            <SelectTrigger class="w-full">
                                                <SelectValue placeholder="Select status" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="active">Active</SelectItem>
                                                <SelectItem value="inactive">Inactive</SelectItem>
                                                <SelectItem value="graduated">Graduated</SelectItem>
                                                <SelectItem value="suspended">Suspended</SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Date of Birth</Label>
                                        <DatePicker v-model="form.date_of_birth" placeholder="Pick date of birth" />
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Phone</Label>
                                        <Input v-model="form.phone" type="text" />
                                    </div>
                                </div>
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Profile Photo</Label>
                                    <div class="flex items-center gap-4">
                                        <img v-if="photoPreview" :src="photoPreview" class="h-16 w-16 rounded-xl object-cover ring-2 ring-slate-100" />
                                        <div v-else class="h-16 w-16 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/></svg>
                                        </div>
                                        <Button variant="outline" class="cursor-pointer text-[13px] flex items-center gap-2" as-child>
                                            <label>
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                Upload
                                                <input type="file" accept="image/*" @change="handlePhoto" class="hidden" />
                                            </label>
                                        </Button>
                                    </div>
                                </div>
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Address</Label>
                                    <Textarea v-model="form.address" rows="2" />
                                </div>
                            </div>
                        </div>

                        <!-- Student-specific -->
                        <template v-if="form.role === 'student'">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                                    Student Info
                                </h3>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Year Level</Label>
                                            <Select v-model="form.year_level">
                                                <SelectTrigger class="w-full">
                                                    <SelectValue placeholder="Select" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem value="1">Year 1</SelectItem>
                                                    <SelectItem value="2">Year 2</SelectItem>
                                                    <SelectItem value="3">Year 3</SelectItem>
                                                    <SelectItem value="4">Year 4</SelectItem>
                                                </SelectContent>
                                            </Select>
                                        </div>
                                        <div>
                                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Enrollment Date</Label>
                                            <DatePicker v-model="form.enrollment_date" placeholder="Pick enrollment date" />
                                        </div>
                                        <div>
                                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Guardian Name</Label>
                                            <Input v-model="form.guardian_name" type="text" />
                                        </div>
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Guardian Phone</Label>
                                        <Input v-model="form.guardian_phone" type="text" />
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Teacher-specific -->
                        <template v-if="form.role === 'teacher'">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-sky-400"></div>
                                    Teacher Info
                                </h3>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Department</Label>
                                        <Input v-model="form.department" type="text" placeholder="e.g. Computer Science" />
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Qualification</Label>
                                        <Input v-model="form.qualification" type="text" placeholder="e.g. Master in CS" />
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Hire Date</Label>
                                        <DatePicker v-model="form.hire_date" placeholder="Pick hire date" />
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="pt-2">
                            <Button type="submit" :disabled="form.processing" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                                {{ form.processing ? 'Creating...' : 'Create User' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>
