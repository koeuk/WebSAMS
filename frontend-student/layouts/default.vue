<script setup lang="ts">
const { user, logout } = useAuth()

const navigation = [
  { name: 'Dashboard', href: '/', icon: 'dashboard' },
  { name: 'My Attendance', href: '/attendance', icon: 'attendance' },
  { name: 'Notifications', href: '/notifications', icon: 'notifications' },
  { name: 'Profile', href: '/profile', icon: 'profile' },
]

const route = useRoute()
const isActive = (href: string) => route.path === href

const handleLogout = async () => {
  await logout()
  navigateTo('/login')
}
</script>

<template>
  <div class="min-h-screen bg-slate-50">
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 w-[260px] bg-[#0c1629] flex flex-col z-30">
      <div class="flex items-center gap-3 h-[72px] px-6 border-b border-white/[0.06]">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#d4a017] to-[#b08f12] flex items-center justify-center shadow-lg shadow-[#d4a017]/20">
          <img src="/logo.png" alt="BELTEI" class="h-7" />
        </div>
        <div>
          <h1 class="text-[15px] font-bold text-white tracking-tight">WebSAMS</h1>
          <p class="text-[10px] text-slate-400 font-medium tracking-wider uppercase">Student Portal</p>
        </div>
      </div>

      <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
        <NuxtLink
          v-for="item in navigation" :key="item.name" :to="item.href"
          class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium transition-all duration-200"
          :class="isActive(item.href) ? 'bg-white/[0.1] text-white nav-active-glow' : 'text-slate-400 hover:bg-white/[0.05] hover:text-slate-200'"
        >
          <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors"
               :class="isActive(item.href) ? 'bg-[#d4a017]/20 text-[#d4a017]' : 'bg-white/[0.04] text-slate-500 group-hover:text-slate-300'">
            <svg v-if="item.icon === 'dashboard'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
            <svg v-else-if="item.icon === 'attendance'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            <svg v-else-if="item.icon === 'notifications'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
            <svg v-else-if="item.icon === 'profile'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </div>
          {{ item.name }}
        </NuxtLink>
      </nav>

      <div class="px-3 pb-4 pt-2 border-t border-white/[0.06]">
        <div class="flex items-center gap-3 px-3 py-3">
          <div class="h-9 w-9 rounded-lg bg-gradient-to-br from-[#d4a017] to-[#b08f12] flex items-center justify-center text-white text-xs font-bold ring-2 ring-white/10">
            {{ user?.name?.charAt(0) }}
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-[13px] font-semibold text-white truncate">{{ user?.name }}</p>
            <p class="text-[11px] text-slate-500 truncate">Student</p>
          </div>
        </div>
        <button @click="handleLogout" class="flex items-center gap-2 w-full mt-1 px-3 py-2 rounded-lg text-[12px] font-medium text-slate-500 hover:text-red-400 hover:bg-red-500/[0.08] transition-colors">
          <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          Sign Out
        </button>
      </div>
    </aside>

    <div class="pl-[260px]">
      <header class="sticky top-0 z-20 flex items-center justify-between h-[72px] px-8 bg-white/80 backdrop-blur-xl border-b border-slate-100">
        <div class="flex items-center gap-3">
          <div class="h-8 w-1 rounded-full bg-gradient-to-b from-[#d4a017] to-beltei"></div>
          <div>
            <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">BELTEI International University</p>
            <p class="text-[13px] font-medium text-slate-600">Student Portal</p>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <div class="text-right mr-2">
            <p class="text-[13px] font-semibold text-slate-700">{{ user?.name }}</p>
            <p class="text-[11px] text-slate-400">Student</p>
          </div>
          <div class="h-9 w-9 rounded-lg bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-slate-500 text-sm font-bold">
            {{ user?.name?.charAt(0) }}
          </div>
        </div>
      </header>
      <main class="p-8"><slot /></main>
    </div>
  </div>
</template>
