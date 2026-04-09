<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const notifications = ref<any>({ data: [] })
const loading = ref(true)

onMounted(async () => {
  try { notifications.value = await apiFetch('/student/notifications') } catch {}
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

const typeBadgeClass = (type: string) => ({
  'bg-rose-50 text-rose-700 ring-1 ring-rose-200': type === 'absence',
  'bg-amber-50 text-amber-700 ring-1 ring-amber-200': type === 'late',
  'bg-slate-100 text-slate-600 ring-1 ring-slate-200': type === 'general',
})
</script>

<template>
  <div class="animate-fade-in">
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Notifications</h2>
      <p class="text-sm text-slate-500 mt-1">Stay updated with your attendance alerts</p>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">Loading...</div>

    <div v-else class="space-y-3 stagger-children">
      <div v-for="n in notifications.data" :key="n.id"
        class="card p-5 animate-fade-in-up transition-opacity"
        :class="n.is_read ? 'opacity-60' : ''"
      >
        <div class="flex items-start justify-between">
          <div>
            <div class="flex items-center gap-2 mb-1.5">
              <span class="badge" :class="typeBadgeClass(n.type)">{{ n.type }}</span>
              <h3 class="text-[13px] font-semibold text-slate-900">{{ n.title }}</h3>
            </div>
            <p class="text-[13px] text-slate-600">{{ n.message }}</p>
            <p class="text-[12px] text-slate-400 mt-1.5">{{ formatDate(n.created_at) }}</p>
          </div>
          <button v-if="!n.is_read" @click="markAsRead(n.id)" class="px-3 py-1.5 text-[12px] font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors whitespace-nowrap">
            Mark read
          </button>
        </div>
      </div>

      <div v-if="!notifications.data?.length" class="card p-12 text-center text-slate-400">
        No notifications.
      </div>
    </div>
  </div>
</template>
