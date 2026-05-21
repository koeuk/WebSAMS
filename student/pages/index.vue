<template>
  <div class="animate-fade-in">

    <!-- Header -->
    <div class="flex items-start justify-between mb-7">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
          {{ __('Good day, {name} 👋', { name: firstName }) }}
        </h2>
        <p class="text-sm text-slate-500 mt-1">{{ __("Here's your attendance overview.") }}</p>
      </div>
      <div class="text-xs font-medium text-slate-500 bg-white px-4 py-2.5 rounded-xl border border-slate-200 shadow-sm hidden sm:block">
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

    <template v-else-if="dashboard">

      <!-- Top row: rate ring + stat cards -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

        <!-- Attendance rate ring -->
        <div class="card p-6 flex items-center gap-6 lg:col-span-1">
          <div class="relative shrink-0">
            <svg width="128" height="128" viewBox="0 0 128 128" class="-rotate-90">
              <circle cx="64" cy="64" :r="radius" fill="none" stroke="#f1f5f9" stroke-width="10" />
              <circle
                cx="64" cy="64" :r="radius" fill="none"
                :stroke="rateColor"
                stroke-width="10"
                stroke-linecap="round"
                :stroke-dasharray="circumference"
                :stroke-dashoffset="dashOffset"
                style="transition: stroke-dashoffset 0.8s ease, stroke 0.4s ease;"
              />
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
              <span class="text-2xl font-extrabold text-slate-900 leading-none">{{ rate }}%</span>
              <span class="text-[10px] text-slate-400 mt-0.5 uppercase tracking-wide">{{ __('Rate') }}</span>
            </div>
          </div>
          <div>
            <p class="text-sm font-semibold text-slate-700 mb-3">{{ __('Attendance Rate') }}</p>
            <div class="space-y-1.5">
              <div class="flex items-center gap-2 text-[13px]">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span class="text-slate-500">{{ __('Present') }}</span>
                <span class="ml-auto font-semibold text-slate-800">{{ dashboard.summary?.present }}</span>
              </div>
              <div class="flex items-center gap-2 text-[13px]">
                <span class="w-2 h-2 rounded-full bg-rose-400"></span>
                <span class="text-slate-500">{{ __('Absent') }}</span>
                <span class="ml-auto font-semibold text-slate-800">{{ dashboard.summary?.absent }}</span>
              </div>
              <div class="flex items-center gap-2 text-[13px]">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                <span class="text-slate-500">{{ __('Late') }}</span>
                <span class="ml-auto font-semibold text-slate-800">{{ dashboard.summary?.late }}</span>
              </div>
              <div class="flex items-center gap-2 text-[13px] pt-1 border-t border-slate-100">
                <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                <span class="text-slate-500">{{ __('Total') }}</span>
                <span class="ml-auto font-semibold text-slate-800">{{ dashboard.summary?.total }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- 4 mini stat cards stacked 2x2 -->
        <div class="lg:col-span-2 grid grid-cols-2 gap-4">
          <div class="card stat-card stat-card-blue p-5 animate-fade-in-up">
            <div class="flex items-center justify-between mb-3">
              <p class="text-[12px] font-semibold text-slate-500 uppercase tracking-wide">{{ __('Total') }}</p>
              <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-4.5 h-4.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
              </div>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ dashboard.summary?.total }}</p>
          </div>
          <div class="card stat-card stat-card-emerald p-5 animate-fade-in-up">
            <div class="flex items-center justify-between mb-3">
              <p class="text-[12px] font-semibold text-slate-500 uppercase tracking-wide">{{ __('Present') }}</p>
              <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                <svg class="w-4.5 h-4.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path d="M20 6L9 17l-5-5"/></svg>
              </div>
            </div>
            <p class="text-3xl font-extrabold text-emerald-600 tracking-tight">{{ dashboard.summary?.present }}</p>
          </div>
          <div class="card stat-card stat-card-rose p-5 animate-fade-in-up">
            <div class="flex items-center justify-between mb-3">
              <p class="text-[12px] font-semibold text-slate-500 uppercase tracking-wide">{{ __('Absent') }}</p>
              <div class="w-9 h-9 rounded-xl bg-rose-50 flex items-center justify-center">
                <svg class="w-4.5 h-4.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </div>
            </div>
            <p class="text-3xl font-extrabold text-rose-600 tracking-tight">{{ dashboard.summary?.absent }}</p>
          </div>
          <div class="card stat-card stat-card-amber p-5 animate-fade-in-up">
            <div class="flex items-center justify-between mb-3">
              <p class="text-[12px] font-semibold text-slate-500 uppercase tracking-wide">{{ __('Late') }}</p>
              <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                <svg class="w-4.5 h-4.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              </div>
            </div>
            <p class="text-3xl font-extrabold text-amber-600 tracking-tight">{{ dashboard.summary?.late }}</p>
          </div>
        </div>
      </div>

      <!-- By Subject -->
      <div class="card overflow-hidden mb-5">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
          <span class="w-1.5 h-1.5 rounded-full" style="background:#d4a017;"></span>
          <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">{{ __('Attendance by Subject') }}</h3>
        </div>
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>{{ __('Subject') }}</TableHead>
              <TableHead class="text-center">{{ __('Total') }}</TableHead>
              <TableHead class="text-center">{{ __('Present') }}</TableHead>
              <TableHead class="text-center">{{ __('Absent') }}</TableHead>
              <TableHead style="min-width:140px;">{{ __('Rate') }}</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="row in dashboard.bySubject" :key="row.subject">
              <TableCell class="font-semibold text-slate-900">{{ row.subject }}</TableCell>
              <TableCell class="text-center font-mono text-slate-600">{{ row.total }}</TableCell>
              <TableCell class="text-center font-mono text-emerald-600 font-semibold">{{ row.present }}</TableCell>
              <TableCell class="text-center font-mono text-rose-600 font-semibold">{{ row.absent }}</TableCell>
              <TableCell>
                <div class="flex items-center gap-2.5">
                  <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div
                      class="h-full rounded-full transition-all duration-700"
                      :style="{ width: row.rate + '%', background: row.rate >= 80 ? '#10b981' : row.rate >= 60 ? '#f59e0b' : '#f43f5e' }"
                    ></div>
                  </div>
                  <span class="text-[13px] font-bold w-10 text-right" :class="row.rate >= 80 ? 'text-emerald-600' : row.rate >= 60 ? 'text-amber-600' : 'text-rose-600'">
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
      <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
          <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
          <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">{{ __('My Classes') }}</h3>
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
              <TableCell><span class="badge bg-slate-100 text-slate-600 ring-1 ring-slate-200">{{ c.academic_year }}</span></TableCell>
            </TableRow>
            <TableRow v-if="!dashboard.enrolledClasses?.length">
              <TableCell colspan="3" class="text-center py-8 text-slate-400">{{ __('Not enrolled in any classes.') }}</TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>
    </template>
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
const radius = 54
const circumference = 2 * Math.PI * radius
const dashOffset = computed(() => circumference - (rate.value / 100) * circumference)
const rateColor = computed(() => rate.value >= 80 ? '#10b981' : rate.value >= 60 ? '#f59e0b' : '#f43f5e')
</script>
