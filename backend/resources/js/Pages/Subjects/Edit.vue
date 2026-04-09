<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ subject: Object, courses: Array });

const form = useForm({
    course_id: props.subject.course_id,
    name: props.subject.name,
    code: props.subject.code,
});

const submit = () => { form.put(`/admin/subjects/${props.subject.id}`); };
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center gap-4 mb-8">
                <Link href="/admin/subjects" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                    Back
                </Link>
                <div class="h-5 w-px bg-slate-200"></div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Subject</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Update {{ subject.name }}</p>
                </div>
            </div>

            <div class="card p-8 max-w-xl">
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Course *</label>
                        <select v-model="form.course_id" required class="select-modern w-full">
                            <option v-for="c in courses" :key="c.id" :value="c.id">{{ c.name }} ({{ c.code }})</option>
                        </select>
                        <p v-if="form.errors.course_id" class="text-[12px] text-rose-500 mt-1">{{ form.errors.course_id }}</p>
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</label>
                        <input v-model="form.name" type="text" required class="input-modern" />
                        <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Code *</label>
                        <input v-model="form.code" type="text" required class="input-modern" />
                        <p v-if="form.errors.code" class="text-[12px] text-rose-500 mt-1">{{ form.errors.code }}</p>
                    </div>
                    <div class="pt-2">
                        <button type="submit" :disabled="form.processing" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                            {{ form.processing ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
