<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';
import ModalForm from '@/Components/ModalForm.vue';
import FilterCombobox from '@/Components/FilterCombobox.vue';
import DatePicker from '@/Components/DatePicker.vue';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Label } from '@/Components/ui/label';
import { Textarea } from '@/Components/ui/textarea';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select';

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

// ── Delete ──────────────────────────────────────────────────────────────────
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

// ── Create Dialog ────────────────────────────────────────────────────────────
const showCreateDialog = ref(false);

const createForm = useForm({
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
});

const submitCreate = () => {
    createForm.post('/admin/users', {
        onSuccess: () => {
            showCreateDialog.value = false;
            createForm.reset();
        },
    });
};

// ── Edit Dialog ──────────────────────────────────────────────────────────────
const showEditDialog = ref(false);
const editingItem = ref(null);

const editForm = useForm({
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
});

const openEdit = (user) => {
    editingItem.value = user;
    editForm.name = user.name;
    editForm.email = user.email;
    editForm.password = '';
    editForm.role = user.role;
    editForm.phone = user.phone || '';
    editForm.year_level = user.year_level || '';
    editForm.id_number = user.id_number || '';
    editForm.gender = user.gender || '';
    editForm.date_of_birth = user.date_of_birth?.split('T')[0] || '';
    editForm.address = user.address || '';
    editForm.status = user.status || 'active';
    editForm.guardian_name = user.guardian_name || '';
    editForm.guardian_phone = user.guardian_phone || '';
    editForm.enrollment_date = user.enrollment_date?.split('T')[0] || '';
    editForm.department = user.department || '';
    editForm.qualification = user.qualification || '';
    editForm.hire_date = user.hire_date?.split('T')[0] || '';
    showEditDialog.value = true;
};

const submitEdit = () => {
    editForm.put(`/admin/users/${editingItem.value.id}`, {
        onSuccess: () => {
            showEditDialog.value = false;
        },
    });
};

// ── Badges ───────────────────────────────────────────────────────────────────
const roleBadgeVariant = (role) => {
    if (role === 'admin') return 'default';
    if (role === 'teacher') return 'secondary';
    return 'outline';
};

const statusBadgeVariant = (status) => {
    if (status === 'active') return 'default';
    if (status === 'suspended') return 'destructive';
    return 'secondary';
};
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Users</h2>
                    <p class="text-sm text-slate-500 mt-1">Manage students, teachers, and administrators</p>
                </div>
                <Button @click="showCreateDialog = true" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Create User
                </Button>
            </div>

            <FlashMessage />

            <!-- Filters -->
            <div class="card p-4 mb-6">
                <div class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-50">
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Search</label>
                        <Input
                            v-model="search"
                            type="text"
                            placeholder="Search by name or email..."
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
                    <Button
                        v-if="search || roleFilter || yearFilter || statusFilter"
                        variant="outline"
                        size="sm"
                        @click="clearFilters"
                        class="self-end flex items-center gap-1.5"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        Clear
                    </Button>
                </div>
            </div>

            <!-- Table -->
            <div class="card overflow-hidden">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="text-left">ID</TableHead>
                            <TableHead class="text-left">Name</TableHead>
                            <TableHead class="text-left">Email</TableHead>
                            <TableHead class="text-left">Role</TableHead>
                            <TableHead class="text-left">Gender</TableHead>
                            <TableHead class="text-left">Status</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="user in users.data" :key="user.id">
                            <TableCell class="text-slate-400 font-mono text-[13px]">{{ user.id_number || '-' }}</TableCell>
                            <TableCell class="font-semibold text-slate-900">
                                <Link :href="`/admin/users/${user.id}`" class="hover:text-beltei transition-colors">{{ user.name }}</Link>
                            </TableCell>
                            <TableCell>{{ user.email }}</TableCell>
                            <TableCell>
                                <Badge :variant="roleBadgeVariant(user.role)" class="capitalize">{{ user.role }}</Badge>
                            </TableCell>
                            <TableCell class="capitalize">{{ user.gender || '-' }}</TableCell>
                            <TableCell>
                                <Badge :variant="statusBadgeVariant(user.status)" class="capitalize">{{ user.status || 'active' }}</Badge>
                            </TableCell>
                            <TableCell class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <Button variant="ghost" size="sm" as-child>
                                        <Link :href="`/admin/users/${user.id}`">View</Link>
                                    </Button>
                                    <Button variant="ghost" size="sm" class="text-blue-600 hover:text-blue-700 hover:bg-blue-50" @click="openEdit(user)">Edit</Button>
                                    <Button variant="ghost" size="sm" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50" @click="confirmDelete(user)">Delete</Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!users.data?.length">
                            <TableCell colspan="7" class="text-center py-12 text-slate-400">No users found.</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <Pagination :links="users.links" />

            <!-- Delete Modal -->
            <Modal
                :show="showDeleteModal"
                title="Delete User"
                :message="`Are you sure you want to delete ${userToDelete?.name}?`"
                @confirm="deleteUser"
                @cancel="showDeleteModal = false"
            />

            <!-- ── Create Dialog ──────────────────────────────────────────── -->
            <ModalForm v-model:open="showCreateDialog" title="Create User" description="Add a new user to the system." size="lg">
                <form @submit.prevent="submitCreate">
                    <div class="max-h-[70vh] overflow-y-auto pr-1 space-y-6 py-2">

                        <!-- Basic Info -->
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                                Basic Info
                            </h3>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                                        <Input v-model="createForm.name" type="text" required />
                                        <p v-if="createForm.errors.name" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.name }}</p>
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Email *</Label>
                                        <Input v-model="createForm.email" type="email" required />
                                        <p v-if="createForm.errors.email" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.email }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Password *</Label>
                                        <Input v-model="createForm.password" type="password" required />
                                        <p v-if="createForm.errors.password" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.password }}</p>
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Role *</Label>
                                        <Select v-model="createForm.role">
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
                                        <Input v-model="createForm.id_number" type="text" :placeholder="createForm.role === 'student' ? 'STU-2025-001' : 'TCH-001'" />
                                        <p v-if="createForm.errors.id_number" class="text-[12px] text-rose-500 mt-1">{{ createForm.errors.id_number }}</p>
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Gender</Label>
                                        <Select v-model="createForm.gender">
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
                                        <Select v-model="createForm.status">
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
                                        <DatePicker v-model="createForm.date_of_birth" placeholder="Pick date of birth" />
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Phone</Label>
                                        <Input v-model="createForm.phone" type="text" />
                                    </div>
                                </div>
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Address</Label>
                                    <Textarea v-model="createForm.address" rows="2" />
                                </div>
                            </div>
                        </div>

                        <!-- Student-specific -->
                        <template v-if="createForm.role === 'student'">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                                    Student Info
                                </h3>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Year Level</Label>
                                            <Select v-model="createForm.year_level">
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
                                            <DatePicker v-model="createForm.enrollment_date" placeholder="Pick enrollment date" />
                                        </div>
                                        <div>
                                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Guardian Name</Label>
                                            <Input v-model="createForm.guardian_name" type="text" />
                                        </div>
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Guardian Phone</Label>
                                        <Input v-model="createForm.guardian_phone" type="text" />
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Teacher-specific -->
                        <template v-if="createForm.role === 'teacher'">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-sky-400"></div>
                                    Teacher Info
                                </h3>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Department</Label>
                                        <Input v-model="createForm.department" type="text" placeholder="e.g. Computer Science" />
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Qualification</Label>
                                        <Input v-model="createForm.qualification" type="text" placeholder="e.g. Master in CS" />
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Hire Date</Label>
                                        <DatePicker v-model="createForm.hire_date" placeholder="Pick hire date" />
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <Button type="button" variant="outline" @click="showCreateDialog = false">Cancel</Button>
                        <Button type="submit" :disabled="createForm.processing" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                            {{ createForm.processing ? 'Creating...' : 'Create User' }}
                        </Button>
                    </div>
                </form>
            </ModalForm>

            <!-- ── Edit Dialog ────────────────────────────────────────────── -->
            <ModalForm v-model:open="showEditDialog" title="Edit User" :description="`Update ${editingItem?.name}'s information.`" size="lg">
                <form @submit.prevent="submitEdit">
                    <div class="max-h-[70vh] overflow-y-auto pr-1 space-y-6 py-2">

                        <!-- Basic Info -->
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                                Basic Info
                            </h3>
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                                        <Input v-model="editForm.name" type="text" required />
                                        <p v-if="editForm.errors.name" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.name }}</p>
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Email *</Label>
                                        <Input v-model="editForm.email" type="email" required />
                                        <p v-if="editForm.errors.email" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.email }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Password <span class="text-slate-400">(leave blank to keep)</span></Label>
                                        <Input v-model="editForm.password" type="password" />
                                        <p v-if="editForm.errors.password" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.password }}</p>
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Role *</Label>
                                        <Select v-model="editForm.role">
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
                                        <Input v-model="editForm.id_number" type="text" />
                                        <p v-if="editForm.errors.id_number" class="text-[12px] text-rose-500 mt-1">{{ editForm.errors.id_number }}</p>
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Gender</Label>
                                        <Select v-model="editForm.gender">
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
                                        <Select v-model="editForm.status">
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
                                        <DatePicker v-model="editForm.date_of_birth" placeholder="Pick date of birth" />
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Phone</Label>
                                        <Input v-model="editForm.phone" type="text" />
                                    </div>
                                </div>
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Address</Label>
                                    <Textarea v-model="editForm.address" rows="2" />
                                </div>
                            </div>
                        </div>

                        <!-- Student-specific -->
                        <template v-if="editForm.role === 'student'">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                                    Student Info
                                </h3>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Year Level</Label>
                                            <Select v-model="editForm.year_level">
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
                                            <DatePicker v-model="editForm.enrollment_date" placeholder="Pick enrollment date" />
                                        </div>
                                        <div>
                                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Guardian Name</Label>
                                            <Input v-model="editForm.guardian_name" type="text" />
                                        </div>
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Guardian Phone</Label>
                                        <Input v-model="editForm.guardian_phone" type="text" />
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Teacher-specific -->
                        <template v-if="editForm.role === 'teacher'">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-sky-400"></div>
                                    Teacher Info
                                </h3>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Department</Label>
                                        <Input v-model="editForm.department" type="text" placeholder="e.g. Computer Science" />
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Qualification</Label>
                                        <Input v-model="editForm.qualification" type="text" placeholder="e.g. Master in CS" />
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Hire Date</Label>
                                        <DatePicker v-model="editForm.hire_date" placeholder="Pick hire date" />
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <Button type="button" variant="outline" @click="showEditDialog = false">Cancel</Button>
                        <Button type="submit" :disabled="editForm.processing" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                            {{ editForm.processing ? 'Saving...' : 'Save Changes' }}
                        </Button>
                    </div>
                </form>
            </ModalForm>

        </div>
    </AdminLayout>
</template>
