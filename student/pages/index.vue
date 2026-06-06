<template>
  <div class="animate-fade-in">

    <!-- Header -->
    <div class="flex items-end justify-between gap-4 mb-8">
      <div>
        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-beltei-gold mb-1.5">{{ __('Dashboard') }}</p>
        <h2 class="page-title text-[30px] sm:text-[34px]">
          {{ __('Good day, {name} 👋', { name: firstName }) }}
        </h2>
        <div class="gold-rule mt-3"></div>
        <p class="text-sm text-slate-500 mt-3">{{ __("Here's your attendance overview.") }}</p>
      </div>
      <div class="hidden sm:flex items-center gap-2.5 text-[13px] font-medium text-slate-600 bg-white px-4 py-2.5 rounded-xl border border-line shadow-sm">
        <svg class="w-4 h-4 text-beltei-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        {{ new Date().toLocaleDateString(dateLocale, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
      </div>
    </div>

    <div v-if="loading" class="card p-12 text-center">
      <div class="inline-flex items-center gap-2 text-sm text-slate-400">
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
        {{ __('Loading...') }}
      </div>
    </div>

    <div v-else>

      <!-- Top row: rate ring + stat cards -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">

        <!-- Attendance rate ring -->
        <div class="card card-hover p-6 lg:p-7 flex items-center gap-6 lg:col-span-1 animate-fade-in-up">
          <div class="relative shrink-0">
            <svg width="132" height="132" viewBox="0 0 132 132" class="-rotate-90">
              <circle cx="66" cy="66" :r="radius" fill="none" stroke="#ece7da" stroke-width="11" />
              <circle
                cx="66" cy="66" :r="radius" fill="none"
                :stroke="rateColor"
                stroke-width="11"
                stroke-linecap="round"
                :stroke-dasharray="circumference"
                :stroke-dashoffset="dashOffset"
                style="transition: stroke-dashoffset 0.9s cubic-bezier(0.22,1,0.36,1), stroke 0.4s ease;"
              />
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
              <span class="font-display nums text-[30px] font-semibold text-slate-900 leading-none">{{ rate }}<span class="text-[18px] text-slate-400">%</span></span>
              <span class="text-[9.5px] text-beltei-gold mt-1 uppercase tracking-[0.18em] font-bold">{{ __('Rate') }}</span>
            </div>
          </div>
          <div class="flex-1 min-w-0">
            <p class="font-display text-[15px] font-semibold text-slate-800 mb-3">{{ __('Attendance Rate') }}</p>
            <div class="space-y-2">
              <div class="flex items-center gap-2 text-[13px]">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-slate-500">{{ __('Present') }}</span>
                <span class="ml-auto font-bold text-slate-800 nums">{{ dashboard.summary?.present }}</span>
              </div>
              <div class="flex items-center gap-2 text-[13px]">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                <span class="text-slate-500">{{ __('Absent') }}</span>
                <span class="ml-auto font-bold text-slate-800 nums">{{ dashboard.summary?.absent }}</span>
              </div>
              <div class="flex items-center gap-2 text-[13px]">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span class="text-slate-500">{{ __('Late') }}</span>
                <span class="ml-auto font-bold text-slate-800 nums">{{ dashboard.summary?.late }}</span>
              </div>
              <div class="flex items-center gap-2 text-[13px] pt-2 mt-1 border-t border-line">
                <span class="w-2 h-2 rounded-full bg-beltei"></span>
                <span class="text-slate-500">{{ __('Total') }}</span>
                <span class="ml-auto font-bold text-slate-900 nums">{{ dashboard.summary?.total }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- 4 stat cards 2x2 -->
        <div class="lg:col-span-2 grid grid-cols-2 gap-4 stagger-children">
          <div class="card card-hover stat-card stat-card-navy p-5 animate-pop-in">
            <div class="flex items-center justify-between mb-3">
              <p class="stat-label">{{ __('Total') }}</p>
              <div class="w-9 h-9 rounded-xl bg-[#eef2f9] flex items-center justify-center ring-1 ring-[#dbe4f3]">
                <svg class="w-4.5 h-4.5 text-beltei" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
              </div>
            </div>
            <p class="stat-value text-[34px]">{{ dashboard.summary?.total }}</p>
            <p class="text-[11px] text-slate-400 mt-1.5">{{ __('Recorded sessions') }}</p>
          </div>

          <div class="card card-hover stat-card stat-card-emerald p-5 animate-pop-in">
            <div class="flex items-center justify-between mb-3">
              <p class="stat-label">{{ __('Present') }}</p>
              <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center ring-1 ring-emerald-100">
                <svg class="w-4.5 h-4.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M20 6L9 17l-5-5"/></svg>
              </div>
            </div>
            <p class="stat-value text-[34px] text-emerald-700">{{ dashboard.summary?.present }}</p>
            <p class="text-[11px] text-slate-400 mt-1.5">{{ __('Days attended') }}</p>
          </div>

          <div class="card card-hover stat-card stat-card-rose p-5 animate-pop-in">
            <div class="flex items-center justify-between mb-3">
              <p class="stat-label">{{ __('Absent') }}</p>
              <div class="w-9 h-9 rounded-xl bg-rose-50 flex items-center justify-center ring-1 ring-rose-100">
                <svg class="w-4.5 h-4.5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </div>
            </div>
            <p class="stat-value text-[34px] text-rose-700">{{ dashboard.summary?.absent }}</p>
            <p class="text-[11px] text-slate-400 mt-1.5">{{ __('Days missed') }}</p>
          </div>

          <div class="card card-hover stat-card stat-card-amber p-5 animate-pop-in">
            <div class="flex items-center justify-between mb-3">
              <p class="stat-label">{{ __('Late') }}</p>
              <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center ring-1 ring-amber-100">
                <svg class="w-4.5 h-4.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              </div>
            </div>
            <p class="stat-value text-[34px] text-amber-700">{{ dashboard.summary?.late }}</p>
            <p class="text-[11px] text-slate-400 mt-1.5">{{ __('Late arrivals') }}</p>
          </div>
        </div>
      </div>

      <!-- By Subject -->
      <div class="card overflow-hidden mb-5 animate-fade-in-up">
        <div class="section-head">
          <span class="section-tick"></span>
          <h3 class="section-title">{{ __('Attendance by Subject') }}</h3>
        </div>
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>{{ __('Subject') }}</TableHead>
              <TableHead class="text-center">{{ __('Total') }}</TableHead>
              <TableHead class="text-center">{{ __('Present') }}</TableHead>
              <TableHead class="text-center">{{ __('Absent') }}</TableHead>
              <TableHead style="min-width:160px;">{{ __('Rate') }}</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="row in dashboard.bySubject" :key="row.subject">
              <TableCell class="font-semibold text-slate-900">{{ row.subject }}</TableCell>
              <TableCell class="text-center nums text-slate-600">{{ row.total }}</TableCell>
              <TableCell class="text-center nums text-emerald-700 font-semibold">{{ row.present }}</TableCell>
              <TableCell class="text-center nums text-rose-700 font-semibold">{{ row.absent }}</TableCell>
              <TableCell>
                <div class="flex items-center gap-3">
                  <div class="flex-1 h-2 bg-[#efece4] rounded-full overflow-hidden">
                    <div
                      class="h-full rounded-full transition-all duration-700"
                      :style="{ width: row.rate + '%', background: row.rate >= 80 ? 'linear-gradient(90deg,#047857,#10b981)' : row.rate >= 60 ? 'linear-gradient(90deg,#b45309,#f59e0b)' : 'linear-gradient(90deg,#b91c1c,#ef4444)' }"
                    ></div>
                  </div>
                  <span class="text-[13px] font-bold w-10 text-right nums" :class="row.rate >= 80 ? 'text-emerald-700' : row.rate >= 60 ? 'text-amber-700' : 'text-rose-700'">
                    {{ row.rate }}%
                  </span>
                </div>
              </TableCell>
            </TableRow>
            <TableRow v-if="!dashboard.bySubject?.length">
              <TableCell colspan="5" class="text-center py-12 text-slate-400">{{ __('No attendance data yet.') }}</TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>

      <!-- Enrolled Classes -->
      <div class="card overflow-hidden animate-fade-in-up">
        <div class="section-head">
          <span class="section-tick"></span>
          <h3 class="section-title">{{ __('My Classes') }}</h3>
        </div>
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>{{ __('Class') }}</TableHead>
              <TableHead>{{ __('Section') }}</TableHead>
              <TableHead>{{ __('Academic Year') }}</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="c in dashboard.enrolledClasses" :key="c.id">
              <TableCell class="font-semibold text-slate-900">{{ c.name }}</TableCell>
              <TableCell>{{ c.section || '-' }}</TableCell>
              <TableCell><span class="badge badge-neutral">{{ c.academic_year }}</span></TableCell>
            </TableRow>
            <TableRow v-if="!dashboard.enrolledClasses?.length">
              <TableCell colspan="3" class="text-center py-8 text-slate-400">{{ __('Not enrolled in any classes.') }}</TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const { user, fetchUser } = useAuth()

const dashboard = ref<any>(null)
const loading = ref(true)

onMounted(async () => {
  await fetchUser()
  try {
    dashboard.value = await apiFetch('/student/dashboard')
  } catch {}
  loading.value = false
})

const { locale } = useI18n()
const dateLocale = computed(() => ({ en: 'en-US', km: 'km-KH', zh: 'zh-CN' } as Record<string, string>)[locale.value] ?? 'en-US')

const rate = computed(() => dashboard.value?.summary?.rate ?? 0)
const firstName = computed(() => user.value?.name?.split(' ')[0] ?? 'Student')

/* SVG donut ring */
const radius = 55
const circumference = 2 * Math.PI * radius
const dashOffset = computed(() => circumference - (rate.value / 100) * circumference)
const rateColor = computed(() => rate.value >= 80 ? '#10b981' : rate.value >= 60 ? '#f59e0b' : '#f43f5e')
</script>
