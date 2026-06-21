<template>
  <div class="relative isolate min-h-screen">

    <!-- Dotted atmosphere + cursor spotlight — spans the very top of the page (behind the header) -->
    <PageAtmosphere />

    <!-- ═══ Desktop · Floating command bar ═══════════════════════ -->
    <header class="hidden lg:block sticky top-0 z-40">
      <div class="relative max-w-[1340px] mx-auto px-8 pt-4 pb-2">
        <div class="cmd-bar flex items-center h-[62px] px-2 gap-2 rounded-2xl border border-white/60 dark:border-white/10 bg-white/45 dark:bg-[#0e1f18]/55 backdrop-blur-2xl">

          <!-- Brand -->
          <NuxtLink to="/" class="flex items-center gap-2.5 shrink-0">
            <div class="w-9 h-9 rounded-xl overflow-hidden shadow-md ring-1 ring-[#c8a415]/30">
              <img :src="logoUrl" @error="onLogoError" alt="BELTEI" class="w-full h-full object-cover" />
            </div>
            <div class="leading-none">
              <h1 class="font-display text-[16px] font-semibold text-beltei tracking-tight">WebSAMS</h1>
              <p class="text-[8px] text-beltei-gold font-bold tracking-[0.22em] uppercase mt-0.5">{{ __('Student Portal') }}</p>
            </div>
          </NuxtLink>

          <div class="h-7 w-px bg-line mx-1 shrink-0"></div>

          <!-- Nav pills -->
          <nav ref="navRef" class="relative flex items-center justify-center gap-0.5 flex-1 min-w-0">
            <!-- Sliding active indicator (glides to the current route) -->
            <span
              class="nav-indicator"
              :style="{ transform: `translateX(${indicator.x}px)`, width: indicator.w + 'px', opacity: indicator.show ? 1 : 0 }"
            ></span>
            <NuxtLink
              v-for="item in mainNav" :key="item.key" :to="item.href"
              :title="item.name"
              :data-active="isActive(item.href)"
              class="group relative z-[1] flex items-center gap-2 px-2.5 xl:px-3 h-10 rounded-xl text-[13px] font-medium transition-colors duration-200"
              :class="isActive(item.href) ? 'text-emerald-800 font-semibold' : 'text-slate-500 hover:text-emerald-700'"
            >
              <NavIcon :name="item.icon" class="w-[17px] h-[17px] shrink-0 transition-colors"
                       :class="isActive(item.href) ? 'text-emerald-600' : 'text-slate-400 group-hover:text-emerald-600'" />
              <span class="hidden xl:inline whitespace-nowrap">{{ item.name }}</span>
            </NuxtLink>
          </nav>

          <div class="h-7 w-px bg-line mx-0.5 shrink-0"></div>

          <!-- Right cluster -->
          <div class="flex items-center gap-2 shrink-0">
            <!-- Theme toggle -->
            <button
              @click="toggleDark"
              :title="__('Toggle theme')" :aria-label="__('Toggle theme')"
              class="flex cursor-pointer items-center justify-center h-9 w-9 rounded-lg text-slate-500 hover:bg-white/50 dark:hover:bg-white/10 hover:text-emerald-600 transition-colors"
            >
              <!-- moon (light mode → click for dark) -->
              <svg class="w-[18px] h-[18px] dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
              <!-- sun (dark mode → click for light) -->
              <svg class="w-[18px] h-[18px] hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="4.2"/><path d="M12 2v2.5M12 19.5V22M4.9 4.9l1.8 1.8M17.3 17.3l1.8 1.8M2 12h2.5M19.5 12H22M4.9 19.1l1.8-1.8M17.3 6.7l1.8-1.8"/></svg>
            </button>

            <!-- Language switcher -->
            <Popover>
              <PopoverTrigger as-child>
                <button class="flex cursor-pointer items-center gap-1.5 h-9 px-2.5 rounded-lg text-[12px] font-semibold text-slate-600 hover:bg-white/50 transition-colors">
                  <svg class="w-4 h-4 text-beltei-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 010 20M12 2a15 15 0 000 20"/></svg>
                  <span class="hidden xl:inline">{{ currentLocaleName }}</span>
                  <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
                </button>
              </PopoverTrigger>
              <PopoverContent class="w-36 p-1" align="end">
                <button
                  v-for="loc in locales"
                  :key="loc.code"
                  @click="setLocale(loc.code as any)"
                  class="w-full flex items-center justify-between gap-2 cursor-pointer px-3 py-2 text-[13px] rounded-md transition-colors"
                  :class="loc.code === locale ? 'bg-accent text-emerald-800 font-semibold' : 'text-slate-600 hover:bg-paper'"
                >
                  {{ loc.name }}
                  <svg v-if="loc.code === locale" class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
                </button>
              </PopoverContent>
            </Popover>

            <!-- Account menu -->
            <Popover :open="acctOpen" @update:open="(v) => acctOpen = v">
              <PopoverTrigger as-child>
                <button
                  class="flex items-center gap-1.5 h-10 pl-1 pr-1.5 rounded-xl hover:bg-white/50 transition-colors cursor-pointer"
                  :class="route.path === '/profile' ? 'bg-accent' : ''"
                >
                  <div class="h-9 w-9 rounded-xl overflow-hidden ring-1 ring-[#c8a415]/30 shrink-0">
                    <img v-if="photoUrl" :src="photoUrl" alt="" class="h-full w-full object-cover" />
                    <div v-else class="h-full w-full flex items-center justify-center text-white text-sm font-bold"
                         style="background: linear-gradient(135deg, #1e3a6e, #2a4f8f);">
                      {{ user?.name?.charAt(0) }}
                    </div>
                  </div>
                  <svg class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="acctOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
                </button>
              </PopoverTrigger>
              <PopoverContent class="w-56 p-1.5" align="end">
                <div class="px-2.5 py-2">
                  <p class="text-[13px] font-semibold text-slate-800 truncate">{{ user?.name }}</p>
                  <p class="text-[11px] text-slate-400 truncate">{{ user?.email }}</p>
                </div>
                <div class="h-px bg-line my-1"></div>
                <NuxtLink
                  to="/profile" @click="acctOpen = false"
                  class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[13px] font-medium text-slate-600 hover:bg-paper hover:text-beltei transition-colors"
                >
                  <svg class="w-4 h-4 text-beltei-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                  {{ __('Profile') }}
                </NuxtLink>
                <button
                  @click="acctOpen = false; handleLogout()"
                  class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[13px] font-medium text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                >
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                  {{ __('Sign Out') }}
                </button>
              </PopoverContent>
            </Popover>
          </div>
        </div>
      </div>
    </header>

    <!-- ═══ Mobile · slim top bar ════════════════════════════════ -->
    <header class="lg:hidden sticky top-0 z-30 bg-white/55 dark:bg-[#0e1f18]/60 backdrop-blur-2xl border-b border-white/50 dark:border-white/10">
      <div class="flex items-center justify-between h-14 px-4">
        <NuxtLink to="/" class="flex items-center gap-2.5">
          <div class="w-8 h-8 rounded-lg overflow-hidden shadow ring-1 ring-[#c8a415]/30">
            <img :src="logoUrl" @error="onLogoError" alt="BELTEI" class="w-full h-full object-cover" />
          </div>
          <div class="leading-none">
            <h1 class="font-display text-[15px] font-semibold text-beltei">WebSAMS</h1>
            <p class="text-[7.5px] text-beltei-gold font-bold tracking-[0.2em] uppercase mt-0.5">{{ __('Student Portal') }}</p>
          </div>
        </NuxtLink>
        <div class="flex items-center gap-2">
          <!-- Theme toggle -->
          <button
            @click="toggleDark"
            :title="__('Toggle theme')" :aria-label="__('Toggle theme')"
            class="flex cursor-pointer items-center justify-center h-9 w-9 rounded-lg text-slate-500 hover:text-emerald-600 transition-colors"
          >
            <svg class="w-[18px] h-[18px] dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
            <svg class="w-[18px] h-[18px] hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="4.2"/><path d="M12 2v2.5M12 19.5V22M4.9 4.9l1.8 1.8M17.3 17.3l1.8 1.8M2 12h2.5M19.5 12H22M4.9 19.1l1.8-1.8M17.3 6.7l1.8-1.8"/></svg>
          </button>

          <!-- Language switcher -->
          <Popover>
            <PopoverTrigger as-child>
              <button class="flex cursor-pointer items-center gap-1 h-9 px-2.5 rounded-lg text-[12px] font-semibold text-slate-600 border border-line hover:bg-white transition-colors">
                <svg class="w-4 h-4 text-beltei-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 010 20M12 2a15 15 0 000 20"/></svg>
                {{ currentLocaleName }}
              </button>
            </PopoverTrigger>
            <PopoverContent class="w-36 p-1" align="end">
              <button
                v-for="loc in locales"
                :key="loc.code"
                @click="setLocale(loc.code as any)"
                class="w-full flex items-center justify-between gap-2 cursor-pointer px-3 py-2 text-[13px] rounded-md transition-colors"
                :class="loc.code === locale ? 'bg-accent text-emerald-800 font-semibold' : 'text-slate-600 hover:bg-paper'"
              >
                {{ loc.name }}
                <svg v-if="loc.code === locale" class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
              </button>
            </PopoverContent>
          </Popover>
          <!-- Account menu -->
          <Popover :open="acctOpenMobile" @update:open="(v) => acctOpenMobile = v">
            <PopoverTrigger as-child>
              <button class="h-9 w-9 rounded-xl overflow-hidden ring-1 ring-[#c8a415]/30 cursor-pointer">
                <img v-if="photoUrl" :src="photoUrl" alt="" class="h-full w-full object-cover" />
                <div v-else class="h-full w-full flex items-center justify-center text-white text-sm font-bold"
                     style="background: linear-gradient(135deg, #1e3a6e, #2a4f8f);">
                  {{ user?.name?.charAt(0) }}
                </div>
              </button>
            </PopoverTrigger>
            <PopoverContent class="w-56 p-1.5" align="end">
              <div class="px-2.5 py-2">
                <p class="text-[13px] font-semibold text-slate-800 truncate">{{ user?.name }}</p>
                <p class="text-[11px] text-slate-400 truncate">{{ user?.email }}</p>
              </div>
              <div class="h-px bg-line my-1"></div>
              <NuxtLink
                to="/profile" @click="acctOpenMobile = false"
                class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[13px] font-medium text-slate-600 hover:bg-paper hover:text-beltei transition-colors"
              >
                <svg class="w-4 h-4 text-beltei-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                {{ __('Profile') }}
              </NuxtLink>
              <button
                @click="acctOpenMobile = false; handleLogout()"
                class="w-full flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[13px] font-medium text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
              >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                {{ __('Sign Out') }}
              </button>
            </PopoverContent>
          </Popover>
        </div>
      </div>
      <div class="h-[2px] w-full" style="background: linear-gradient(90deg, transparent, #c8a415 25%, #d8b53a 50%, #c8a415 75%, transparent);"></div>
    </header>

    <main class="relative isolate">
      <div :key="route.path" class="enter-rise p-3 pb-24 sm:p-6 sm:pb-24 lg:px-8 lg:pt-2 lg:pb-10 max-w-[1340px] mx-auto">
        <slot />
      </div>
    </main>

    <!-- Mobile bottom tab bar -->
    <nav
      class="fixed bottom-0 inset-x-0 z-20 lg:hidden border-t border-white/50 dark:border-white/10 bg-white/65 dark:bg-[#0e1f18]/70 backdrop-blur-2xl pb-[env(safe-area-inset-bottom)]"
      style="box-shadow: 0 -10px 30px -12px rgba(6,78,59,0.25);"
    >
      <div class="grid grid-cols-4">
        <NuxtLink
          v-for="item in bottomNav" :key="item.key" :to="item.href"
          class="flex flex-col items-center justify-center py-2"
        >
          <span
            class="flex flex-col items-center justify-center gap-1 rounded-2xl px-2.5 py-1.5 transition-all duration-200"
            :class="isActive(item.href)
              ? 'text-white shadow-[0_8px_20px_-6px_rgba(6,120,87,0.6)]'
              : 'text-slate-500 active:text-emerald-700'"
            :style="isActive(item.href) ? 'background: var(--nav-grad);' : ''"
          >
            <svg v-if="item.icon === 'dashboard'" class="w-[22px] h-[22px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
            <svg v-else-if="item.icon === 'attendance'" class="w-[22px] h-[22px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            <svg v-else-if="item.icon === 'bell'" class="w-[22px] h-[22px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
            <svg v-else-if="item.icon === 'profile'" class="w-[22px] h-[22px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            <span class="text-[10px] font-medium leading-none whitespace-nowrap">{{ item.name }}</span>
          </span>
        </NuxtLink>
      </div>
    </nav>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, watch, nextTick, onMounted, onBeforeUnmount } from 'vue'
import { Popover, PopoverTrigger, PopoverContent } from '@/components/ui/popover'

const { user, logout } = useAuth()
const { logoUrl, loadBranding, onLogoError } = useBranding()
onMounted(loadBranding)

// Resolve the user's profile photo to a full storage URL (same scheme as the
// profile page). Falls back to the name initial when there's no photo.
const config = useRuntimeConfig()
const storageBase = (config.public.apiBase as string).replace(/\/api\/?$/, '/storage')
const photoUrl = computed(() =>
  user.value?.profile_photo ? `${storageBase}/${user.value.profile_photo}` : null,
)

// Light / dark theme (persisted by @nuxtjs/color-mode)
const colorMode = useColorMode()
const toggleDark = () => {
  colorMode.preference = colorMode.value === 'dark' ? 'light' : 'dark'
}
const { locale, locales, setLocale } = useI18n()

const navigation = computed(() => [
  { key: 'dashboard',      name: __('Dashboard'),       href: '/',              icon: 'dashboard' },
  { key: 'myAttendance',   name: __('My Attendance'),   href: '/attendance',    icon: 'attendance' },
  { key: 'excuseRequests', name: __('Excuse Requests'), href: '/excuses',       icon: 'excuse' },
  { key: 'qrAttend',       name: __('QR Attend'),       href: '/qr',            icon: 'qr' },
  { key: 'announcements',  name: __('Announcements'),   href: '/announcements', icon: 'announce' },
  { key: 'notifications',  name: __('Notifications'),   href: '/notifications', icon: 'bell' },
  { key: 'profile',        name: __('Profile'),         href: '/profile',       icon: 'profile' },
])

// Primary items surfaced as a bottom tab bar on mobile
const bottomNavKeys = ['dashboard', 'myAttendance', 'notifications', 'profile']
const bottomNav = computed(() =>
  navigation.value
    .filter((i) => bottomNavKeys.includes(i.key))
    .map((i) => (i.key === 'dashboard' ? { ...i, name: __('Home') } : i)),
)

// Desktop bar shows the main sections; Profile + Sign Out live in the account menu
const mainNav = computed(() => navigation.value.filter((i) => i.key !== 'profile'))
const acctOpen = ref(false)
const acctOpenMobile = ref(false)

const currentLocaleName = computed(() => {
  const found = (locales.value as any[]).find((l) => l.code === locale.value)
  return found?.name ?? locale.value
})

const route = useRoute()
const isActive = (href: string) => route.path === href || (href !== '/' && route.path.startsWith(href))

// Sliding active indicator for the desktop command-bar nav
const navRef = ref<HTMLElement | null>(null)
const indicator = ref({ x: 0, w: 0, show: false })
const moveIndicator = () => {
  const nav = navRef.value
  if (!nav) return
  const active = nav.querySelector<HTMLElement>('[data-active="true"]')
  if (!active) { indicator.value = { ...indicator.value, show: false }; return }
  const navRect = nav.getBoundingClientRect()
  const r = active.getBoundingClientRect()
  indicator.value = { x: r.left - navRect.left, w: r.width, show: true }
}
onMounted(() => {
  nextTick(moveIndicator)
  window.addEventListener('resize', moveIndicator)
  // Re-measure once web fonts settle (label widths can shift on load)
  ;(document as any).fonts?.ready?.then(() => moveIndicator())
})
onBeforeUnmount(() => window.removeEventListener('resize', moveIndicator))
// Re-measure on navigation and on language change (label widths shift)
watch([() => route.path, locale], () => nextTick(moveIndicator))

const handleLogout = async () => {
  await logout()
  navigateTo('/auth/login')
}
</script>

<style scoped>
/* Sliding active pill — glides between nav items with a soft "liquid" overshoot */
.nav-indicator {
  position: absolute;
  top: 0;
  left: 0;
  height: 2.5rem;            /* matches h-10 nav links */
  border-radius: 0.75rem;    /* rounded-xl */
  background: var(--nav-pill-bg);
  box-shadow:
    inset 0 0 0 1px var(--nav-pill-ring),
    0 4px 14px -4px var(--nav-pill-glow);
  transition:
    transform 0.42s cubic-bezier(0.34, 1.18, 0.4, 1),
    width 0.42s cubic-bezier(0.34, 1.18, 0.4, 1),
    opacity 0.25s ease;
  pointer-events: none;
}
@media (prefers-reduced-motion: reduce) {
  .nav-indicator { transition: opacity 0.2s ease; }
}

.cmd-bar {
  -webkit-backdrop-filter: blur(24px) saturate(180%);
  backdrop-filter: blur(24px) saturate(180%);
  box-shadow:
    inset 0 1px 0 rgba(255, 255, 255, 0.85),          /* specular top edge */
    0 18px 50px -20px rgba(6, 78, 59, 0.35),           /* emerald-tinted glass shadow */
    0 2px 8px -3px rgba(6, 78, 59, 0.10);
  animation: barDrop 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
}

@keyframes barDrop {
  from { opacity: 0; transform: translateY(-10px); }
  to   { opacity: 1; transform: translateY(0); }
}

@media (prefers-reduced-motion: reduce) {
  .cmd-bar { animation: none; }
}
</style>
