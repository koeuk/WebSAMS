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

const formatDate = (d: string) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : '-'
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-900 mb-6">My Profile</h2>

    <div v-if="loading" class="text-sm text-gray-500">Loading...</div>

    <template v-else-if="profile">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Personal Info -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
          <div class="flex items-center gap-4 mb-4 border-b pb-4">
            <img v-if="profile.profile_photo" :src="`http://localhost:8000/storage/${profile.profile_photo}`" class="h-20 w-20 rounded-full object-cover border" />
            <div v-else class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 text-2xl font-bold">
              {{ profile.name?.charAt(0) }}
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">{{ profile.name }}</h3>
              <p class="text-sm text-gray-600">{{ profile.id_number || 'No ID' }}</p>
              <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 capitalize">{{ profile.status || 'active' }}</span>
            </div>
          </div>
          <div class="space-y-3">
            <div class="grid grid-cols-2">
              <p class="text-sm text-gray-500">Email</p>
              <p class="text-sm font-medium text-gray-900">{{ profile.email }}</p>
            </div>
            <div class="grid grid-cols-2">
              <p class="text-sm text-gray-500">Phone</p>
              <p class="text-sm font-medium text-gray-900">{{ profile.phone || '-' }}</p>
            </div>
            <div class="grid grid-cols-2">
              <p class="text-sm text-gray-500">Gender</p>
              <p class="text-sm font-medium text-gray-900 capitalize">{{ profile.gender || '-' }}</p>
            </div>
            <div class="grid grid-cols-2">
              <p class="text-sm text-gray-500">Date of Birth</p>
              <p class="text-sm font-medium text-gray-900">{{ formatDate(profile.date_of_birth) }}</p>
            </div>
            <div class="grid grid-cols-2">
              <p class="text-sm text-gray-500">Address</p>
              <p class="text-sm font-medium text-gray-900">{{ profile.address || '-' }}</p>
            </div>
          </div>
        </div>

        <!-- Student Info -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Student Info</h3>
          <div class="space-y-3">
            <div class="grid grid-cols-2">
              <p class="text-sm text-gray-500">Year Level</p>
              <p class="text-sm font-medium text-gray-900">{{ profile.year_level ? 'Year ' + profile.year_level : '-' }}</p>
            </div>
            <div class="grid grid-cols-2">
              <p class="text-sm text-gray-500">Enrollment Date</p>
              <p class="text-sm font-medium text-gray-900">{{ formatDate(profile.enrollment_date) }}</p>
            </div>
            <div class="grid grid-cols-2">
              <p class="text-sm text-gray-500">Guardian Name</p>
              <p class="text-sm font-medium text-gray-900">{{ profile.guardian_name || '-' }}</p>
            </div>
            <div class="grid grid-cols-2">
              <p class="text-sm text-gray-500">Guardian Phone</p>
              <p class="text-sm font-medium text-gray-900">{{ profile.guardian_phone || '-' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Enrolled Classes -->
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
