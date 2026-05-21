<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('Users') }}</h2>
                    <p class="text-sm text-slate-500 mt-1">{{ __('Manage students, teachers, and administrators') }}</p>
                </div>
                <Button @click="openCreate" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    {{ __('Create User') }}
                </Button>
            </div>


            <!-- Filters -->
            <div class="card p-4 mb-6">
                <div class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-50">
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Search') }}</label>
                        <Input
                            v-model="search"
                            type="text"
                            :placeholder="__('Search by name or email...')"
                            @keyup.enter="applyFilters"
                        />
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Role') }}</label>
                        <FilterCombobox v-model="roleFilter" :options="roleOptions" :placeholder="__('All Roles')" @update:model-value="applyFilters" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Year Level') }}</label>
                        <FilterCombobox v-model="yearFilter" :options="yearOptions" :placeholder="__('All Years')" @update:model-value="applyFilters" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Status') }}</label>
                        <FilterCombobox v-model="statusFilter" :options="statusOptions" :placeholder="__('All Status')" @update:model-value="applyFilters" />
                    </div>
                    <Button
                        v-if="search || roleFilter || yearFilter || statusFilter"
                        variant="outline"
                        size="sm"
                        @click="clearFilters"
                        class="self-end flex items-center gap-1.5"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        {{ __('Clear') }}
                    </Button>
                </div>
            </div>

            <!-- Table -->
            <div class="card overflow-hidden">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="text-left">{{ __('ID') }}</TableHead>
                            <TableHead class="text-left">{{ __('Name') }}</TableHead>
                            <TableHead class="text-left">{{ __('Email') }}</TableHead>
                            <TableHead class="text-left">{{ __('Role') }}</TableHead>
                            <TableHead class="text-left">{{ __('Gender') }}</TableHead>
                            <TableHead class="text-left">{{ __('Status') }}</TableHead>
                            <TableHead class="text-right">{{ __('Actions') }}</TableHead>
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
                                        <Link :href="`/admin/users/${user.id}`">{{ __('View') }}</Link>
                                    </Button>
                                    <Button variant="ghost" size="sm" class="text-blue-600 hover:text-blue-700 hover:bg-blue-50" @click="openEdit(user)">{{ __('Edit') }}</Button>
                                    <Button variant="ghost" size="sm" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50" @click="router.visit(`/admin/users/${user.id}/delete`, { preserveState: true, preserveScroll: true })">{{ __('Delete') }}</Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!users.data?.length">
                            <TableCell colspan="7" class="p-0">
                                <Empty class="border-0 rounded-none">
                                    <EmptyHeader>
                                        <EmptyMedia variant="icon"><Users class="w-6 h-6" /></EmptyMedia>
                                        <EmptyTitle>{{ __('No users found') }}</EmptyTitle>
                                        <EmptyDescription>{{ __('Try adjusting your filters or create a new user.') }}</EmptyDescription>
                                    </EmptyHeader>
                                </Empty>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <Pagination :pagination="users" />

            <UserForm v-model:open="showForm" :user="editingUser" :roles="props.roles" :genders="props.genders" :statuses="props.statuses" :year-levels="props.yearLevels" />

        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FilterCombobox from '@/Components/FilterCombobox.vue';
import UserForm from '@/Components/Forms/UserForm.vue';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select';
import { Empty, EmptyHeader, EmptyMedia, EmptyTitle, EmptyDescription } from '@/Components/ui/empty';
import { Users } from 'lucide-vue-next';
import { __ } from '@/Composables/useTranslate';

const props = defineProps({
    users:      Object,
    filters:    Object,
    roles:      Array,
    genders:    Array,
    statuses:   Array,
    yearLevels: Array,
});

const roleOptions   = computed(() => (props.roles   || []).map(r => ({ value: r, label: r.charAt(0).toUpperCase() + r.slice(1) })));
const yearOptions   = computed(() => props.yearLevels || []);
const statusOptions = computed(() => (props.statuses || []).map(s => ({ value: s, label: s.charAt(0).toUpperCase() + s.slice(1) })));

const search = ref(props.filters?.search || '');
const roleFilter = ref(props.filters?.role || '');
const yearFilter = ref(props.filters?.year_level || '');
const statusFilter = ref(props.filters?.status || '');

const applyFilters = () => {
    router.get('/admin/users', {
        filter: {
            search: search.value || undefined,
            role: roleFilter.value || undefined,
            year_level: yearFilter.value || undefined,
            status: statusFilter.value || undefined,
        },
    }, { preserveState: true });
};

const clearFilters = () => {
    search.value = '';
    roleFilter.value = '';
    yearFilter.value = '';
    statusFilter.value = '';
    router.get('/admin/users', {}, { preserveState: false });
};

// ── Form Dialog ──────────────────────────────────────────────────────────────
const showForm = ref(false);
const editingUser = ref(null);

const openCreate = () => {
    editingUser.value = null;
    showForm.value = true;
};

const openEdit = (user) => {
    editingUser.value = user;
    showForm.value = true;
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
