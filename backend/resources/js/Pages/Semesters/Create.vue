<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const form = useForm({ name: '', academic_year: '', start_date: '', end_date: '' });
const submit = () => { form.post('/admin/semesters'); };
</script>

<template>
    <AdminLayout>
        <div class="flex items-center gap-4 mb-6">
            <Link href="/admin/semesters" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back</Link>
            <h2 class="text-2xl font-bold text-gray-900">Create Semester</h2>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 max-w-xl">
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input v-model="form.name" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g. Semester 1" />
                    <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
                    <input v-model="form.academic_year" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g. 2025-2026" />
                    <p v-if="form.errors.academic_year" class="text-sm text-red-600 mt-1">{{ form.errors.academic_year }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input v-model="form.start_date" type="date" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                        <p v-if="form.errors.start_date" class="text-sm text-red-600 mt-1">{{ form.errors.start_date }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input v-model="form.end_date" type="date" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                        <p v-if="form.errors.end_date" class="text-sm text-red-600 mt-1">{{ form.errors.end_date }}</p>
                    </div>
                </div>
                <button type="submit" :disabled="form.processing" class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark disabled:opacity-50">
                    {{ form.processing ? 'Creating...' : 'Create Semester' }}
                </button>
            </form>
        </div>
    </AdminLayout>
</template>
