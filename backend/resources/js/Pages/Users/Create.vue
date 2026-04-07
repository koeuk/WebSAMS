<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: 'student',
    phone: '',
    year_level: '',
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

        <div class="bg-white rounded-lg border border-gray-200 p-6 max-w-xl">
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input v-model="form.name" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                    <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input v-model="form.email" type="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                    <p v-if="form.errors.email" class="text-sm text-red-600 mt-1">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input v-model="form.password" type="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                    <p v-if="form.errors.password" class="text-sm text-red-600 mt-1">{{ form.errors.password }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select v-model="form.role" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="admin">Admin</option>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                    </select>
                    <p v-if="form.errors.role" class="text-sm text-red-600 mt-1">{{ form.errors.role }}</p>
                </div>

                <div v-if="form.role === 'student'">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
                    <select v-model="form.year_level" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="">Select Year</option>
                        <option value="1">Year 1</option>
                        <option value="2">Year 2</option>
                        <option value="3">Year 3</option>
                        <option value="4">Year 4</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input v-model="form.phone" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                </div>

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
