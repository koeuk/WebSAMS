<template>
  <div class="animate-fade-in">

    <!-- Header -->
    <div class="flex items-center gap-4 mb-7">
      <NuxtLink to="/classes" class="flex items-center gap-1.5 text-sm font-medium text-slate-400 hover:text-slate-600 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        {{ __('Classes') }}
      </NuxtLink>
      <div class="h-4 w-px bg-slate-200"></div>
      <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('Student List') }}</h2>
        <p class="text-sm text-slate-500 mt-0.5">
          {{ __('{n} students enrolled', { n: students.length }) }}
        </p>
      </div>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">{{ __('Loading...') }}</div>

    <template v-else>
      <!-- Search -->
      <div class="card p-4 mb-5" v-if="students.length">
        <div class="relative max-w-xs">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
          </svg>
          <Input
            v-model="search"
            type="text"
            :placeholder="__('Search students...')"
            class="pl-9"
          />
        </div>
      </div>

      <!-- Table -->
      <div class="card overflow-hidden">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead class="w-12">#</TableHead>
              <TableHead>{{ __('Student') }}</TableHead>
              <TableHead>{{ __('Email') }}</TableHead>
              <TableHead>{{ __('ID Number') }}</TableHead>
              <TableHead>{{ __('Year') }}</TableHead>
              <TableHead>{{ __('Gender') }}</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="(student, index) in filtered" :key="student.id">
              <TableCell class="text-slate-400 font-mono text-[13px]">{{ index + 1 }}</TableCell>
              <TableCell>
                <div class="flex items-center gap-3">
                  <div
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0"
                    style="background: linear-gradient(135deg, #1e3a6e, #2a4f8f);"
                  >
                    {{ student.name?.charAt(0) }}
                  </div>
                  <span class="font-semibold text-slate-900">{{ student.name }}</span>
                </div>
              </TableCell>
              <TableCell class="text-slate-500">{{ student.email }}</TableCell>
              <TableCell class="font-mono text-[13px] text-slate-400">{{ student.id_number || '-' }}</TableCell>
              <TableCell>
                <span v-if="student.year_level" class="badge bg-slate-100 text-slate-600 ring-1 ring-slate-200">
                  {{ __('Year {n}', { n: student.year_level }) }}
                </span>
                <span v-else class="text-slate-400">-</span>
              </TableCell>
              <TableCell class="capitalize text-slate-500">{{ student.gender || '-' }}</TableCell>
            </TableRow>
            <TableRow v-if="!filtered.length">
              <TableCell colspan="6" class="text-center py-12 text-slate-400">
                {{ search ? __('No students match your search.') : __('No students enrolled.') }}
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>
    </template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

definePageMeta({ middleware: 'auth' })

const route = useRoute()
const { apiFetch } = useApi()

const students = ref<any[]>([])
const loading = ref(true)
const search = ref('')

onMounted(async () => {
  try {
    students.value = await apiFetch(`/teacher/classes/${route.params.id}/students`)
  } catch {}
  loading.value = false
})

const filtered = computed(() =>
  search.value
    ? students.value.filter((s: any) =>
        s.name?.toLowerCase().includes(search.value.toLowerCase()) ||
        s.email?.toLowerCase().includes(search.value.toLowerCase())
      )
    : students.value
)
</script>
