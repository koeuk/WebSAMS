<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const form = useForm({ name: '', section: '', academic_year: '' });
const submit = () => { form.post('/admin/classes'); };
</script>

<template>
    <AdminLayout>
        <div class="flex items-center gap-4 mb-6">
            <Link href="/admin/classes" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back</Link>
            <h2 class="text-2xl font-bold text-gray-900">Create Class</h2>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 max-w-xl">
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input v-model="form.name" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g. Grade 10-A" />
                    <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                    <input v-model="form.section" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g. A" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Academic Year</label>
                    <input v-model="form.academic_year" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g. 2025-2026" />
                    <p v-if="form.errors.academic_year" class="text-sm text-red-600 mt-1">{{ form.errors.academic_year }}</p>
                </div>
                <button type="submit" :disabled="form.processing" class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark disabled:opacity-50">
                    {{ form.processing ? 'Creating...' : 'Create Class' }}
                </button>
            </form>
        </div>
    </AdminLayout>
</template>
