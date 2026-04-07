<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const classes = ref<any[]>([])
const loading = ref(true)

onMounted(async () => {
  try {
    classes.value = await apiFetch('/teacher/classes')
  } catch {}
  loading.value = false
})
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">My Classes</h2>

    <div v-if="loading" class="text-sm text-gray-500">Loading...</div>

    <div v-else-if="classes.length" class="grid gap-4 md:grid-cols-2">
      <NuxtLink
        v-for="c in classes"
        :key="c.id"
        :to="`/classes/${c.id}`"
        class="bg-white rounded-lg border border-gray-200 p-6 hover:border-blue-300 transition-colors"
      >
        <h3 class="font-semibold text-gray-900">{{ c.subject?.name }}</h3>
        <p class="text-sm text-gray-600 mt-1">{{ c.school_class?.name }}</p>
        <p class="text-xs text-gray-500 mt-2">Code: {{ c.subject?.code }}</p>
      </NuxtLink>
    </div>

    <div v-else class="bg-white rounded-lg border border-gray-200 p-8 text-center text-sm text-gray-500">
      No classes assigned to you.
    </div>
  </div>
</template>
