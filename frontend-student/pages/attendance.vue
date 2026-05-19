<template>
  <div class="animate-fade-in">
    <div class="mb-7">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">My Attendance</h2>
      <p class="text-sm text-slate-500 mt-1">View and filter your attendance records</p>
    </div>

    <!-- Filters -->
    <div class="card p-4 mb-5">
      <div class="flex flex-wrap gap-3 items-end">
        <div>
          <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">From</label>
          <DatePicker v-model="dateFrom" placeholder="Start date" @update:model-value="loadAttendance" />
        </div>
        <div>
          <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">To</label>
          <DatePicker v-model="dateTo" placeholder="End date" @update:model-value="loadAttendance" />
        </div>
        <button
          v-if="dateFrom || dateTo"
          @click="dateFrom = ''; dateTo = ''; loadAttendance()"
          class="btn-secondary py-2.5 text-[13px]"
        >
          Clear
        </button>
      </div>
    </div>

    <!-- Mini stats (shown when data exists) -->
    <div v-if="!loading && stats.total > 0" class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5 stagger-children">
      <div class="card stat-card stat-card-blue px-4 py-3 animate-fade-in-up">
        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Total</p>
        <p class="text-xl font-extrabold text-slate-900 mt-1">{{ stats.total }}</p>
      </div>
      <div class="card stat-card stat-card-emerald px-4 py-3 animate-fade-in-up">
        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Present</p>
        <p class="text-xl font-extrabold text-emerald-600 mt-1">{{ stats.present }}</p>
      </div>
      <div class="card stat-card stat-card-rose px-4 py-3 animate-fade-in-up">
        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Absent</p>
        <p class="text-xl font-extrabold text-rose-600 mt-1">{{ stats.absent }}</p>
      </div>
      <div class="card stat-card stat-card-amber px-4 py-3 animate-fade-in-up">
        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Rate</p>
        <p class="text-xl font-extrabold mt-1" :class="stats.rate >= 80 ? 'text-emerald-600' : 'text-rose-600'">
          {{ stats.rate }}%
        </p>
      </div>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">Loading...</div>

    <div v-else class="card overflow-hidden">
      <table class="modern-table">
        <thead>
          <tr>
            <th class="text-left">Date</th>
            <th class="text-left">Time Slot</th>
            <th class="text-left">Class</th>
            <th class="text-left">Subject</th>
            <th class="text-left">Status</th>
            <th class="text-left">Remarks</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="record in records" :key="record.id">
            <td class="font-semibold text-slate-900">{{ record.date }}</td>
            <td class="text-slate-500">{{ record.time_slot?.name || '-' }}</td>
            <td>{{ record.class_subject?.school_class?.name }}</td>
            <td>{{ record.class_subject?.subject?.name }}</td>
            <td>
              <span class="badge" :class="statusConfig[record.status] ?? ''">{{ record.status }}</span>
            </td>
            <td class="text-slate-500">{{ record.remarks || '-' }}</td>
          </tr>
          <tr v-if="!records.length">
            <td colspan="6" class="!text-center !py-14 text-slate-400">
              <svg class="w-8 h-8 mx-auto mb-3 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              No attendance records found.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()

const attendance = ref<any>({ data: [] })
const dateFrom = ref('')
const dateTo = ref('')
const loading = ref(true)

onMounted(async () => {
  await loadAttendance()
  loading.value = false
})

const loadAttendance = async () => {
  const params = new URLSearchParams()
  if (dateFrom.value) params.set('filter[date_from]', dateFrom.value)
  if (dateTo.value) params.set('filter[date_to]', dateTo.value)
  try { attendance.value = await apiFetch(`/student/attendance?${params.toString()}`) } catch {}
}

const records = computed(() => attendance.value?.data ?? [])

const stats = computed(() => {
  const data = records.value
  const total = data.length
  const present = data.filter((r: any) => r.status === 'present').length
  const absent = data.filter((r: any) => r.status === 'absent').length
  const late = data.filter((r: any) => r.status === 'late').length
  const rate = total ? Math.round((present / total) * 100) : 0
  return { total, present, absent, late, rate }
})

const statusConfig: Record<string, string> = {
  present: 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
  absent:  'bg-rose-50 text-rose-700 ring-1 ring-rose-200',
  late:    'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
  excused: 'bg-sky-50 text-sky-700 ring-1 ring-sky-200',
}
</script>
