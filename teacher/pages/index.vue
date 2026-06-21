<template>
  <div class="animate-fade-in">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('Dashboard') }}</h2>
        <p class="text-sm text-slate-500 mt-1">{{ __("Welcome back! Here's your overview for today.") }}</p>
      </div>
      <Popover :open="dayOpen" @update:open="(v: boolean) => dayOpen = v">
        <PopoverTrigger as-child>
          <button
            class="flex items-center gap-2 h-10 pl-3.5 pr-3 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:border-beltei/40 hover:bg-slate-50 transition-colors cursor-pointer"
          >
            <svg class="w-4 h-4 text-beltei" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            <span>{{ __(dayName(selectedDay)) }}</span>
            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="dayOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 9l-7 7-7-7"/></svg>
          </button>
        </PopoverTrigger>
        <PopoverContent class="w-44 p-1" align="end">
          <button
            v-for="d in days" :key="d"
            @click="selectDay(d)"
            class="w-full flex items-center justify-between gap-2 cursor-pointer px-3 py-2 text-sm rounded-md transition-colors"
            :class="d === selectedDay ? 'bg-beltei/5 text-beltei font-semibold' : 'text-slate-600 hover:bg-slate-50'"
          >
            <span class="flex items-center gap-2">
              {{ __(dayName(d)) }}
              <span v-if="d === today" class="text-[10px] font-bold uppercase tracking-wide text-emerald-600">{{ __('Today') }}</span>
            </span>
            <svg v-if="d === selectedDay" class="w-3.5 h-3.5 text-beltei" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg>
          </button>
        </PopoverContent>
      </Popover>
    </div>

    <div v-if="loading" class="card p-12 text-center">
      <div class="inline-flex items-center gap-2 text-sm text-slate-400">
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
        {{ __('Loading...') }}
      </div>
    </div>

    <div v-else>
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8 stagger-children">
        <div class="card stat-card stat-card-blue p-6 animate-fade-in-up">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-[13px] font-medium text-slate-500">{{ __('My Classes') }}</p>
              <p class="text-3xl font-bold text-slate-900 mt-1.5 tracking-tight">{{ dashboard?.stats?.totalClasses ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
          </div>
        </div>
        <div class="card stat-card stat-card-emerald p-6 animate-fade-in-up">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-[13px] font-medium text-slate-500">{{ __('Total Students') }}</p>
              <p class="text-3xl font-bold text-slate-900 mt-1.5 tracking-tight">{{ dashboard?.stats?.totalStudents ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center">
              <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            </div>
          </div>
        </div>
        <div class="card stat-card stat-card-violet p-6 animate-fade-in-up">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-[13px] font-medium text-slate-500">{{ __("Today's Attendance") }}</p>
              <p class="text-3xl font-bold mt-1.5 tracking-tight" :class="(dashboard?.stats?.todayAttendance ?? 0) >= 80 ? 'text-emerald-600' : 'text-rose-600'">
                {{ dashboard?.stats?.todayAttendance ?? 0 }}%
              </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-violet-50 flex items-center justify-center">
              <svg class="w-6 h-6 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            </div>
          </div>
        </div>
      </div>

      <div class="flex items-center gap-3 mb-4">
        <h3 class="text-base font-semibold text-slate-900">
          {{ selectedDay === today ? __("Today's Schedule") : __('Schedule') }}
        </h3>
        <span class="badge bg-slate-100 text-slate-600 ring-1 ring-slate-200">{{ __(dayName(selectedDay)) }}</span>
        <svg v-if="scheduleLoading" class="w-4 h-4 animate-spin text-slate-300" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
      </div>

      <div v-if="dashboard?.schedule?.length" class="grid gap-4 stagger-children">
        <div v-for="s in dashboard.schedule" :key="s.id" class="card card-interactive p-5 animate-fade-in-up cursor-default">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-2xl bg-beltei/5 flex items-center justify-center">
                <svg class="w-6 h-6 text-beltei" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/></svg>
              </div>
              <div>
                <p class="font-semibold text-slate-900">{{ s.class_subject?.subject?.name }}</p>
                <p class="text-[13px] text-slate-500 mt-0.5">{{ s.class_subject?.school_class?.name }}</p>
              </div>
            </div>
            <div class="text-right">
              <p class="text-sm font-semibold text-slate-900">{{ s.time_slot?.name }}</p>
              <p class="text-[12px] text-slate-400 mt-0.5">{{ s.time_slot?.start_time?.slice(0,5) }} - {{ s.time_slot?.end_time?.slice(0,5) }}</p>
              <p v-if="s.room" class="text-[12px] text-slate-400">{{ s.room }}</p>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="card p-12 text-center text-slate-400">
        {{ selectedDay === today ? __('No classes scheduled for today.') : __('No classes scheduled on {day}.', { day: __(dayName(selectedDay)) }) }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Popover, PopoverTrigger, PopoverContent } from '@/components/ui/popover'

definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const { fetchUser } = useAuth()

const dashboard = ref<any>(null)
const loading = ref(true)
const scheduleLoading = ref(false)

const days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun']
const dayCodeMap: Record<number, string> = { 0: 'sun', 1: 'mon', 2: 'tue', 3: 'wed', 4: 'thu', 5: 'fri', 6: 'sat' }
const today = dayCodeMap[new Date().getDay()]
const selectedDay = ref(today)
const dayOpen = ref(false)

const dayName = (code: string) => ({
  mon: 'Monday', tue: 'Tuesday', wed: 'Wednesday', thu: 'Thursday',
  fri: 'Friday', sat: 'Saturday', sun: 'Sunday',
} as Record<string, string>)[code] ?? code

const loadDashboard = async () => {
  try {
    dashboard.value = await apiFetch(`/teacher/dashboard?day=${selectedDay.value}`)
  } catch {}
}

const selectDay = async (code: string) => {
  dayOpen.value = false
  if (code === selectedDay.value) return
  selectedDay.value = code
  scheduleLoading.value = true
  await loadDashboard()
  scheduleLoading.value = false
}

onMounted(async () => {
  await fetchUser()
  await loadDashboard()
  loading.value = false
})
</script>
