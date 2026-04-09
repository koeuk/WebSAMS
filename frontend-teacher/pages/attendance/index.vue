<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()

const classes = ref<any[]>([])
const attendance = ref<any>({ data: [] })
const selectedClass = ref('')
const dateFrom = ref('')
const dateTo = ref('')
const loading = ref(true)

onMounted(async () => {
  try {
    classes.value = await apiFetch('/teacher/classes')
    await loadAttendance()
  } catch {}
  loading.value = false
})

const loadAttendance = async () => {
  const params = new URLSearchParams()
  if (selectedClass.value) params.set('class_subject_id', selectedClass.value)
  if (dateFrom.value) params.set('date_from', dateFrom.value)
  if (dateTo.value) params.set('date_to', dateTo.value)
  try {
    attendance.value = await apiFetch(`/teacher/attendance?${params.toString()}`)
  } catch {}
}

const statusClass = (status: string) => ({
  'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200': status === 'present',
  'bg-rose-50 text-rose-700 ring-1 ring-rose-200': status === 'absent',
  'bg-amber-50 text-amber-700 ring-1 ring-amber-200': status === 'late',
  'bg-sky-50 text-sky-700 ring-1 ring-sky-200': status === 'excused',
})
</script>

<template>
  <div class="animate-fade-in">
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Attendance History</h2>
      <p class="text-sm text-slate-500 mt-1">View past attendance records for your classes</p>
    </div>

    <div class="card p-4 mb-6">
      <div class="flex flex-wrap gap-3 items-end">
        <div>
          <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Class</label>
          <select v-model="selectedClass" class="select-modern" @change="loadAttendance">
            <option value="">All Classes</option>
            <option v-for="c in classes" :key="c.id" :value="c.id">{{ c.school_class?.name }} - {{ c.subject?.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">From</label>
          <input v-model="dateFrom" type="date" class="input-modern" @change="loadAttendance" />
        </div>
        <div>
          <label class="block text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">To</label>
          <input v-model="dateTo" type="date" class="input-modern" @change="loadAttendance" />
        </div>
      </div>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">Loading...</div>

    <div v-else class="card overflow-hidden">
      <table class="modern-table">
        <thead>
          <tr>
            <th class="text-left">Date</th>
            <th class="text-left">Time Slot</th>
            <th class="text-left">Student</th>
            <th class="text-left">Class</th>
            <th class="text-left">Subject</th>
            <th class="text-left">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="record in attendance.data" :key="record.id">
            <td class="font-medium text-slate-900">{{ record.date }}</td>
            <td>{{ record.time_slot?.name || '-' }}</td>
            <td class="font-semibold text-slate-900">{{ record.student?.name }}</td>
            <td>{{ record.class_subject?.school_class?.name }}</td>
            <td>{{ record.class_subject?.subject?.name }}</td>
            <td><span class="badge" :class="statusClass(record.status)">{{ record.status }}</span></td>
          </tr>
          <tr v-if="!attendance.data?.length">
            <td colspan="6" class="!text-center !py-12 text-slate-400">No attendance records found.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
