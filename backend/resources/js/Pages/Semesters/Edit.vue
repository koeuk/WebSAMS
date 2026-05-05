<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DatePicker from '@/Components/DatePicker.vue';

const props = defineProps({ semester: Object });

const form = useForm({
    name: props.semester.name,
    academic_year: props.semester.academic_year,
    start_date: props.semester.start_date?.split('T')[0] || '',
    end_date: props.semester.end_date?.split('T')[0] || '',
});

const submit = () => { form.put(`/admin/semesters/${props.semester.id}`); };
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center gap-4 mb-8">
                <Link href="/admin/semesters" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                    Back
                </Link>
                <div class="h-5 w-px bg-slate-200"></div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Semester</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Update semester details</p>
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
                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Academic Year *</label>
                        <input v-model="form.academic_year" type="text" required class="input-modern" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Start Date *</label>
                            <DatePicker v-model="form.start_date" placeholder="Pick start date" />
                            <p v-if="form.errors.start_date" class="text-[12px] text-rose-500 mt-1">{{ form.errors.start_date }}</p>
                        </div>
                        <div>
                            <label class="block text-[13px] font-medium text-slate-600 mb-1.5">End Date *</label>
                            <DatePicker v-model="form.end_date" placeholder="Pick end date" />
                            <p v-if="form.errors.end_date" class="text-[12px] text-rose-500 mt-1">{{ form.errors.end_date }}</p>
                        </div>
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
