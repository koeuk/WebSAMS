<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({ notifications: Object, filters: Object });

const typeFilter = ref(props.filters?.type || '');
const search = ref(props.filters?.search || '');

const applyFilters = () => {
    router.get('/admin/notifications', {
        type: typeFilter.value || undefined,
        search: search.value || undefined,
    }, { preserveState: true });
};

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';

const typeBadge = (type) => ({
    'bg-red-100 text-red-800': type === 'absence',
    'bg-yellow-100 text-yellow-800': type === 'late',
    'bg-gray-100 text-gray-800': type === 'general',
});
</script>

<template>
    <AdminLayout>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Notifications</h2>

        <div class="flex gap-3 mb-4">
            <input v-model="search" type="text" placeholder="Search student..." class="px-3 py-2 border border-gray-300 rounded-md text-sm w-64" @keyup.enter="applyFilters" />
            <select v-model="typeFilter" class="px-3 py-2 border border-gray-300 rounded-md text-sm" @change="applyFilters">
                <option value="">All Types</option>
                <option value="absence">Absence</option>
                <option value="late">Late</option>
                <option value="general">General</option>
            </select>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Student</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Message</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Read</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="n in notifications.data" :key="n.id" class="border-b border-gray-100">
                        <td class="px-6 py-3 text-sm text-gray-900 whitespace-nowrap">{{ formatDate(n.created_at) }}</td>
                        <td class="px-6 py-3 text-sm text-gray-900">{{ n.user?.name }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full" :class="typeBadge(n.type)">{{ n.type }}</span>
                        </td>
                        <td class="px-6 py-3 text-sm text-gray-900">{{ n.title }}</td>
                        <td class="px-6 py-3 text-sm text-gray-600">{{ n.message }}</td>
                        <td class="px-6 py-3 text-center">
                            <span :class="n.is_read ? 'text-green-600' : 'text-gray-400'">{{ n.is_read ? 'Yes' : 'No' }}</span>
                        </td>
                    </tr>
                    <tr v-if="!notifications.data?.length">
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">No notifications.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination :links="notifications.links" />
    </AdminLayout>
</template>
