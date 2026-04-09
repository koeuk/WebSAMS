<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const { fetchUser } = useAuth()

const dashboard = ref<any>(null)
const loading = ref(true)

onMounted(async () => {
  await fetchUser()
  try { dashboard.value = await apiFetch('/student/dashboard') } catch {}
  loading.value = false
})
</script>

<template>
  <div class="animate-fade-in">
    <div class="flex items-center justify-between mb-8">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Dashboard</h2>
        <p class="text-sm text-slate-500 mt-1">Your attendance overview and class information</p>
      </div>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">Loading...</div>

    <template v-else-if="dashboard">
      <!-- Summary Cards -->
      <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8 stagger-children">
        <div class="card stat-card stat-card-blue p-5 animate-fade-in-up">
          <p class="text-[13px] font-medium text-slate-500">Total</p>
          <p class="text-2xl font-bold text-slate-900 mt-1">{{ dashboard.summary?.total }}</p>
        </div>
        <div class="card stat-card stat-card-emerald p-5 animate-fade-in-up">
          <p class="text-[13px] font-medium text-slate-500">Present</p>
          <p class="text-2xl font-bold text-emerald-600 mt-1">{{ dashboard.summary?.present }}</p>
        </div>
        <div class="card stat-card stat-card-rose p-5 animate-fade-in-up">
          <p class="text-[13px] font-medium text-slate-500">Absent</p>
          <p class="text-2xl font-bold text-rose-600 mt-1">{{ dashboard.summary?.absent }}</p>
        </div>
        <div class="card stat-card stat-card-amber p-5 animate-fade-in-up">
          <p class="text-[13px] font-medium text-slate-500">Late</p>
          <p class="text-2xl font-bold text-amber-600 mt-1">{{ dashboard.summary?.late }}</p>
        </div>
        <div class="card stat-card stat-card-violet p-5 animate-fade-in-up">
          <p class="text-[13px] font-medium text-slate-500">Attendance Rate</p>
          <p class="text-2xl font-bold mt-1" :class="(dashboard.summary?.rate ?? 0) >= 80 ? 'text-emerald-600' : 'text-rose-600'">{{ dashboard.summary?.rate }}%</p>
        </div>
      </div>

      <!-- By Subject -->
      <div class="card overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-slate-100">
          <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider flex items-center gap-2">
            <div class="w-1.5 h-1.5 rounded-full bg-[#d4a017]"></div>
            By Subject
          </h3>
        </div>
        <table class="modern-table">
          <thead><tr><th class="text-left">Subject</th><th class="text-center">Total</th><th class="text-center">Present</th><th class="text-center">Absent</th><th class="text-center">Rate</th></tr></thead>
          <tbody>
            <tr v-for="row in dashboard.bySubject" :key="row.subject">
              <td class="font-semibold text-slate-900">{{ row.subject }}</td>
              <td class="text-center font-mono">{{ row.total }}</td>
              <td class="text-center font-mono text-emerald-600">{{ row.present }}</td>
              <td class="text-center font-mono text-rose-600">{{ row.absent }}</td>
              <td class="text-center font-bold" :class="row.rate >= 80 ? 'text-emerald-600' : 'text-rose-600'">{{ row.rate }}%</td>
            </tr>
            <tr v-if="!dashboard.bySubject?.length"><td colspan="5" class="!text-center !py-12 text-slate-400">No attendance data yet.</td></tr>
          </tbody>
        </table>
      </div>

      <!-- Enrolled Classes -->
      <div class="card overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
          <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider flex items-center gap-2">
            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
            My Classes
          </h3>
        </div>
        <table class="modern-table">
          <thead><tr><th class="text-left">Class</th><th class="text-left">Section</th><th class="text-left">Academic Year</th></tr></thead>
          <tbody>
            <tr v-for="c in dashboard.enrolledClasses" :key="c.id">
              <td class="font-semibold text-slate-900">{{ c.name }}</td>
              <td>{{ c.section || '-' }}</td>
              <td><span class="badge bg-slate-100 text-slate-600 ring-1 ring-slate-200">{{ c.academic_year }}</span></td>
            </tr>
            <tr v-if="!dashboard.enrolledClasses?.length"><td colspan="3" class="!text-center !py-8 text-slate-400">Not enrolled in any classes.</td></tr>
          </tbody>
        </table>
      </div>
    </template>
  </div>
</template>
