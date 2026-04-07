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
  <div>
    <div class="flex items-center gap-4 mb-6">
      <NuxtLink to="/classes" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back</NuxtLink>
      <h2 class="text-2xl font-bold text-gray-900">Student List</h2>
    </div>

    <div v-if="loading" class="text-sm text-gray-500">Loading...</div>

    <div v-else class="bg-white rounded-lg border border-gray-200 overflow-hidden">
      <table class="w-full">
        <thead>
          <tr class="border-b border-gray-200 bg-gray-50">
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">#</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(student, index) in students" :key="student.id" class="border-b border-gray-100">
            <td class="px-6 py-3 text-sm text-gray-600">{{ index + 1 }}</td>
            <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ student.name }}</td>
            <td class="px-6 py-3 text-sm text-gray-600">{{ student.email }}</td>
          </tr>
          <tr v-if="!students.length">
            <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">No students enrolled.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
