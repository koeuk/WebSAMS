<template>
  <div class="min-h-screen bg-paper">

    <!-- Mobile overlay -->
    <Transition name="fade">
      <div
        v-if="sidebarOpen"
        class="fixed inset-0 bg-[#0b1424]/70 backdrop-blur-sm z-30 lg:hidden"
        @click="sidebarOpen = false"
      />
    </Transition>

    <!-- Sidebar -->
    <aside
      class="fixed inset-y-0 left-0 w-[264px] flex flex-col z-40 transition-transform duration-300"
      style="background: linear-gradient(185deg, #0b1424 0%, #0d182c 55%, #101e38 100%);"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    >
      <!-- gold hairline at very top -->
      <div class="h-[3px] w-full" style="background: linear-gradient(90deg, transparent, #c8a415 35%, #d8b53a 50%, #c8a415 65%, transparent);"></div>

      <div class="flex items-center gap-3 h-[72px] px-6 border-b border-white/[0.07]">
        <div class="w-10 h-10 rounded-xl overflow-hidden shadow-lg ring-1 ring-[#c8a415]/30">
          <img src="/logo1.png" alt="BELTEI" class="w-full h-full object-cover" />
        </div>
        <div>
          <h1 class="font-display text-[18px] font-semibold text-white leading-none tracking-tight">WebSAMS</h1>
          <p class="text-[9.5px] text-[#c8a415]/80 font-semibold tracking-[0.2em] uppercase mt-1">{{ __('Student Portal') }}</p>
        </div>
        <button class="ml-auto cursor-pointer lg:hidden text-slate-400 hover:text-white" @click="sidebarOpen = false">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
        <p class="px-3 pb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">{{ __('Menu') }}</p>
        <NuxtLink
          v-for="item in navigation" :key="item.key" :to="item.href"
          class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-medium transition-all duration-200"
          :class="isActive(item.href) ? 'bg-white/[0.08] text-white nav-active-glow' : 'text-slate-400 hover:bg-white/[0.05] hover:text-slate-200'"
          @click="sidebarOpen = false"
        >
          <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors shrink-0"
               :class="isActive(item.href) ? 'bg-[#c8a415]/20 text-[#d8b53a] ring-1 ring-[#c8a415]/30' : 'bg-white/[0.04] text-slate-500 group-hover:text-slate-300'">
            <svg v-if="item.icon === 'dashboard'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
            <svg v-else-if="item.icon === 'attendance'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            <svg v-else-if="item.icon === 'excuse'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><path d="M9 12h6M9 16h4"/></svg>
            <svg v-else-if="item.icon === 'qr'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="5" height="5"/><rect x="16" y="3" width="5" height="5"/><rect x="3" y="16" width="5" height="5"/><path d="M21 16h-3v2h-2v3h2v-2h3v-3zM21 21v-2M14 14h3v3h-3z"/><path d="M3 14h2v2H3z"/></svg>
            <svg v-else-if="item.icon === 'announce'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.82a19.79 19.79 0 01-3.07-8.67A2 2 0 012 .18h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
            <svg v-else-if="item.icon === 'bell'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
            <svg v-else-if="item.icon === 'profile'" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
          </div>
          {{ item.name }}
        </NuxtLink>
      </nav>

      <div class="px-3 pb-4 pt-2 border-t border-white/[0.06]">
        <div class="flex items-center gap-3 px-3 py-3">
          <div class="h-9 w-9 rounded-lg bg-gradient-to-br from-[#d4a017] to-[#b08f12] flex items-center justify-center text-white text-xs font-bold ring-2 ring-white/10 shrink-0">
            {{ user?.name?.charAt(0) }}
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-[13px] font-semibold text-white truncate">{{ user?.name }}</p>
            <p class="text-[11px] text-slate-500 truncate">{{ __('Student') }}</p>
          </div>
          <button
            @click="handleLogout"
            :title="__('Sign Out')"
            :aria-label="__('Sign Out')"
            class="shrink-0 cursor-pointer flex items-center justify-center h-9 w-9 rounded-lg text-slate-500 hover:text-red-400 hover:bg-red-500/[0.08] transition-colors"
          >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          </button>
        </div>
      </div>
    </aside>

    <div class="lg:pl-[264px]">
      <header class="sticky top-0 z-20 flex items-center justify-between h-[72px] px-6 lg:px-8 bg-[#f7f5f0]/85 backdrop-blur-xl border-b border-line">
        <div class="flex items-center gap-3.5">
          <button
            class="lg:hidden w-9 h-9 flex cursor-pointer items-center justify-center rounded-lg text-slate-600 hover:bg-black/5 transition-colors"
            @click="sidebarOpen = true"
          >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
          </button>
          <div class="h-9 w-[3px] rounded-full bg-gradient-to-b from-[#d8b53a] via-[#c8a415] to-beltei hidden lg:block"></div>
          <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.16em] hidden lg:block">{{ __('BELTEI International University') }}</p>
            <p class="font-display text-[15px] font-semibold text-beltei leading-tight">{{ __('Student Portal') }}</p>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <!-- Language switcher -->
          <Popover>
            <PopoverTrigger as-child>
              <button class="flex cursor-pointer items-center gap-1.5 h-9 px-3 rounded-lg text-[12px] font-semibold text-slate-600 border border-line hover:bg-white hover:border-line-strong transition-colors">
                <svg class="w-4 h-4 text-beltei-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 010 20M12 2a15 15 0 000 20"/></svg>
                {{ currentLocaleName }}
                <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
              </button>
            </PopoverTrigger>
            <PopoverContent class="w-36 p-1" align="end">
              <button
                v-for="loc in locales"
                :key="loc.code"
                @click="setLocale(loc.code as any)"
                class="w-full flex items-center justify-between gap-2 cursor-pointer px-3 py-2 text-[13px] rounded-md transition-colors"
                :class="loc.code === locale ? 'bg-accent text-beltei font-semibold' : 'text-slate-600 hover:bg-paper'"
              >
                {{ loc.name }}
                <svg v-if="loc.code === locale" class="w-3.5 h-3.5 text-beltei-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
              </button>
            </PopoverContent>
          </Popover>

          <div class="h-6 w-px bg-line hidden sm:block"></div>

          <div class="text-right mr-1 hidden sm:block">
            <p class="text-[13px] font-semibold text-slate-800">{{ user?.name }}</p>
            <p class="text-[10px] text-beltei-gold font-semibold uppercase tracking-wider">{{ __('Student') }}</p>
          </div>
          <div class="h-9 w-9 rounded-xl flex items-center justify-center text-white text-sm font-bold ring-1 ring-[#c8a415]/30"
               style="background: linear-gradient(135deg, #1e3a6e, #2a4f8f);">
            {{ user?.name?.charAt(0) }}
          </div>
        </div>
      </header>
      <main class="p-6 lg:p-8 max-w-[1340px] mx-auto"><slot /></main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { Popover, PopoverTrigger, PopoverContent } from '@/components/ui/popover'

const { user, logout } = useAuth()
const { locale, locales, setLocale } = useI18n()

const navigation = computed(() => [
  { key: 'dashboard',      name: __('Dashboard'),      href: '/',              icon: 'dashboard' },
  { key: 'myAttendance',   name: __('My Attendance'),   href: '/attendance',    icon: 'attendance' },
  { key: 'excuseRequests', name: __('Excuse Requests'), href: '/excuses',       icon: 'excuse' },
  { key: 'qrAttend',       name: __('QR Attend'),       href: '/qr',            icon: 'qr' },
  { key: 'announcements',  name: __('Announcements'),  href: '/announcements', icon: 'announce' },
  { key: 'notifications',  name: __('Notifications'),  href: '/notifications', icon: 'bell' },
  { key: 'profile',        name: __('Profile'),        href: '/profile',       icon: 'profile' },
])

const currentLocaleName = computed(() => {
  const found = (locales.value as any[]).find((l) => l.code === locale.value)
  return found?.name ?? locale.value
})

const route = useRoute()
const isActive = (href: string) => route.path === href || (href !== '/' && route.path.startsWith(href))

const sidebarOpen = ref(false)

const handleLogout = async () => {
  await logout()
  navigateTo('/auth/login')
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
