<template>
  <div class="animate-fade-in">
    <div class="mb-7">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('Announcements') }}</h2>
      <p class="text-sm text-slate-500 mt-1">{{ __('Important updates from your teachers') }}</p>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">{{ __('Loading...') }}</div>

    <template v-else>
      <div v-if="!announcements.length" class="card overflow-hidden">
        <Empty class="border-0 rounded-none">
          <EmptyHeader>
            <EmptyMedia variant="icon"><Megaphone class="w-6 h-6" /></EmptyMedia>
            <EmptyTitle>{{ __('No announcements yet') }}</EmptyTitle>
            <EmptyDescription>{{ __('Check back later for updates from your teachers.') }}</EmptyDescription>
          </EmptyHeader>
        </Empty>
      </div>

      <div class="space-y-4 stagger-children">
        <div
          v-for="a in announcements" :key="a.id"
          class="card p-5 cursor-pointer hover:shadow-md transition-shadow animate-fade-in-up"
          @click="expanded = expanded === a.id ? null : a.id"
        >
          <div class="flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 mb-1 flex-wrap">
                <h3 class="font-semibold text-slate-900">{{ a.title }}</h3>
                <span class="badge" :class="audienceConfig[a.audience] ?? ''">{{ __(a.audience) }}</span>
              </div>
              <p class="text-[12px] text-slate-400">
                {{ a.author?.name }} · {{ formatDate(a.published_at) }}
                <span v-if="a.school_class"> · {{ a.school_class?.name }}</span>
              </p>
              <div v-if="expanded !== a.id" class="text-[13px] text-slate-500 mt-2 line-clamp-2 prose prose-sm max-w-none" v-html="a.body"></div>
              <div v-else class="text-[13px] text-slate-600 mt-2 prose prose-sm max-w-none" v-html="a.body"></div>
            </div>
            <svg
              class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200"
              :class="expanded === a.id ? 'rotate-180' : ''"
              fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
            ><path d="M19 9l-7 7-7-7"/></svg>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Megaphone } from 'lucide-vue-next'

definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const data = ref<any>({ data: [] })
const loading = ref(true)
const expanded = ref<number | null>(null)

onMounted(async () => {
  try { data.value = await apiFetch('/student/announcements') } catch {}
  loading.value = false
})

const announcements = computed(() => data.value?.data ?? [])

const audienceConfig: Record<string, string> = {
  all:      'bg-violet-50 text-violet-700 ring-1 ring-violet-200',
  students: 'bg-sky-50 text-sky-700 ring-1 ring-sky-200',
  teachers: 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
}

const { locale } = useI18n()
const dateLocale = computed(() => ({ en: 'en-US', km: 'km-KH', zh: 'zh-CN' } as Record<string, string>)[locale.value] ?? 'en-US')
const formatDate = (d: string) => d
  ? new Date(d).toLocaleDateString(dateLocale.value, { year: 'numeric', month: 'long', day: 'numeric' })
  : ''
</script>
