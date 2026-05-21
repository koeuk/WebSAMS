<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('Subjects') }}</h2>
                    <p class="text-sm text-slate-500 mt-1">{{ __('Manage subjects across courses') }}</p>
                </div>
                <Button @click="openCreate" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    {{ __('Create Subject') }}
                </Button>
            </div>


            <Card class="mb-6">
                <CardContent class="p-4">
                    <div class="flex flex-wrap gap-3 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Search') }}</label>
                            <Input v-model="search" type="text" :placeholder="__('Search subjects...')" @keyup.enter="applyFilters" />
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">{{ __('Course') }}</label>
                            <FilterCombobox v-model="courseFilter" :options="courseOptions" :placeholder="__('All Courses')" @update:model-value="applyFilters" />
                        </div>
                        <Button v-if="search || courseFilter" variant="outline" size="sm" @click="clearFilters" class="flex items-center gap-1.5 self-end">
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
                            <TableHead>{{ __('Name') }}</TableHead>
                            <TableHead>{{ __('Code') }}</TableHead>
                            <TableHead>{{ __('Course') }}</TableHead>
                            <TableHead class="text-right">{{ __('Actions') }}</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="subject in subjects.data" :key="subject.id">
                            <TableCell class="font-semibold text-slate-900">{{ subject.name }}</TableCell>
                            <TableCell>
                                <Badge variant="secondary" class="font-mono">{{ subject.code }}</Badge>
                            </TableCell>
                            <TableCell>{{ subject.course?.name }}</TableCell>
                            <TableCell class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <Button variant="ghost" size="sm" class="text-blue-600 hover:text-blue-700" @click="openEdit(subject)">{{ __('Edit') }}</Button>
                                    <Button variant="ghost" size="sm" @click="router.visit(`/admin/subjects/${subject.id}/delete`, { preserveState: true, preserveScroll: true })" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50">{{ __('Delete') }}</Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!subjects.data?.length">
                            <TableCell colspan="4" class="p-0">
                                <Empty class="border-0 rounded-none">
                                    <EmptyHeader>
                                        <EmptyMedia variant="icon"><Book class="w-6 h-6" /></EmptyMedia>
                                        <EmptyTitle>{{ __('No subjects found') }}</EmptyTitle>
                                        <EmptyDescription>{{ __('Try adjusting your filters or create a new subject.') }}</EmptyDescription>
                                    </EmptyHeader>
                                </Empty>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <Pagination :pagination="subjects" />
        </div>

        <SubjectForm v-model:open="showForm" :subject="editingSubject" :courses="courses" />
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FilterCombobox from '@/Components/FilterCombobox.vue';
import SubjectForm from '@/Components/Forms/SubjectForm.vue';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Card, CardContent } from '@/Components/ui/card';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { Empty, EmptyHeader, EmptyMedia, EmptyTitle, EmptyDescription } from '@/Components/ui/empty';
import { Book } from 'lucide-vue-next';
import { __ } from '@/Composables/useTranslate';

const props = defineProps({ subjects: Object, courses: Array, filters: Object });
const search = ref(props.filters?.search || '');
const courseFilter = ref(props.filters?.course_id || '');
const courseOptions = computed(() => (props.courses || []).map(c => ({ value: c.id, label: c.name })));

const applyFilters = () => {
    router.get('/admin/subjects', {
        filter: {
            search: search.value || undefined,
            course_id: courseFilter.value || undefined,
        },
    }, { preserveState: true });
};

const clearFilters = () => {
    search.value = '';
    courseFilter.value = '';
    router.get('/admin/subjects', {}, { preserveState: false });
};

// Create / Edit
const showForm = ref(false);
const editingSubject = ref(null);
const openCreate = () => { editingSubject.value = null; showForm.value = true; };
const openEdit = (subject) => { editingSubject.value = subject; showForm.value = true; };

</script>
