<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';
import FilterCombobox from '@/Components/FilterCombobox.vue';

const roleOptions = [
    { value: 'admin', label: 'Admin' },
    { value: 'teacher', label: 'Teacher' },
    { value: 'student', label: 'Student' },
];
const yearOptions = [
    { value: '1', label: 'Year 1' },
    { value: '2', label: 'Year 2' },
    { value: '3', label: 'Year 3' },
    { value: '4', label: 'Year 4' },
];
const statusOptions = [
    { value: 'active', label: 'Active' },
    { value: 'inactive', label: 'Inactive' },
    { value: 'graduated', label: 'Graduated' },
    { value: 'suspended', label: 'Suspended' },
];

const props = defineProps({
    users: Object,
    filters: Object,
});

const search = ref(props.filters?.search || '');
const roleFilter = ref(props.filters?.role || '');
const yearFilter = ref(props.filters?.year_level || '');
const statusFilter = ref(props.filters?.status || '');

const applyFilters = () => {
    router.get('/admin/users', {
        search: search.value || undefined,
        role: roleFilter.value || undefined,
        year_level: yearFilter.value || undefined,
        status: statusFilter.value || undefined,
    }, { preserveState: true });
};

const clearFilters = () => {
    search.value = '';
    roleFilter.value = '';
    yearFilter.value = '';
    statusFilter.value = '';
    router.get('/admin/users', {}, { preserveState: false });
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
    'bg-violet-50 text-violet-700 ring-1 ring-violet-200': role === 'admin',
    'bg-sky-50 text-sky-700 ring-1 ring-sky-200': role === 'teacher',
    'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200': role === 'student',
});

const statusBadgeClass = (status) => ({
    'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200': status === 'active',
    'bg-slate-100 text-slate-600 ring-1 ring-slate-200': status === 'inactive',
    'bg-sky-50 text-sky-700 ring-1 ring-sky-200': status === 'graduated',
    'bg-rose-50 text-rose-700 ring-1 ring-rose-200': status === 'suspended',
});
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Users</h2>
                    <p class="text-sm text-slate-500 mt-1">Manage students, teachers, and administrators</p>
                </div>
                <Link href="/admin/users/create" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Create User
                </Link>
            </div>

            <FlashMessage />

            <!-- Filters -->
            <div class="card p-4 mb-6">
                <div class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-50">
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Search</label>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Search by name or email..."
                            class="input-modern"
                            @keyup.enter="applyFilters"
                        />
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Role</label>
                        <FilterCombobox v-model="roleFilter" :options="roleOptions" placeholder="All Roles" @update:model-value="applyFilters" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Year Level</label>
                        <FilterCombobox v-model="yearFilter" :options="yearOptions" placeholder="All Years" @update:model-value="applyFilters" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Status</label>
                        <FilterCombobox v-model="statusFilter" :options="statusOptions" placeholder="All Status" @update:model-value="applyFilters" />
                    </div>
                    <button v-if="search || roleFilter || yearFilter || statusFilter" @click="clearFilters" class="flex items-center gap-1.5 px-3 py-2 text-[12px] font-medium text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg border border-slate-200 transition-colors self-end">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        Clear
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="card overflow-hidden">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th class="text-left">ID</th>
                            <th class="text-left">Name</th>
                            <th class="text-left">Email</th>
                            <th class="text-left">Role</th>
                            <th class="text-left">Gender</th>
                            <th class="text-left">Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users.data" :key="user.id">
                            <td class="text-slate-400 font-mono text-[13px]">{{ user.id_number || '-' }}</td>
                            <td class="font-semibold text-slate-900">
                                <Link :href="`/admin/users/${user.id}`" class="hover:text-beltei transition-colors">{{ user.name }}</Link>
                            </td>
                            <td>{{ user.email }}</td>
                            <td><span class="badge" :class="roleBadgeClass(user.role)">{{ user.role }}</span></td>
                            <td class="capitalize">{{ user.gender || '-' }}</td>
                            <td><span class="badge" :class="statusBadgeClass(user.status)">{{ user.status || 'active' }}</span></td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <Link :href="`/admin/users/${user.id}`" class="px-2.5 py-1.5 text-[12px] font-medium text-slate-500 hover:text-slate-700 hover:bg-slate-50 rounded-lg transition-colors">View</Link>
                                    <Link :href="`/admin/users/${user.id}/edit`" class="px-2.5 py-1.5 text-[12px] font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors">Edit</Link>
                                    <button @click="confirmDelete(user)" class="px-2.5 py-1.5 text-[12px] font-medium text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!users.data?.length">
                            <td colspan="7" class="text-center! py-12! text-slate-400">No users found.</td>
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
        </div>
    </AdminLayout>
</template>
