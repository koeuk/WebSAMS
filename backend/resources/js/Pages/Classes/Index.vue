<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Classes</h2>
                    <p class="text-sm text-slate-500 mt-1">Manage classes and sections</p>
                </div>
                <Button @click="openCreate" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Create Class
                </Button>
            </div>

            <FlashMessage />

            <Card class="mb-6">
                <CardContent class="p-4">
                    <div class="flex flex-wrap gap-3 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Search</label>
                            <Input v-model="search" type="text" placeholder="Search classes..." @keyup.enter="applyFilters" />
                        </div>
                        <Button v-if="search" variant="outline" @click="clearFilters" class="flex items-center gap-1.5 text-[12px]">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            Clear
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <Card class="overflow-hidden">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Section</TableHead>
                            <TableHead>Academic Year</TableHead>
                            <TableHead>Subjects</TableHead>
                            <TableHead>Students</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="c in classes.data" :key="c.id">
                            <TableCell class="font-semibold text-slate-900">
                                <Link :href="`/admin/classes/${c.id}`" class="hover:text-beltei transition-colors">{{ c.name }}</Link>
                            </TableCell>
                            <TableCell>{{ c.section || '-' }}</TableCell>
                            <TableCell>
                                <Badge variant="secondary">{{ c.academic_year }}</Badge>
                            </TableCell>
                            <TableCell class="font-mono text-[13px]">{{ c.subjects_count }}</TableCell>
                            <TableCell class="font-mono text-[13px]">{{ c.students_count }}</TableCell>
                            <TableCell class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <Button variant="ghost" size="sm" as-child>
                                        <Link :href="`/admin/classes/${c.id}`">View</Link>
                                    </Button>
                                    <Button variant="ghost" size="sm" class="text-blue-600 hover:text-blue-700 hover:bg-blue-50" @click="openEdit(c)">Edit</Button>
                                    <Button variant="ghost" size="sm" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50" @click="router.visit(`/admin/classes/${c.id}/delete`, { preserveState: true, preserveScroll: true })">Delete</Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!classes.data?.length">
                            <TableCell colspan="6" class="p-0">
                                <Empty class="border-0 rounded-none">
                                    <EmptyHeader>
                                        <EmptyMedia variant="icon"><School class="w-6 h-6" /></EmptyMedia>
                                        <EmptyTitle>No classes found</EmptyTitle>
                                        <EmptyDescription>Try adjusting your search or create a new class.</EmptyDescription>
                                    </EmptyHeader>
                                </Empty>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <Pagination :pagination="classes" />

            <ClassForm v-model:open="showForm" :school-class="editingClass" />
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import ClassForm from '@/Components/Forms/ClassForm.vue';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';
import { Card, CardContent } from '@/Components/ui/card';
import { Empty, EmptyHeader, EmptyMedia, EmptyTitle, EmptyDescription } from '@/Components/ui/empty';
import { School } from 'lucide-vue-next';

const props = defineProps({ classes: Object, filters: Object });
const search = ref(props.filters?.search || '');

const applyFilters = () => {
    router.get('/admin/classes', { filter: { search: search.value || undefined } }, { preserveState: true });
};

const clearFilters = () => {
    search.value = '';
    router.get('/admin/classes', {}, { preserveState: false });
};

// ── Form (create & edit) ─────────────────────────────────
const showForm = ref(false);
const editingClass = ref(null);

const openCreate = () => { editingClass.value = null; showForm.value = true; };
const openEdit = (c) => { editingClass.value = c; showForm.value = true; };

</script>
