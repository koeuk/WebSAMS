<template>
  <div class="animate-fade-in">
    <div class="flex items-center justify-between mb-7">
      <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Notifications</h2>
        <p class="text-sm text-slate-500 mt-1">
          <span v-if="unreadCount" class="font-semibold text-slate-700">{{ unreadCount }}</span>
          <span v-if="unreadCount"> unread · </span>
          {{ notifications.length }} total
        </p>
      </div>
      <button v-if="unreadCount" @click="markAllRead" class="btn-secondary py-2 text-[13px]">
        Mark all read
      </button>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">Loading...</div>

    <div v-else class="card overflow-hidden">
      <div v-if="!notifications.length" class="p-14 text-center text-slate-400">
        <svg class="w-8 h-8 mx-auto mb-3 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
          <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>
        </svg>
        No notifications yet.
      </div>
      <div
        v-for="n in notifications" :key="n.id"
        class="flex items-start gap-4 px-5 py-4 border-b border-slate-50 last:border-0 cursor-pointer hover:bg-slate-50/70 transition-colors"
        :class="!n.is_read ? 'bg-blue-50/30' : ''"
        @click="markRead(n)"
      >
        <div class="shrink-0 mt-0.5">
          <span class="w-2 h-2 rounded-full mt-1.5 block" :class="n.is_read ? 'bg-slate-200' : 'bg-blue-500'"></span>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-[13px] text-slate-800 font-medium leading-snug">{{ n.message }}</p>
          <p class="text-[11px] text-slate-400 mt-1">{{ formatDate(n.created_at) }}</p>
        </div>
        <span v-if="n.type" class="badge shrink-0" :class="typeConfig[n.type] ?? typeConfig.info">{{ n.type }}</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const data = ref<any>({ data: [] })
const loading = ref(true)

onMounted(async () => {
  try { data.value = await apiFetch('/teacher/notifications') } catch {}
  loading.value = false
})

const notifications = computed(() => data.value?.data ?? [])
const unreadCount = computed(() => notifications.value.filter((n: any) => !n.is_read).length)

const markRead = async (n: any) => {
  if (n.is_read) return
  try {
    await apiFetch(`/teacher/notifications/${n.id}/read`, { method: 'PUT' })
    n.is_read = true
  } catch {}
}

const markAllRead = async () => {
  try {
    await apiFetch('/teacher/notifications/read-all', { method: 'POST' })
    notifications.value.forEach((n: any) => { n.is_read = true })
  } catch {}
}

const typeConfig: Record<string, string> = {
  absent:  'bg-rose-50 text-rose-700 ring-1 ring-rose-200',
  late:    'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
  excused: 'bg-sky-50 text-sky-700 ring-1 ring-sky-200',
  info:    'bg-slate-100 text-slate-600 ring-1 ring-slate-200',
}

const formatDate = (d: string) => d ? new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : ''
</script>
