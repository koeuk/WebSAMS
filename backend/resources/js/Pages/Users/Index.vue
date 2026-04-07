<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    users: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const roleFilter = ref(props.filters?.role || '');

const applyFilters = () => {
    router.get('/admin/users', {
        search: search.value || undefined,
        role: roleFilter.value || undefined,
    }, { preserveState: true });
};

const showDeleteModal = ref(false);
const userToDelete = ref(null);

const confirmDelete = (user) => {
    userToDelete.value = user;
    showDeleteModal.value = true;
};

const deleteUser = () => {
    router.delete(`/admin/users/${userToDelete.value.id}`, {
        onFinish: () => {
            showDeleteModal.value = false;
            userToDelete.value = null;
        },
    });
};

const roleBadgeClass = (role) => ({
    'bg-purple-100 text-purple-800': role === 'admin',
    'bg-blue-100 text-blue-800': role === 'teacher',
    'bg-green-100 text-green-800': role === 'student',
});
</script>

<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Users</h2>
            <Link
                href="/admin/users/create"
                class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark"
            >
                Create User
            </Link>
        </div>

        <FlashMessage />

        <!-- Filters -->
        <div class="flex gap-4 mb-4">
            <input
                v-model="search"
                type="text"
                placeholder="Search by name or email..."
                class="px-3 py-2 border border-gray-300 rounded-md text-sm w-64"
                @keyup.enter="applyFilters"
            />
            <select
                v-model="roleFilter"
                class="px-3 py-2 border border-gray-300 rounded-md text-sm"
                @change="applyFilters"
            >
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="teacher">Teacher</option>
                <option value="student">Student</option>
            </select>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Gender</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users.data" :key="user.id" class="border-b border-gray-100">
                        <td class="px-6 py-3 text-sm text-gray-600">{{ user.id_number || '-' }}</td>
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">
                            <Link :href="`/admin/users/${user.id}`" class="hover:text-blue-600">{{ user.name }}</Link>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ user.email }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full" :class="roleBadgeClass(user.role)">
                                {{ user.role }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-600 capitalize">{{ user.gender || '-' }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                                :class="{
                                    'bg-green-100 text-green-800': user.status === 'active',
                                    'bg-gray-100 text-gray-800': user.status === 'inactive',
                                    'bg-blue-100 text-blue-800': user.status === 'graduated',
                                    'bg-red-100 text-red-800': user.status === 'suspended',
                                }">
                                {{ user.status || 'active' }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right">
                            <Link :href="`/admin/users/${user.id}`" class="text-sm text-gray-600 hover:text-gray-800 mr-2">View</Link>
                            <Link :href="`/admin/users/${user.id}/edit`" class="text-sm text-blue-600 hover:text-blue-800 mr-2">Edit</Link>
                            <button @click="confirmDelete(user)" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="!users.data?.length">
                        <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">No users found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :links="users.links" />

        <Modal
            :show="showDeleteModal"
            title="Delete User"
            :message="`Are you sure you want to delete ${userToDelete?.name}?`"
            @confirm="deleteUser"
            @cancel="showDeleteModal = false"
        />
    </AdminLayout>
</template>
