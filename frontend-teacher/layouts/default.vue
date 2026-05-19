<script setup lang="ts">
import { ref } from 'vue'

const { user, logout } = useAuth()

const navigation = [
  { name: 'Dashboard',        href: '/',                icon: 'dashboard', exact: true },
  { name: 'My Classes',       href: '/classes',         icon: 'classes' },
  { name: 'Mark Attendance',  href: '/attendance/mark', icon: 'mark' },
  { name: 'History',          href: '/attendance',      icon: 'history',   exact: true },
  { name: 'QR Attendance',    href: '/qr',              icon: 'qr' },
  { name: 'Excuse Requests',  href: '/excuses',         icon: 'excuse' },
  { name: 'Announcements',    href: '/announcements',   icon: 'announce' },
  { name: 'Notifications',    href: '/notifications',   icon: 'bell' },
  { name: 'Profile',          href: '/profile',         icon: 'profile' },
]

const route = useRoute()
const isActive = (item: { href: string; exact?: boolean }) =>
  item.exact ? route.path === item.href : route.path === item.href || route.path.startsWith(item.href + '/')

const sidebarOpen = ref(false)

const handleLogout = async () => {
  await logout()
  navigateTo('/auth/login')
}
</script>

<template>
  <div class="min-h-screen bg-slate-50">

    <!-- Mobile overlay -->
    <Transition name="fade">
      <div
        v-if="sidebarOpen"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden"
        @click="sidebarOpen = false"
      />
    </Transition>

    <!-- Sidebar -->
    <aside
      class="fixed inset-y-0 left-0 w-[260px] bg-[#0c1629] flex flex-col z-40 transition-transform duration-300"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    >
      <!-- Brand -->
      <div class="flex items-center gap-3 h-[72px] px-6 border-b border-white/[0.06]">
        <div class="w-10 h-10 rounded-xl overflow-hidden shadow-lg">
          <img src="/logo1.png" alt="BELTEI" class="w-full h-full object-cover" />
        </div>
        <div>
          <h1 class="text-[15px] font-bold text-white tracking-tight">WebSAMS</h1>
          <p class="text-[10px] text-slate-400 font-medium tracking-wider uppercase">Teacher Portal</p>
        </div>
        <button class="ml-auto lg:hidden text-slate-400 hover:text-white" @click="sidebarOpen = false">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
        <NuxtLink
          v-for="item in navigation"
          :key="item.name"
          :to="item.href"
          class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium transition-all duration-200"
          :class="isActive(item)
            ? 'bg-white/[0.1] text-white nav-active-glow'
            : 'text-slate-400 hover:bg-white/[0.05] hover:text-slate-200'"
          @click="sidebarOpen = false"
        >
          <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors shrink-0"
               :class="isActive(item) ? 'bg-[#d4a017]/20 text-[#d4a017]' : 'bg-white/[0.04] text-slate-500 group-hover:text-slate-300'">
            <svg v-if="item.icon === 'dashboard'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
            <svg v-else-if="item.icon === 'classes'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <svg v-else-if="item.icon === 'mark'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            <svg v-else-if="item.icon === 'history'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <svg v-else-if="item.icon === 'qr'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="5" height="5"/><rect x="16" y="3" width="5" height="5"/><rect x="3" y="16" width="5" height="5"/><path d="M21 16h-3v2h-2v3h2v-2h3v-3zM21 21v-2M14 14h3v3h-3z"/><path d="M3 14h2v2H3z"/></svg>
            <svg v-else-if="item.icon === 'excuse'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><path d="M9 12h6M9 16h4"/></svg>
            <svg v-else-if="item.icon === 'announce'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.82a19.79 19.79 0 01-3.07-8.67A2 2 0 012 .18h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
            <svg v-else-if="item.icon === 'bell'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
            <svg v-else-if="item.icon === 'profile'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
          </div>
          {{ item.name }}
        </NuxtLink>
      </nav>

      <!-- User section -->
      <div class="px-3 pb-4 pt-2 border-t border-white/[0.06]">
        <div class="flex items-center gap-3 px-3 py-3">
          <div class="h-9 w-9 rounded-lg bg-gradient-to-br from-[#d4a017] to-[#b08f12] flex items-center justify-center text-white text-xs font-bold ring-2 ring-white/10 shrink-0">
            {{ user?.name?.charAt(0) }}
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-[13px] font-semibold text-white truncate">{{ user?.name }}</p>
            <p class="text-[11px] text-slate-500 truncate">Teacher</p>
          </div>
        </div>
        <button
          @click="handleLogout"
          class="flex items-center gap-2 w-full mt-1 px-3 py-2 rounded-lg text-[12px] font-medium text-slate-500 hover:text-red-400 hover:bg-red-500/[0.08] transition-colors"
        >
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          Sign Out
        </button>
      </div>
    </aside>

    <!-- Main content -->
    <div class="lg:pl-[260px]">
      <!-- Top bar -->
      <header class="sticky top-0 z-20 flex items-center justify-between h-[72px] px-6 lg:px-8 bg-white/80 backdrop-blur-xl border-b border-slate-100">
        <div class="flex items-center gap-3">
          <!-- Hamburger (mobile) -->
          <button
            class="lg:hidden w-9 h-9 flex items-center justify-center rounded-lg text-slate-600 hover:bg-slate-100 transition-colors"
            @click="sidebarOpen = true"
          >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
          </button>
          <div class="h-8 w-1 rounded-full bg-gradient-to-b from-[#d4a017] to-beltei hidden lg:block"></div>
          <div>
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider hidden lg:block">BELTEI International University</p>
            <p class="text-[13px] font-medium text-slate-600">Teacher Portal</p>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <div class="text-right mr-2 hidden sm:block">
            <p class="text-[13px] font-semibold text-slate-700">{{ user?.name }}</p>
            <p class="text-[11px] text-slate-400">Teacher</p>
          </div>
          <div class="h-9 w-9 rounded-lg bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-slate-500 text-sm font-bold">
            {{ user?.name?.charAt(0) }}
          </div>
        </div>
      </header>

      <main class="p-6 lg:p-8">
        <slot />
      </main>
    </div>
  </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
