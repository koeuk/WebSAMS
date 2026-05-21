<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('Notifications') }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ __('System notifications and alerts') }}</p>
            </div>

            <Card class="mb-6">
                <CardContent class="p-4">
                    <div class="flex flex-wrap gap-3 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <Label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Search') }}</Label>
                            <Input v-model="search" type="text" :placeholder="__('Search student...')" @keyup.enter="applyFilters" />
                        </div>
                        <div>
                            <Label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Type') }}</Label>
                            <FilterCombobox v-model="typeFilter" :options="typeOptions" :placeholder="__('All Types')" @update:model-value="applyFilters" />
                        </div>
                        <Button v-if="search || typeFilter" variant="outline" size="sm" @click="clearFilters" class="flex items-center gap-1.5 self-end">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            {{ __('Clear') }}
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <Card class="overflow-hidden">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>{{ __('Date') }}</TableHead>
                            <TableHead>{{ __('Student') }}</TableHead>
                            <TableHead>{{ __('Type') }}</TableHead>
                            <TableHead>{{ __('Title') }}</TableHead>
                            <TableHead>{{ __('Message') }}</TableHead>
                            <TableHead class="text-center">{{ __('Read') }}</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="n in notifications.data" :key="n.id">
                            <TableCell class="whitespace-nowrap font-medium text-slate-900">{{ formatDate(n.created_at) }}</TableCell>
                            <TableCell class="font-semibold text-slate-900">{{ n.user?.name }}</TableCell>
                            <TableCell>
                                <Badge variant="outline" :class="typeBadgeClass(n.type)">{{ n.type }}</Badge>
                            </TableCell>
                            <TableCell class="font-medium text-slate-900">{{ n.title }}</TableCell>
                            <TableCell class="max-w-[250px] truncate">{{ n.message }}</TableCell>
                            <TableCell class="text-center">
                                <Badge v-if="n.is_read" variant="outline" class="bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">{{ __('Yes') }}</Badge>
                                <Badge v-else variant="outline" class="bg-slate-100 text-slate-500 ring-1 ring-slate-200">{{ __('No') }}</Badge>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!notifications.data?.length">
                            <TableCell colspan="6" class="text-center py-12 text-slate-400">{{ __('No notifications.') }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <Pagination :pagination="notifications" />
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FilterCombobox from '@/Components/FilterCombobox.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Badge } from '@/Components/ui/badge';
import { Card, CardContent } from '@/Components/ui/card';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { __ } from '@/Composables/useTranslate';

const typeOptions = [
    { value: 'absence', label: __('Absence') },
    { value: 'late', label: __('Late') },
    { value: 'general', label: __('General') },
];

const props = defineProps({ notifications: Object, filters: Object });

const typeFilter = ref(props.filters?.type || '');
const search = ref(props.filters?.search || '');

const applyFilters = () => {
    router.get('/admin/notifications', { filter: { type: typeFilter.value || undefined, search: search.value || undefined } }, { preserveState: true });
};

const clearFilters = () => {
    typeFilter.value = '';
    search.value = '';
    router.get('/admin/notifications', {}, { preserveState: false });
};

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-';

const typeBadgeClass = (type) => ({
    'bg-rose-50 text-rose-700 ring-1 ring-rose-200': type === 'absence',
    'bg-amber-50 text-amber-700 ring-1 ring-amber-200': type === 'late',
    'bg-slate-100 text-slate-600 ring-1 ring-slate-200': type === 'general',
});
</script>
