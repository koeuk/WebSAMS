<template>
    <AdminLayout>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">{{ __('Semesters') }}</h2>
            <Button @click="openCreate" class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                {{ __('Create Semester') }}
            </Button>
        </div>


        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <Table>
                <TableHeader>
                    <TableRow class="bg-gray-50">
                        <TableHead>{{ __('Name') }}</TableHead>
                        <TableHead>{{ __('Academic Year') }}</TableHead>
                        <TableHead>{{ __('Start Date') }}</TableHead>
                        <TableHead>{{ __('End Date') }}</TableHead>
                        <TableHead class="text-right">{{ __('Actions') }}</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="s in semesters.data" :key="s.id">
                        <TableCell class="font-medium text-gray-900">{{ s.name }}</TableCell>
                        <TableCell class="text-gray-600">{{ s.academic_year }}</TableCell>
                        <TableCell class="text-gray-600">{{ formatDate(s.start_date) }}</TableCell>
                        <TableCell class="text-gray-600">{{ formatDate(s.end_date) }}</TableCell>
                        <TableCell class="text-right">
                            <Button variant="ghost" size="sm" class="text-blue-600 hover:text-blue-800 mr-1" @click="openEdit(s)">{{ __('Edit') }}</Button>
                            <Button variant="ghost" size="sm" class="text-red-600 hover:text-red-800" @click="router.visit(`/admin/semesters/${s.id}/delete`, { preserveState: true, preserveScroll: true })">{{ __('Delete') }}</Button>
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="!semesters.data?.length">
                        <TableCell colspan="5" class="p-0">
                            <Empty class="border-0 rounded-none">
                                <EmptyHeader>
                                    <EmptyMedia variant="icon"><CalendarDays class="w-6 h-6" /></EmptyMedia>
                                    <EmptyTitle>{{ __('No semesters found') }}</EmptyTitle>
                                    <EmptyDescription>{{ __('Create a semester to get started.') }}</EmptyDescription>
                                </EmptyHeader>
                            </Empty>
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <Pagination :pagination="semesters" />

        <!-- Create / Edit Form -->
        <SemesterForm v-model:open="showForm" :semester="editingSemester" />
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import SemesterForm from '@/Components/Forms/SemesterForm.vue';
import { Button } from '@/Components/ui/button';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { Empty, EmptyHeader, EmptyMedia, EmptyTitle, EmptyDescription } from '@/Components/ui/empty';
import { CalendarDays } from 'lucide-vue-next';
import { __ } from '@/Composables/useTranslate';

const props = defineProps({ semesters: Object });

// ── Create / Edit ─────────────────────────────────────────────────────────────
const showForm = ref(false);
const editingSemester = ref(null);
const openCreate = () => { editingSemester.value = null; showForm.value = true; };
const openEdit = (s) => { editingSemester.value = s; showForm.value = true; };

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '-';
</script>
