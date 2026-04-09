<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();
const user = page.props.auth.user;
const sidebarCollapsed = ref(false);

const navigation = [
    { name: 'Dashboard', href: '/admin/dashboard', icon: 'dashboard' },
    { name: 'Users', href: '/admin/users', icon: 'users' },
    { name: 'Courses', href: '/admin/courses', icon: 'courses' },
    { name: 'Subjects', href: '/admin/subjects', icon: 'subjects' },
    { name: 'Classes', href: '/admin/classes', icon: 'classes' },
    { name: 'Semesters', href: '/admin/semesters', icon: 'semesters' },
    { name: 'Attendance', href: '/admin/attendance', icon: 'attendance' },
    { name: 'Time Slots', href: '/admin/time-slots', icon: 'timeslots' },
    { name: 'Schedules', href: '/admin/schedules', icon: 'schedules' },
    { name: 'Student Tracking', href: '/admin/student-tracking', icon: 'tracking' },
    { name: 'Reports', href: '/admin/reports', icon: 'reports' },
    { name: 'Notifications', href: '/admin/notifications', icon: 'notifications' },
    { name: 'Settings', href: '/admin/settings', icon: 'settings' },
];

const isActive = (href) => page.url.startsWith(href);
</script>

<template>
    <div class="min-h-screen bg-page-bg">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 w-[260px] bg-sidebar flex flex-col z-30">
            <!-- Brand -->
            <div class="flex items-center gap-3 h-[72px] px-6 border-b border-white/[0.06]">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-beltei-gold to-beltei-gold-dark flex items-center justify-center shadow-lg shadow-beltei-gold/20">
                    <img src="/logo.png" alt="BELTEI" class="h-7" />
                </div>
                <div>
                    <h1 class="text-[15px] font-bold text-white tracking-tight">WebSAMS</h1>
                    <p class="text-[10px] text-slate-400 font-medium tracking-wider uppercase">Admin Portal</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium transition-all duration-200"
                    :class="
                        isActive(item.href)
                            ? 'bg-white/[0.1] text-white nav-active-glow'
                            : 'text-slate-400 hover:bg-white/[0.05] hover:text-slate-200'
                    "
                >
                    <!-- Icons -->
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors"
                         :class="isActive(item.href) ? 'bg-beltei-gold/20 text-beltei-gold' : 'bg-white/[0.04] text-slate-500 group-hover:text-slate-300'">
                        <svg v-if="item.icon === 'dashboard'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                        <svg v-else-if="item.icon === 'users'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                        <svg v-else-if="item.icon === 'courses'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                        <svg v-else-if="item.icon === 'subjects'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/></svg>
                        <svg v-else-if="item.icon === 'classes'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <svg v-else-if="item.icon === 'semesters'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <svg v-else-if="item.icon === 'attendance'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                        <svg v-else-if="item.icon === 'timeslots'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <svg v-else-if="item.icon === 'schedules'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="9" y1="4" x2="9" y2="22"/></svg>
                        <svg v-else-if="item.icon === 'tracking'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        <svg v-else-if="item.icon === 'reports'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                        <svg v-else-if="item.icon === 'notifications'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                        <svg v-else-if="item.icon === 'settings'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                    </div>
                    {{ item.name }}
                </Link>
            </nav>

            <!-- User section at bottom -->
            <div class="px-3 pb-4 pt-2 border-t border-white/[0.06]">
                <Link href="/admin/profile" class="flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-white/[0.05] transition-colors">
                    <div class="relative">
                        <img v-if="user?.profile_photo" :src="`/storage/${user.profile_photo}`" class="h-9 w-9 rounded-lg object-cover ring-2 ring-white/10" />
                        <div v-else class="h-9 w-9 rounded-lg bg-gradient-to-br from-beltei-gold to-beltei-gold-dark flex items-center justify-center text-white text-xs font-bold ring-2 ring-white/10">
                            {{ user?.name?.charAt(0) }}
                        </div>
                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-400 rounded-full border-2 border-sidebar"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] font-semibold text-white truncate">{{ user?.name }}</p>
                        <p class="text-[11px] text-slate-500 truncate">Administrator</p>
                    </div>
                </Link>
                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    class="flex items-center gap-2 w-full mt-1 px-3 py-2 rounded-lg text-[12px] font-medium text-slate-500 hover:text-red-400 hover:bg-red-500/[0.08] transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Sign Out
                </Link>
            </div>
        </aside>

        <!-- Main content -->
        <div class="pl-[260px]">
            <!-- Top bar -->
            <header class="sticky top-0 z-20 flex items-center justify-between h-[72px] px-8 bg-white/80 backdrop-blur-xl border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-1 rounded-full bg-gradient-to-b from-beltei-gold to-beltei"></div>
                    <div>
                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">BELTEI International University</p>
                        <p class="text-[13px] font-medium text-slate-600">Student Attendance Management System</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right mr-2">
                        <p class="text-[13px] font-semibold text-slate-700">{{ user?.name }}</p>
                        <p class="text-[11px] text-slate-400">Admin</p>
                    </div>
                    <Link href="/admin/profile">
                        <img v-if="user?.profile_photo" :src="`/storage/${user.profile_photo}`" class="h-9 w-9 rounded-lg object-cover ring-2 ring-slate-100 hover:ring-beltei-gold/40 transition-all" />
                        <div v-else class="h-9 w-9 rounded-lg bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-slate-500 text-sm font-bold hover:ring-2 hover:ring-beltei-gold/40 transition-all">
                            {{ user?.name?.charAt(0) }}
                        </div>
                    </Link>
                </div>
            </header>

            <!-- Page content -->
            <main class="p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
