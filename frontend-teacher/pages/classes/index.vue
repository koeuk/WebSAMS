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
  <div class="animate-fade-in">
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">My Classes</h2>
      <p class="text-sm text-slate-500 mt-1">Classes assigned to you this semester</p>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">Loading...</div>

    <div v-else-if="classes.length" class="grid gap-4 md:grid-cols-2 stagger-children">
      <NuxtLink
        v-for="c in classes"
        :key="c.id"
        :to="`/classes/${c.id}`"
        class="card p-6 hover:shadow-lg transition-all duration-200 hover:-translate-y-0.5 animate-fade-in-up group"
      >
        <div class="flex items-start justify-between">
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-beltei/5 flex items-center justify-center group-hover:bg-beltei/10 transition-colors">
              <svg class="w-6 h-6 text-beltei" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/></svg>
            </div>
            <div>
              <h3 class="font-semibold text-slate-900 group-hover:text-beltei transition-colors">{{ c.subject?.name }}</h3>
              <p class="text-[13px] text-slate-500 mt-1">{{ c.school_class?.name }}</p>
            </div>
          </div>
          <span class="badge bg-slate-100 text-slate-500 ring-1 ring-slate-200 font-mono">{{ c.subject?.code }}</span>
        </div>
      </NuxtLink>
    </div>

    <div v-else class="card p-12 text-center text-slate-400">
      No classes assigned to you.
    </div>
  </div>
</template>
