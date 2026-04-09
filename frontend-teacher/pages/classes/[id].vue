<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const route = useRoute()
const { apiFetch } = useApi()

const students = ref<any[]>([])
const loading = ref(true)

onMounted(async () => {
  try {
    students.value = await apiFetch(`/teacher/classes/${route.params.id}/students`)
  } catch {}
  loading.value = false
})
</script>

<template>
  <div class="animate-fade-in">
    <div class="flex items-center gap-4 mb-8">
      <NuxtLink to="/classes" class="flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7"/></svg>
        Back
      </NuxtLink>
      <div class="h-5 w-px bg-slate-200"></div>
      <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Student List</h2>
        <p class="text-sm text-slate-500 mt-0.5">{{ students.length }} students enrolled</p>
      </div>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">Loading...</div>

    <div v-else class="card overflow-hidden">
      <table class="modern-table">
        <thead>
          <tr>
            <th class="text-left">#</th>
            <th class="text-left">Name</th>
            <th class="text-left">Email</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(student, index) in students" :key="student.id">
            <td class="text-slate-400 font-mono text-[13px]">{{ index + 1 }}</td>
            <td class="font-semibold text-slate-900">{{ student.name }}</td>
            <td>{{ student.email }}</td>
          </tr>
          <tr v-if="!students.length">
            <td colspan="3" class="!text-center !py-12 text-slate-400">No students enrolled.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
