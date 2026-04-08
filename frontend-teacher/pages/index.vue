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
  <div>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Dashboard</h2>

    <div v-if="loading" class="text-sm text-gray-500">Loading...</div>

    <template v-else>
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
          <p class="text-sm text-gray-600">My Classes</p>
          <p class="text-2xl font-bold text-gray-900">{{ dashboard?.stats?.totalClasses ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
          <p class="text-sm text-gray-600">Total Students</p>
          <p class="text-2xl font-bold text-gray-900">{{ dashboard?.stats?.totalStudents ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
          <p class="text-sm text-gray-600">Today's Attendance</p>
          <p class="text-2xl font-bold" :class="(dashboard?.stats?.todayAttendance ?? 0) >= 80 ? 'text-green-600' : 'text-red-600'">
            {{ dashboard?.stats?.todayAttendance ?? 0 }}%
          </p>
        </div>
      </div>

      <h3 class="text-lg font-semibold text-gray-900 mb-3">
        Today's Schedule ({{ days[dashboard?.today] || 'No classes today' }})
      </h3>

      <div v-if="dashboard?.schedule?.length" class="grid gap-4">
        <div v-for="s in dashboard.schedule" :key="s.id" class="bg-white rounded-lg border border-gray-200 p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="font-medium text-gray-900">{{ s.class_subject?.subject?.name }}</p>
              <p class="text-sm text-gray-600">{{ s.class_subject?.school_class?.name }}</p>
            </div>
            <div class="text-right">
              <p class="text-sm font-medium text-gray-900">{{ s.time_slot?.name }}</p>
              <p class="text-xs text-gray-500">{{ s.time_slot?.start_time?.slice(0,5) }} - {{ s.time_slot?.end_time?.slice(0,5) }}</p>
              <p class="text-sm text-gray-600">{{ s.room || 'No room' }}</p>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="bg-white rounded-lg border border-gray-200 p-8 text-center text-sm text-gray-500">
        No classes scheduled for today.
      </div>
    </template>
  </div>
</template>
