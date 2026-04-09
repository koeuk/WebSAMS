<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const form = useForm({ name: '', section: '', academic_year: '' });
const submit = () => { form.post('/admin/classes'); };
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center gap-4 mb-8">
                <Link href="/admin/classes" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                    Back
                </Link>
                <div class="h-5 w-px bg-slate-200"></div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Create Class</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Add a new class to the system</p>
                </div>
            </div>

            <div class="card p-8 max-w-xl">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</label>
                        <input v-model="form.name" type="text" required class="input-modern" placeholder="e.g. Grade 10-A" />
                        <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Section</label>
                        <input v-model="form.section" type="text" class="input-modern" placeholder="e.g. A" />
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Academic Year *</label>
                        <input v-model="form.academic_year" type="text" required class="input-modern" placeholder="e.g. 2025-2026" />
                        <p v-if="form.errors.academic_year" class="text-[12px] text-rose-500 mt-1">{{ form.errors.academic_year }}</p>
                    </div>
                    <div class="pt-2">
                        <button type="submit" :disabled="form.processing" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                            {{ form.processing ? 'Creating...' : 'Create Class' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
