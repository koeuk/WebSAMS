<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ user: Object });

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
});

const submit = () => {
    form.put(`/admin/users/${props.user.id}`);
};
</script>

<template>
    <AdminLayout>
        <div class="flex items-center gap-4 mb-6">
            <Link href="/admin/users" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back</Link>
            <h2 class="text-2xl font-bold text-gray-900">Edit User</h2>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-gray-400">(leave blank to keep)</span></label>
                        <input v-model="form.password" type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
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
                        <input v-model="form.id_number" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
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
                            <input v-model="form.department" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Qualification</label>
                            <input v-model="form.qualification" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
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
                    {{ form.processing ? 'Saving...' : 'Save Changes' }}
                </button>
            </form>
        </div>
    </AdminLayout>
</template>
