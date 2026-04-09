<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const form = useForm({ name: '', code: '', description: '' });
const submit = () => { form.post('/admin/courses'); };
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center gap-4 mb-8">
                <Link href="/admin/courses" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                    Back
                </Link>
                <div class="h-5 w-px bg-slate-200"></div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Create Course</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Add a new course to the system</p>
                </div>
            </div>

            <div class="card p-8 max-w-xl">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</label>
                        <input v-model="form.name" type="text" required class="input-modern" />
                        <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Code *</label>
                        <input v-model="form.code" type="text" required class="input-modern" placeholder="e.g. CS" />
                        <p v-if="form.errors.code" class="text-[12px] text-rose-500 mt-1">{{ form.errors.code }}</p>
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Description</label>
                        <textarea v-model="form.description" rows="3" class="input-modern" />
                    </div>
                    <div class="pt-2">
                        <button type="submit" :disabled="form.processing" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                            {{ form.processing ? 'Creating...' : 'Create Course' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
