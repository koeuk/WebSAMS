<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import DatePicker from '@/Components/DatePicker.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Card, CardContent } from '@/Components/ui/card';

const form = useForm({ name: '', academic_year: '', start_date: '', end_date: '' });
const submit = () => { form.post('/admin/semesters'); };
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
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Create Semester</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Add a new academic semester</p>
                </div>
            </div>

            <Card class="max-w-xl">
                <CardContent class="p-8">
                    <form @submit.prevent="submit" class="space-y-5">
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                            <Input v-model="form.name" type="text" required placeholder="e.g. Semester 1" />
                            <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Academic Year *</Label>
                            <Input v-model="form.academic_year" type="text" required placeholder="e.g. 2025-2026" />
                            <p v-if="form.errors.academic_year" class="text-[12px] text-rose-500 mt-1">{{ form.errors.academic_year }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Start Date *</Label>
                                <DatePicker v-model="form.start_date" placeholder="Pick start date" />
                                <p v-if="form.errors.start_date" class="text-[12px] text-rose-500 mt-1">{{ form.errors.start_date }}</p>
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">End Date *</Label>
                                <DatePicker v-model="form.end_date" placeholder="Pick end date" />
                                <p v-if="form.errors.end_date" class="text-[12px] text-rose-500 mt-1">{{ form.errors.end_date }}</p>
                            </div>
                        </div>
                        <div class="pt-2">
                            <Button type="submit" :disabled="form.processing" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                                {{ form.processing ? 'Creating...' : 'Create Semester' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>
