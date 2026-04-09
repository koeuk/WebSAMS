<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const { fetchUser } = useAuth()

const dashboard = ref<any>(null)
const loading = ref(true)

onMounted(async () => {
  await fetchUser()
  try {
    dashboard.value = await apiFetch('/teacher/dashboard')
  } catch {}
  loading.value = false
})

const days: Record<string, string> = { mon: 'Monday', tue: 'Tuesday', wed: 'Wednesday', thu: 'Thursday', fri: 'Friday', sat: 'Saturday', sun: 'Sunday' }
</script>

<template>
  <div class="animate-fade-in">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard</h2>
        <p class="text-sm text-slate-500 mt-1">Welcome back! Here's your overview for today.</p>
      </div>
      <div class="text-sm text-slate-400 font-medium">
        {{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
      </div>
    </div>

    <div v-if="loading" class="card p-12 text-center">
      <div class="inline-flex items-center gap-2 text-sm text-slate-400">
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
        Loading...
      </div>
    </div>

    <template v-else>
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8 stagger-children">
        <div class="card stat-card stat-card-blue p-6 animate-fade-in-up">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-[13px] font-medium text-slate-500">My Classes</p>
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
              <p class="text-[13px] font-medium text-slate-500">Total Students</p>
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
              <p class="text-[13px] font-medium text-slate-500">Today's Attendance</p>
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
        <h3 class="text-base font-semibold text-slate-900">Today's Schedule</h3>
        <span class="badge bg-slate-100 text-slate-600 ring-1 ring-slate-200">{{ days[dashboard?.today] || 'No classes today' }}</span>
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
        No classes scheduled for today.
      </div>
    </template>
  </div>
</template>
