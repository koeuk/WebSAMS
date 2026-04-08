<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const notifications = ref<any>({ data: [] })
const loading = ref(true)

onMounted(async () => {
  try {
    notifications.value = await apiFetch('/student/notifications')
  } catch {}
  loading.value = false
})

const markAsRead = async (id: number) => {
  try {
    await apiFetch(`/student/notifications/${id}/read`, { method: 'PUT' })
    const n = notifications.value.data.find((n: any) => n.id === id)
    if (n) n.is_read = true
  } catch {}
}

const formatDate = (d: string) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-'

const typeBadge = (type: string) => ({
  'bg-red-100 text-red-800': type === 'absence',
  'bg-yellow-100 text-yellow-800': type === 'late',
  'bg-gray-100 text-gray-800': type === 'general',
})
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Notifications</h2>

    <div v-if="loading" class="text-sm text-gray-500">Loading...</div>

    <div v-else class="space-y-3">
      <div v-for="n in notifications.data" :key="n.id"
        class="bg-white rounded-lg border border-gray-200 p-4"
        :class="n.is_read ? 'opacity-60' : ''"
      >
        <div class="flex items-start justify-between">
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full" :class="typeBadge(n.type)">{{ n.type }}</span>
              <h3 class="text-sm font-semibold text-gray-900">{{ n.title }}</h3>
            </div>
            <p class="text-sm text-gray-600">{{ n.message }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ formatDate(n.created_at) }}</p>
          </div>
          <button v-if="!n.is_read" @click="markAsRead(n.id)" class="text-xs text-blue-600 hover:text-blue-800 whitespace-nowrap">
            Mark read
          </button>
        </div>
      </div>

      <div v-if="!notifications.data?.length" class="bg-white rounded-lg border border-gray-200 p-8 text-center text-sm text-gray-500">
        No notifications.
      </div>
    </div>
  </div>
</template>
