<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ user: Object });

const formatDate = (d) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : '-';

const statusBadgeClass = (status) => ({
    'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200': status === 'active',
    'bg-slate-100 text-slate-600 ring-1 ring-slate-200': status === 'inactive',
    'bg-sky-50 text-sky-700 ring-1 ring-sky-200': status === 'graduated',
    'bg-rose-50 text-rose-700 ring-1 ring-rose-200': status === 'suspended',
});

const roleBadgeClass = (role) => ({
    'bg-violet-50 text-violet-700 ring-1 ring-violet-200': role === 'admin',
    'bg-sky-50 text-sky-700 ring-1 ring-sky-200': role === 'teacher',
    'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200': role === 'student',
});
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <Link href="/admin/users" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
                        Back
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
                                <span class="badge" :class="roleBadgeClass(user.role)">{{ user.role }}</span>
                                <span class="badge" :class="statusBadgeClass(user.status)">{{ user.status || 'active' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <Link :href="`/admin/users/${user.id}/edit`" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit
                </Link>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 stagger-children">
                <!-- Basic Info -->
                <div class="card p-6 animate-fade-in-up">
                    <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-5 flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                        Basic Info
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <p class="text-[13px] text-slate-500">ID Number</p>
                            <p class="text-[13px] font-semibold text-slate-900 font-mono">{{ user.id_number || '-' }}</p>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <p class="text-[13px] text-slate-500">Name</p>
                            <p class="text-[13px] font-semibold text-slate-900">{{ user.name }}</p>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <p class="text-[13px] text-slate-500">Email</p>
                            <p class="text-[13px] font-semibold text-slate-900">{{ user.email }}</p>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <p class="text-[13px] text-slate-500">Gender</p>
                            <p class="text-[13px] font-semibold text-slate-900 capitalize">{{ user.gender || '-' }}</p>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <p class="text-[13px] text-slate-500">Date of Birth</p>
                            <p class="text-[13px] font-semibold text-slate-900">{{ formatDate(user.date_of_birth) }}</p>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <p class="text-[13px] text-slate-500">Phone</p>
                            <p class="text-[13px] font-semibold text-slate-900">{{ user.phone || '-' }}</p>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <p class="text-[13px] text-slate-500">Address</p>
                            <p class="text-[13px] font-semibold text-slate-900 text-right max-w-[200px]">{{ user.address || '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Role-specific Info -->
                <div class="card p-6 animate-fade-in-up">
                    <!-- Student Info -->
                    <template v-if="user.role === 'student'">
                        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-5 flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                            Student Info
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                <p class="text-[13px] text-slate-500">Year Level</p>
                                <p class="text-[13px] font-semibold text-slate-900">{{ user.year_level ? 'Year ' + user.year_level : '-' }}</p>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                <p class="text-[13px] text-slate-500">Enrollment Date</p>
                                <p class="text-[13px] font-semibold text-slate-900">{{ formatDate(user.enrollment_date) }}</p>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                <p class="text-[13px] text-slate-500">Guardian Name</p>
                                <p class="text-[13px] font-semibold text-slate-900">{{ user.guardian_name || '-' }}</p>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <p class="text-[13px] text-slate-500">Guardian Phone</p>
                                <p class="text-[13px] font-semibold text-slate-900">{{ user.guardian_phone || '-' }}</p>
                            </div>
                        </div>
                    </template>

                    <!-- Teacher Info -->
                    <template v-if="user.role === 'teacher'">
                        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-5 flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-sky-400"></div>
                            Teacher Info
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                <p class="text-[13px] text-slate-500">Department</p>
                                <p class="text-[13px] font-semibold text-slate-900">{{ user.department || '-' }}</p>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                <p class="text-[13px] text-slate-500">Qualification</p>
                                <p class="text-[13px] font-semibold text-slate-900">{{ user.qualification || '-' }}</p>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <p class="text-[13px] text-slate-500">Hire Date</p>
                                <p class="text-[13px] font-semibold text-slate-900">{{ formatDate(user.hire_date) }}</p>
                            </div>
                        </div>
                    </template>

                    <!-- Admin Info -->
                    <template v-if="user.role === 'admin'">
                        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-5 flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-violet-400"></div>
                            Admin Info
                        </h3>
                        <div class="flex items-center gap-3 p-4 rounded-xl bg-violet-50/50">
                            <svg class="w-5 h-5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                            <p class="text-[13px] text-violet-700 font-medium">Full system access</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
