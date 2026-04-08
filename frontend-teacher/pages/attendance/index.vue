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
  'bg-green-100 text-green-800': status === 'present',
  'bg-red-100 text-red-800': status === 'absent',
  'bg-yellow-100 text-yellow-800': status === 'late',
  'bg-blue-100 text-blue-800': status === 'excused',
})
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Attendance History</h2>

    <div class="flex flex-wrap gap-3 mb-4">
      <select v-model="selectedClass" class="px-3 py-2 border border-gray-300 rounded-md text-sm" @change="loadAttendance">
        <option value="">All Classes</option>
        <option v-for="c in classes" :key="c.id" :value="c.id">{{ c.school_class?.name }} - {{ c.subject?.name }}</option>
      </select>
      <input v-model="dateFrom" type="date" class="px-3 py-2 border border-gray-300 rounded-md text-sm" @change="loadAttendance" />
      <input v-model="dateTo" type="date" class="px-3 py-2 border border-gray-300 rounded-md text-sm" @change="loadAttendance" />
    </div>

    <div v-if="loading" class="text-sm text-gray-500">Loading...</div>

    <div v-else class="bg-white rounded-lg border border-gray-200 overflow-hidden">
      <table class="w-full">
        <thead>
          <tr class="border-b border-gray-200 bg-gray-50">
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Time Slot</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Student</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Class</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Subject</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="record in attendance.data" :key="record.id" class="border-b border-gray-100">
            <td class="px-6 py-3 text-sm text-gray-900">{{ record.date }}</td>
            <td class="px-6 py-3 text-sm text-gray-600">{{ record.time_slot?.name || '-' }}</td>
            <td class="px-6 py-3 text-sm text-gray-900">{{ record.student?.name }}</td>
            <td class="px-6 py-3 text-sm text-gray-600">{{ record.class_subject?.school_class?.name }}</td>
            <td class="px-6 py-3 text-sm text-gray-600">{{ record.class_subject?.subject?.name }}</td>
            <td class="px-6 py-3">
              <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full" :class="statusClass(record.status)">{{ record.status }}</span>
            </td>
          </tr>
          <tr v-if="!attendance.data?.length">
            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">No attendance records found.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
