<template>
  <div class="animate-fade-in">
    <div class="mb-7">
      <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-beltei-gold mb-1.5">{{ __('Alerts') }}</p>
      <h2 class="page-title text-[28px] sm:text-[32px]">{{ __('Notifications') }}</h2>
      <div class="gold-rule mt-3"></div>
      <p class="text-sm text-slate-500 mt-3">{{ __('Stay updated with your attendance alerts') }}</p>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">{{ __('Loading...') }}</div>

    <div v-else class="space-y-3 stagger-children">
      <div v-for="n in notifications.data" :key="n.id"
        class="card card-hover p-5 pl-6 animate-fade-in-up overflow-hidden"
        :class="n.is_read ? 'opacity-65' : ''"
      >
        <span v-if="!n.is_read" class="absolute left-0 top-0 bottom-0 w-1" style="background: linear-gradient(180deg, #d8b53a, #a8870f);"></span>
        <div class="flex items-start justify-between gap-3">
          <div>
            <div class="flex items-center gap-2 mb-1.5">
              <span class="badge" :class="typeBadgeClass(n.type)">{{ __(n.type) }}</span>
              <h3 class="text-[13px] font-semibold text-slate-900">{{ n.title }}</h3>
              <span v-if="!n.is_read" class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></span>
            </div>
            <p class="text-[13px] text-slate-600">{{ n.message }}</p>
            <p class="text-[12px] text-slate-400 mt-1.5">{{ formatDate(n.created_at) }}</p>
          </div>
          <button v-if="!n.is_read" @click="markAsRead(n.id)" class="cursor-pointer px-3 py-1.5 text-[12px] font-semibold text-beltei hover:bg-accent rounded-lg transition-colors whitespace-nowrap">
            {{ __('Mark read') }}
          </button>
        </div>
      </div>

      <div v-if="!notifications.data?.length" class="card overflow-hidden">
        <Empty class="border-0 rounded-none">
          <EmptyHeader>
            <EmptyMedia variant="icon"><Bell class="w-6 h-6" /></EmptyMedia>
            <EmptyTitle>{{ __('No notifications') }}</EmptyTitle>
            <EmptyDescription>{{ __("You're all caught up.") }}</EmptyDescription>
          </EmptyHeader>
        </Empty>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Bell } from 'lucide-vue-next'

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

const { locale } = useI18n()
const dateLocale = computed(() => ({ en: 'en-US', km: 'km-KH', zh: 'zh-CN' } as Record<string, string>)[locale.value] ?? 'en-US')
const formatDate = (d: string) => d ? new Date(d).toLocaleDateString(dateLocale.value, { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-'

const typeBadgeClass = (type: string) => ({
  'bg-rose-50 text-rose-700 ring-1 ring-rose-200': type === 'absence',
  'bg-amber-50 text-amber-700 ring-1 ring-amber-200': type === 'late',
  'bg-slate-100 text-slate-600 ring-1 ring-slate-200': type === 'general',
})
</script>
