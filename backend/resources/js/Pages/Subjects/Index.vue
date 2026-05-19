<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';
import FilterCombobox from '@/Components/FilterCombobox.vue';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Card, CardContent } from '@/Components/ui/card';
import { Table, TableHeader, TableBody, TableRow, TableHead, TableCell } from '@/Components/ui/table';

const props = defineProps({ subjects: Object, courses: Array, filters: Object });
const search = ref(props.filters?.search || '');
const courseFilter = ref(props.filters?.course_id || '');
const courseOptions = computed(() => (props.courses || []).map(c => ({ value: c.id, label: c.name })));

const applyFilters = () => {
    router.get('/admin/subjects', {
        search: search.value || undefined,
        course_id: courseFilter.value || undefined,
    }, { preserveState: true });
};

const clearFilters = () => {
    search.value = '';
    courseFilter.value = '';
    router.get('/admin/subjects', {}, { preserveState: false });
};

const showDeleteModal = ref(false);
const subjectToDelete = ref(null);
const confirmDelete = (subject) => { subjectToDelete.value = subject; showDeleteModal.value = true; };
const deleteSubject = () => {
    router.delete(`/admin/subjects/${subjectToDelete.value.id}`, {
        onFinish: () => { showDeleteModal.value = false; subjectToDelete.value = null; },
    });
};
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Subjects</h2>
                    <p class="text-sm text-slate-500 mt-1">Manage subjects across courses</p>
                </div>
                <Button as-child>
                    <Link href="/admin/subjects/create" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                        Create Subject
                    </Link>
                </Button>
            </div>

            <FlashMessage />

            <Card class="mb-6">
                <CardContent class="p-4">
                    <div class="flex flex-wrap gap-3 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Search</label>
                            <Input v-model="search" type="text" placeholder="Search subjects..." @keyup.enter="applyFilters" />
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Course</label>
                            <FilterCombobox v-model="courseFilter" :options="courseOptions" placeholder="All Courses" @update:model-value="applyFilters" />
                        </div>
                        <Button v-if="search || courseFilter" variant="outline" size="sm" @click="clearFilters" class="flex items-center gap-1.5 self-end">
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
                            <TableHead>Code</TableHead>
                            <TableHead>Course</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
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
                                    <Button variant="ghost" size="sm" as-child>
                                        <Link :href="`/admin/subjects/${subject.id}/edit`" class="text-blue-600 hover:text-blue-700">Edit</Link>
                                    </Button>
                                    <Button variant="ghost" size="sm" @click="confirmDelete(subject)" class="text-rose-500 hover:text-rose-700 hover:bg-rose-50">Delete</Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="!subjects.data?.length">
                            <TableCell colspan="4" class="text-center py-12 text-slate-400">No subjects found.</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </Card>

            <Pagination :links="subjects.links" />
            <Modal :show="showDeleteModal" title="Delete Subject" :message="`Delete ${subjectToDelete?.name}?`" @confirm="deleteSubject" @cancel="showDeleteModal = false" />
        </div>
    </AdminLayout>
</template>
