<script setup>
import { useForm, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ user: Object });

const photoPreview = ref(props.user.profile_photo ? `/storage/${props.user.profile_photo}` : null);
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
    name: props.user.name,
    email: props.user.email,
    password: '',
    role: props.user.role,
    phone: props.user.phone || '',
    year_level: props.user.year_level || '',
    id_number: props.user.id_number || '',
    gender: props.user.gender || '',
    date_of_birth: props.user.date_of_birth?.split('T')[0] || '',
    address: props.user.address || '',
    status: props.user.status || 'active',
    guardian_name: props.user.guardian_name || '',
    guardian_phone: props.user.guardian_phone || '',
    enrollment_date: props.user.enrollment_date?.split('T')[0] || '',
    department: props.user.department || '',
    qualification: props.user.qualification || '',
    hire_date: props.user.hire_date?.split('T')[0] || '',
    profile_photo: null,
});

const submit = () => {
    form.post(`/admin/users/${props.user.id}`, {
        _method: 'PUT',
        forceFormData: true,
    });
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
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Edit User</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Update {{ user.name }}'s information</p>
                </div>
            </div>

            <div class="card p-8 max-w-2xl">
                <form @submit.prevent="submit" class="space-y-6">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                            Basic Info
                        </h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</label>
                                    <input v-model="form.name" type="text" required class="input-modern" />
                                    <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Email *</label>
                                    <input v-model="form.email" type="email" required class="input-modern" />
                                    <p v-if="form.errors.email" class="text-[12px] text-rose-500 mt-1">{{ form.errors.email }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Password <span class="text-slate-400">(leave blank to keep)</span></label>
                                    <input v-model="form.password" type="password" class="input-modern" />
                                </div>
                                <div>
                                    <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Role *</label>
                                    <select v-model="form.role" class="select-modern w-full">
                                        <option value="admin">Admin</option>
                                        <option value="teacher">Teacher</option>
                                        <option value="student">Student</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[13px] font-medium text-slate-600 mb-1.5">ID Number</label>
                                    <input v-model="form.id_number" type="text" class="input-modern" />
                                    <p v-if="form.errors.id_number" class="text-[12px] text-rose-500 mt-1">{{ form.errors.id_number }}</p>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Gender</label>
                                    <select v-model="form.gender" class="select-modern w-full">
                                        <option value="">Select</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Status</label>
                                    <select v-model="form.status" class="select-modern w-full">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="graduated">Graduated</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Date of Birth</label>
                                    <input v-model="form.date_of_birth" type="date" class="input-modern" />
                                </div>
                                <div>
                                    <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Phone</label>
                                    <input v-model="form.phone" type="text" class="input-modern" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Profile Photo</label>
                                <div class="flex items-center gap-4">
                                    <img v-if="photoPreview" :src="photoPreview" class="h-16 w-16 rounded-xl object-cover ring-2 ring-slate-100" />
                                    <div v-else class="h-16 w-16 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/></svg>
                                    </div>
                                    <label class="btn-secondary cursor-pointer text-[13px]">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        Upload
                                        <input type="file" accept="image/*" @change="handlePhoto" class="hidden" />
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Address</label>
                                <textarea v-model="form.address" rows="2" class="input-modern" />
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
                                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Year Level</label>
                                        <select v-model="form.year_level" class="select-modern w-full">
                                            <option value="">Select</option>
                                            <option value="1">Year 1</option>
                                            <option value="2">Year 2</option>
                                            <option value="3">Year 3</option>
                                            <option value="4">Year 4</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Enrollment Date</label>
                                        <input v-model="form.enrollment_date" type="date" class="input-modern" />
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Guardian Name</label>
                                        <input v-model="form.guardian_name" type="text" class="input-modern" />
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Guardian Phone</label>
                                    <input v-model="form.guardian_phone" type="text" class="input-modern" />
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
                                    <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Department</label>
                                    <input v-model="form.department" type="text" class="input-modern" placeholder="e.g. Computer Science" />
                                </div>
                                <div>
                                    <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Qualification</label>
                                    <input v-model="form.qualification" type="text" class="input-modern" placeholder="e.g. Master in CS" />
                                </div>
                                <div>
                                    <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Hire Date</label>
                                    <input v-model="form.hire_date" type="date" class="input-modern" />
                                </div>
                            </div>
                        </div>
                    </template>

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
