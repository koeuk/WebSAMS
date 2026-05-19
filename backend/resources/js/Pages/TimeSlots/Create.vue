<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Card, CardContent } from '@/Components/ui/card';
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select';

const form = useForm({ name: '', start_time: '', end_time: '', type: 'morning' });
const submit = () => { form.post('/admin/time-slots'); };
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center gap-4 mb-8">
                <Link href="/admin/time-slots" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                    Back
                </Link>
                <div class="h-5 w-px bg-slate-200"></div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Create Time Slot</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Add a new time period</p>
                </div>
            </div>

            <Card class="max-w-xl">
                <CardContent class="p-8">
                    <form @submit.prevent="submit" class="space-y-5">
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                            <Input v-model="form.name" type="text" required placeholder="e.g. Morning 1" />
                            <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Start Time *</Label>
                                <Input v-model="form.start_time" type="time" required />
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">End Time *</Label>
                                <Input v-model="form.end_time" type="time" required />
                            </div>
                        </div>
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Type</Label>
                            <Select v-model="form.type">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select type..." />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="morning">Morning</SelectItem>
                                    <SelectItem value="afternoon">Afternoon</SelectItem>
                                    <SelectItem value="evening">Evening</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="pt-2">
                            <Button type="submit" :disabled="form.processing" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                                {{ form.processing ? 'Creating...' : 'Create' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>
