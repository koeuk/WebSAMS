<template>
  <div class="animate-fade-in">
    <div class="mb-7">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Announcements</h2>
      <p class="text-sm text-slate-500 mt-1">Important updates from your teachers</p>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">Loading...</div>

    <template v-else>
      <div v-if="!announcements.length" class="card p-14 text-center text-slate-400">
        <svg class="w-8 h-8 mx-auto mb-3 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
          <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.82a19.79 19.79 0 01-3.07-8.67A2 2 0 012 .18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/>
        </svg>
        No announcements yet.
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
                <span class="badge" :class="audienceConfig[a.audience] ?? ''">{{ a.audience }}</span>
              </div>
              <p class="text-[12px] text-slate-400">
                {{ a.author?.name }} · {{ formatDate(a.published_at) }}
                <span v-if="a.school_class"> · {{ a.school_class?.name }}</span>
              </p>
              <p v-if="expanded !== a.id" class="text-[13px] text-slate-500 mt-2 line-clamp-2">{{ a.body }}</p>
              <p v-else class="text-[13px] text-slate-600 mt-2 whitespace-pre-wrap leading-relaxed">{{ a.body }}</p>
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

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

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

const formatDate = (d: string) => d
  ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
  : ''
</script>
