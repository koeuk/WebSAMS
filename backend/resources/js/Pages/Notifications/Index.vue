<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({ notifications: Object, filters: Object });

const typeFilter = ref(props.filters?.type || '');
const search = ref(props.filters?.search || '');

const applyFilters = () => {
    router.get('/admin/notifications', { type: typeFilter.value || undefined, search: search.value || undefined }, { preserveState: true });
};

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';

const typeBadgeClass = (type) => ({
    'bg-rose-50 text-rose-700 ring-1 ring-rose-200': type === 'absence',
    'bg-amber-50 text-amber-700 ring-1 ring-amber-200': type === 'late',
    'bg-slate-100 text-slate-600 ring-1 ring-slate-200': type === 'general',
});
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Notifications</h2>
                <p class="text-sm text-slate-500 mt-1">System notifications and alerts</p>
            </div>

            <div class="card p-4 mb-6">
                <div class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Search</label>
                        <input v-model="search" type="text" placeholder="Search student..." class="input-modern" @keyup.enter="applyFilters" />
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Type</label>
                        <select v-model="typeFilter" class="select-modern" @change="applyFilters">
                            <option value="">All Types</option>
                            <option value="absence">Absence</option>
                            <option value="late">Late</option>
                            <option value="general">General</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card overflow-hidden">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th class="text-left">Date</th>
                            <th class="text-left">Student</th>
                            <th class="text-left">Type</th>
                            <th class="text-left">Title</th>
                            <th class="text-left">Message</th>
                            <th class="text-center">Read</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="n in notifications.data" :key="n.id">
                            <td class="whitespace-nowrap font-medium text-slate-900">{{ formatDate(n.created_at) }}</td>
                            <td class="font-semibold text-slate-900">{{ n.user?.name }}</td>
                            <td><span class="badge" :class="typeBadgeClass(n.type)">{{ n.type }}</span></td>
                            <td class="font-medium text-slate-900">{{ n.title }}</td>
                            <td class="max-w-[250px] truncate">{{ n.message }}</td>
                            <td class="text-center">
                                <span v-if="n.is_read" class="badge bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">Yes</span>
                                <span v-else class="badge bg-slate-100 text-slate-500 ring-1 ring-slate-200">No</span>
                            </td>
                        </tr>
                        <tr v-if="!notifications.data?.length">
                            <td colspan="6" class="!text-center !py-12 text-slate-400">No notifications.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <Pagination :links="notifications.links" />
        </div>
    </AdminLayout>
</template>
