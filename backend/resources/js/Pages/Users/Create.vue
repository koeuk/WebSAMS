<script setup>
import { useForm, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

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
        <div class="flex items-center gap-4 mb-6">
            <Link href="/admin/users" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back</Link>
            <h2 class="text-2xl font-bold text-gray-900">Create User</h2>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 max-w-2xl">
            <form @submit.prevent="submit" class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 border-b pb-2">Basic Info</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input v-model="form.name" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                        <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input v-model="form.email" type="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                        <p v-if="form.errors.email" class="text-sm text-red-600 mt-1">{{ form.errors.email }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                        <input v-model="form.password" type="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                        <p v-if="form.errors.password" class="text-sm text-red-600 mt-1">{{ form.errors.password }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                        <select v-model="form.role" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                            <option value="admin">Admin</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID Number</label>
                        <input v-model="form.id_number" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" :placeholder="form.role === 'student' ? 'STU-2025-001' : 'TCH-001'" />
                        <p v-if="form.errors.id_number" class="text-sm text-red-600 mt-1">{{ form.errors.id_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select v-model="form.gender" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                            <option value="">Select</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select v-model="form.status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="graduated">Graduated</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                        <input v-model="form.date_of_birth" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input v-model="form.phone" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                    <div class="flex items-center gap-4">
                        <img v-if="photoPreview" :src="photoPreview" class="h-16 w-16 rounded-full object-cover border" />
                        <div v-else class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 text-xs">No photo</div>
                        <input type="file" accept="image/*" @change="handlePhoto" class="text-sm" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <textarea v-model="form.address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                </div>

                <!-- Student-specific -->
                <template v-if="form.role === 'student'">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 pt-2">Student Info</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
                            <select v-model="form.year_level" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                <option value="">Select</option>
                                <option value="1">Year 1</option>
                                <option value="2">Year 2</option>
                                <option value="3">Year 3</option>
                                <option value="4">Year 4</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Enrollment Date</label>
                            <input v-model="form.enrollment_date" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Guardian Name</label>
                            <input v-model="form.guardian_name" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Guardian Phone</label>
                        <input v-model="form.guardian_phone" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                    </div>
                </template>

                <!-- Teacher-specific -->
                <template v-if="form.role === 'teacher'">
                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 pt-2">Teacher Info</h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                            <input v-model="form.department" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g. Computer Science" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Qualification</label>
                            <input v-model="form.qualification" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g. Master in CS" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hire Date</label>
                            <input v-model="form.hire_date" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                        </div>
                    </div>
                </template>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark disabled:opacity-50"
                >
                    {{ form.processing ? 'Creating...' : 'Create User' }}
                </button>
            </form>
        </div>
    </AdminLayout>
</template>
