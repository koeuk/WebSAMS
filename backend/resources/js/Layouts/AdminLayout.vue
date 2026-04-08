<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user;

const navigation = [
    { name: 'Dashboard', href: '/admin/dashboard' },
    { name: 'Users', href: '/admin/users' },
    { name: 'Courses', href: '/admin/courses' },
    { name: 'Subjects', href: '/admin/subjects' },
    { name: 'Classes', href: '/admin/classes' },
    { name: 'Semesters', href: '/admin/semesters' },
    { name: 'Attendance', href: '/admin/attendance' },
    { name: 'Time Slots', href: '/admin/time-slots' },
    { name: 'Schedules', href: '/admin/schedules' },
    { name: 'Student Tracking', href: '/admin/student-tracking' },
    { name: 'Reports', href: '/admin/reports' },
    { name: 'Notifications', href: '/admin/notifications' },
    { name: 'Settings', href: '/admin/settings' },
];
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200">
            <div class="flex items-center gap-3 h-16 px-6 border-b-2 border-beltei-gold bg-beltei text-white">
                <img src="/logo.png" alt="BELTEI" class="h-10" />
                <h1 class="text-xl font-bold">WebSAMS</h1>
            </div>
            <nav class="p-4 space-y-1">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors"
                    :class="
                        $page.url.startsWith(item.href)
                            ? 'bg-beltei text-white'
                            : 'text-gray-600 hover:bg-gray-100'
                    "
                >
                    {{ item.name }}
                </Link>
            </nav>
        </aside>

        <!-- Main content -->
        <div class="pl-64">
            <!-- Top bar -->
            <header class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-200">
                <div></div>
                <div class="flex items-center gap-4">
                    <Link href="/admin/profile" class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900">
                        <img v-if="user?.profile_photo" :src="`/storage/${user.profile_photo}`" class="h-8 w-8 rounded-full object-cover border" />
                        <div v-else class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs font-bold">
                            {{ user?.name?.charAt(0) }}
                        </div>
                        {{ user?.name }}
                    </Link>
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="text-sm text-red-600 hover:text-red-800"
                    >
                        Logout
                    </Link>
                </div>
            </header>

            <!-- Page content -->
            <main class="p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
