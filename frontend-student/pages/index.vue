<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const { fetchUser } = useAuth()

const dashboard = ref<any>(null)
const loading = ref(true)

onMounted(async () => {
  await fetchUser()
  try {
    dashboard.value = await apiFetch('/student/dashboard')
  } catch {}
  loading.value = false
})
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Dashboard</h2>

    <div v-if="loading" class="text-sm text-gray-500">Loading...</div>

    <template v-else-if="dashboard">
      <!-- Overall Summary -->
      <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
          <p class="text-sm text-gray-600">Total</p>
          <p class="text-2xl font-bold text-gray-900">{{ dashboard.summary?.total }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
          <p class="text-sm text-gray-600">Present</p>
          <p class="text-2xl font-bold text-green-600">{{ dashboard.summary?.present }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
          <p class="text-sm text-gray-600">Absent</p>
          <p class="text-2xl font-bold text-red-600">{{ dashboard.summary?.absent }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
          <p class="text-sm text-gray-600">Late</p>
          <p class="text-2xl font-bold text-yellow-600">{{ dashboard.summary?.late }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4 text-center">
          <p class="text-sm text-gray-600">Attendance Rate</p>
          <p class="text-2xl font-bold" :class="(dashboard.summary?.rate ?? 0) >= 80 ? 'text-green-600' : 'text-red-600'">
            {{ dashboard.summary?.rate }}%
          </p>
        </div>
      </div>

      <!-- By Subject -->
      <h3 class="text-lg font-semibold text-gray-900 mb-3">By Subject</h3>
      <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-200 bg-gray-50">
              <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Subject</th>
              <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Total</th>
              <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Present</th>
              <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Absent</th>
              <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Rate</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="row in dashboard.bySubject" :key="row.subject" class="border-b border-gray-100">
              <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ row.subject }}</td>
              <td class="px-6 py-3 text-sm text-gray-600 text-center">{{ row.total }}</td>
              <td class="px-6 py-3 text-sm text-green-600 text-center">{{ row.present }}</td>
              <td class="px-6 py-3 text-sm text-red-600 text-center">{{ row.absent }}</td>
              <td class="px-6 py-3 text-sm font-medium text-center" :class="row.rate >= 80 ? 'text-green-600' : 'text-red-600'">
                {{ row.rate }}%
              </td>
            </tr>
            <tr v-if="!dashboard.bySubject?.length">
              <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No attendance data yet.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </template>
  </div>
</template>
