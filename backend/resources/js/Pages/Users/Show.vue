<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <Link href="/admin/users" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                        {{ __('Back') }}
                    </Link>
                    <div class="h-5 w-px bg-slate-200"></div>
                    <div class="flex items-center gap-3">
                        <div v-if="user.profile_photo" class="h-12 w-12 rounded-xl overflow-hidden ring-2 ring-slate-100">
                            <img :src="`/storage/${user.profile_photo}`" class="h-full w-full object-cover" />
                        </div>
                        <div v-else class="h-12 w-12 rounded-xl bg-gradient-to-br from-beltei to-beltei-dark flex items-center justify-center text-white text-lg font-bold ring-2 ring-slate-100">
                            {{ user.name?.charAt(0) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ user.name }}</h2>
                            <div class="flex items-center gap-2 mt-1">
                                <Badge :variant="roleBadgeVariant(user.role)" class="capitalize">{{ user.role }}</Badge>
                                <Badge :variant="statusBadgeVariant(user.status)" class="capitalize">{{ user.status || 'active' }}</Badge>
                            </div>
                        </div>
                    </div>
                </div>
                <Button @click="showEdit = true" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    {{ __('Edit') }}
                </Button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <Card>
                    <CardContent class="p-6">
                        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-5 flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                            {{ __('Basic Info') }}
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                <p class="text-[13px] text-slate-500">{{ __('ID Number') }}</p>
                                <p class="text-[13px] font-semibold text-slate-900 font-mono">{{ user.id_number || '-' }}</p>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                <p class="text-[13px] text-slate-500">{{ __('Name') }}</p>
                                <p class="text-[13px] font-semibold text-slate-900">{{ user.name }}</p>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                <p class="text-[13px] text-slate-500">{{ __('Email') }}</p>
                                <p class="text-[13px] font-semibold text-slate-900">{{ user.email }}</p>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                <p class="text-[13px] text-slate-500">{{ __('Gender') }}</p>
                                <p class="text-[13px] font-semibold text-slate-900 capitalize">{{ user.gender || '-' }}</p>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                <p class="text-[13px] text-slate-500">{{ __('Date of Birth') }}</p>
                                <p class="text-[13px] font-semibold text-slate-900">{{ formatDate(user.date_of_birth) }}</p>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                <p class="text-[13px] text-slate-500">{{ __('Phone') }}</p>
                                <p class="text-[13px] font-semibold text-slate-900">{{ user.phone || '-' }}</p>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <p class="text-[13px] text-slate-500">{{ __('Address') }}</p>
                                <p class="text-[13px] font-semibold text-slate-900 text-right max-w-[200px]">{{ stripHtml(user.address) || '-' }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Role-specific Info -->
                <Card>
                    <CardContent class="p-6">
                        <template v-if="user.role === 'student'">
                            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-5 flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                                {{ __('Student Info') }}
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                    <p class="text-[13px] text-slate-500">{{ __('Year Level') }}</p>
                                    <p class="text-[13px] font-semibold text-slate-900">{{ user.year_level ? __('Year {n}', { n: user.year_level }) : '-' }}</p>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                    <p class="text-[13px] text-slate-500">{{ __('Enrollment Date') }}</p>
                                    <p class="text-[13px] font-semibold text-slate-900">{{ formatDate(user.enrollment_date) }}</p>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                    <p class="text-[13px] text-slate-500">{{ __('Guardian Name') }}</p>
                                    <p class="text-[13px] font-semibold text-slate-900">{{ user.guardian_name || '-' }}</p>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <p class="text-[13px] text-slate-500">{{ __('Guardian Phone') }}</p>
                                    <p class="text-[13px] font-semibold text-slate-900">{{ user.guardian_phone || '-' }}</p>
                                </div>
                            </div>
                        </template>

                        <template v-else-if="user.role === 'teacher'">
                            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-5 flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-400"></div>
                                {{ __('Teacher Info') }}
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                    <p class="text-[13px] text-slate-500">{{ __('Department') }}</p>
                                    <p class="text-[13px] font-semibold text-slate-900">{{ user.department || '-' }}</p>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                    <p class="text-[13px] text-slate-500">{{ __('Qualification') }}</p>
                                    <p class="text-[13px] font-semibold text-slate-900">{{ user.qualification || '-' }}</p>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <p class="text-[13px] text-slate-500">{{ __('Hire Date') }}</p>
                                    <p class="text-[13px] font-semibold text-slate-900">{{ formatDate(user.hire_date) }}</p>
                                </div>
                            </div>
                        </template>

                        <template v-else>
                            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-5 flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-slate-400"></div>
                                {{ __('Account') }}
                            </h3>
                            <p class="text-[13px] text-slate-500">{{ __('Administrator account') }}</p>
                        </template>
                    </CardContent>
                </Card>
            </div>

            <UserForm v-model:open="showEdit" :user="user" />
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import UserForm from '@/Components/Forms/UserForm.vue';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import { Card, CardContent } from '@/Components/ui/card';
import { __ } from '@/Composables/useTranslate';
import { stripHtml } from '@/lib/utils';

const props = defineProps({ user: Object });

const showEdit = ref(false);

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : '-';

const statusBadgeVariant = (status) => {
    if (status === 'active') return 'default';
    if (status === 'suspended') return 'destructive';
    return 'secondary';
};

const roleBadgeVariant = (role) => {
    if (role === 'admin') return 'default';
    if (role === 'teacher') return 'secondary';
    return 'outline';
};
</script>
