<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ courses: Array });

const form = useForm({
    course_id: '',
    name: '',
    code: '',
});

const submit = () => { form.post('/admin/subjects'); };
</script>

<template>
    <AdminLayout>
        <div class="flex items-center gap-4 mb-6">
            <Link href="/admin/subjects" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back</Link>
            <h2 class="text-2xl font-bold text-gray-900">Create Subject</h2>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 max-w-xl">
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                    <select v-model="form.course_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="" disabled>Select a course</option>
                        <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.name }} ({{ c.code }})</option>
                    </select>
                    <p v-if="form.errors.course_id" class="text-sm text-red-600 mt-1">{{ form.errors.course_id }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input v-model="form.name" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                    <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                    <input v-model="form.code" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g. CS101" />
                    <p v-if="form.errors.code" class="text-sm text-red-600 mt-1">{{ form.errors.code }}</p>
                </div>
                <button type="submit" :disabled="form.processing" class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark disabled:opacity-50">
                    {{ form.processing ? 'Creating...' : 'Create Subject' }}
                </button>
            </form>
        </div>
    </AdminLayout>
</template>
