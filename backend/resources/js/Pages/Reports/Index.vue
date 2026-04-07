<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ classes: Array, subjects: Array });

const form = useForm({
    class_id: '',
    subject_id: '',
    date_from: '',
    date_to: '',
});

const submit = () => {
    form.get('/admin/reports/generate');
};
</script>

<template>
    <AdminLayout>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Reports</h2>

        <div class="bg-white rounded-lg border border-gray-200 p-6 max-w-xl">
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Class (optional)</label>
                    <select v-model="form.class_id" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="">All Classes</option>
                        <option v-for="c in classes" :key="c.id" :value="c.id">{{ c.name }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject (optional)</label>
                    <select v-model="form.subject_id" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                        <option value="">All Subjects</option>
                        <option v-for="s in subjects" :key="s.id" :value="s.id">{{ s.name }}</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                        <input v-model="form.date_from" type="date" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                        <p v-if="form.errors.date_from" class="text-sm text-red-600 mt-1">{{ form.errors.date_from }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                        <input v-model="form.date_to" type="date" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                        <p v-if="form.errors.date_to" class="text-sm text-red-600 mt-1">{{ form.errors.date_to }}</p>
                    </div>
                </div>
                <button type="submit" :disabled="form.processing" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-md hover:bg-gray-800 disabled:opacity-50">
                    {{ form.processing ? 'Generating...' : 'Generate Report' }}
                </button>
            </form>
        </div>
    </AdminLayout>
</template>
