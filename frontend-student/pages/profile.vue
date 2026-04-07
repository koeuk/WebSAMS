<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const profile = ref<any>(null)
const loading = ref(true)

onMounted(async () => {
  try {
    profile.value = await apiFetch('/student/profile')
  } catch {}
  loading.value = false
})
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">My Profile</h2>

    <div v-if="loading" class="text-sm text-gray-500">Loading...</div>

    <template v-else-if="profile">
      <div class="bg-white rounded-lg border border-gray-200 p-6 max-w-xl mb-6">
        <div class="space-y-4">
          <div>
            <p class="text-sm text-gray-500">Name</p>
            <p class="text-sm font-medium text-gray-900">{{ profile.name }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Email</p>
            <p class="text-sm font-medium text-gray-900">{{ profile.email }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Phone</p>
            <p class="text-sm font-medium text-gray-900">{{ profile.phone || 'Not set' }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Role</p>
            <p class="text-sm font-medium text-gray-900 capitalize">{{ profile.role }}</p>
          </div>
        </div>
      </div>

      <h3 class="text-lg font-semibold text-gray-900 mb-3">Enrolled Classes</h3>
      <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-200 bg-gray-50">
              <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Class</th>
              <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Section</th>
              <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Academic Year</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in profile.enrolled_classes" :key="c.id" class="border-b border-gray-100">
              <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ c.name }}</td>
              <td class="px-6 py-3 text-sm text-gray-600">{{ c.section || '-' }}</td>
              <td class="px-6 py-3 text-sm text-gray-600">{{ c.academic_year }}</td>
            </tr>
            <tr v-if="!profile.enrolled_classes?.length">
              <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500">Not enrolled in any classes.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </template>
  </div>
</template>
