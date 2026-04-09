<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const profile = ref<any>(null)
const loading = ref(true)

onMounted(async () => {
  try { profile.value = await apiFetch('/student/profile') } catch {}
  loading.value = false
})

const formatDate = (d: string) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }) : '-'

const statusBadgeClass = (status: string) => ({
  'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200': status === 'active',
  'bg-slate-100 text-slate-600 ring-1 ring-slate-200': status === 'inactive',
  'bg-sky-50 text-sky-700 ring-1 ring-sky-200': status === 'graduated',
  'bg-rose-50 text-rose-700 ring-1 ring-rose-200': status === 'suspended',
})
</script>

<template>
  <div class="animate-fade-in">
    <div class="mb-8">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">My Profile</h2>
      <p class="text-sm text-slate-500 mt-1">Your personal and academic information</p>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">Loading...</div>

    <template v-else-if="profile">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 stagger-children">
        <!-- Personal Info -->
        <div class="card p-6 animate-fade-in-up">
          <div class="flex items-center gap-4 mb-5 pb-5 border-b border-slate-100">
            <img v-if="profile.profile_photo" :src="`http://localhost:8000/storage/${profile.profile_photo}`" class="h-20 w-20 rounded-xl object-cover ring-2 ring-slate-100" />
            <div v-else class="h-20 w-20 rounded-xl bg-gradient-to-br from-beltei to-beltei-dark flex items-center justify-center text-white text-2xl font-bold ring-2 ring-slate-100">
              {{ profile.name?.charAt(0) }}
            </div>
            <div>
              <h3 class="text-lg font-bold text-slate-900">{{ profile.name }}</h3>
              <p class="text-[13px] text-slate-500 font-mono">{{ profile.id_number || 'No ID' }}</p>
              <span class="badge mt-1" :class="statusBadgeClass(profile.status || 'active')">{{ profile.status || 'active' }}</span>
            </div>
          </div>
          <div class="space-y-3">
            <div class="flex items-center justify-between py-2 border-b border-slate-50">
              <p class="text-[13px] text-slate-500">Email</p>
              <p class="text-[13px] font-semibold text-slate-900">{{ profile.email }}</p>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-slate-50">
              <p class="text-[13px] text-slate-500">Phone</p>
              <p class="text-[13px] font-semibold text-slate-900">{{ profile.phone || '-' }}</p>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-slate-50">
              <p class="text-[13px] text-slate-500">Gender</p>
              <p class="text-[13px] font-semibold text-slate-900 capitalize">{{ profile.gender || '-' }}</p>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-slate-50">
              <p class="text-[13px] text-slate-500">Date of Birth</p>
              <p class="text-[13px] font-semibold text-slate-900">{{ formatDate(profile.date_of_birth) }}</p>
            </div>
            <div class="flex items-center justify-between py-2">
              <p class="text-[13px] text-slate-500">Address</p>
              <p class="text-[13px] font-semibold text-slate-900 text-right max-w-[200px]">{{ profile.address || '-' }}</p>
            </div>
          </div>
        </div>

        <!-- Student Info -->
        <div class="card p-6 animate-fade-in-up">
          <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-5 flex items-center gap-2">
            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
            Student Info
          </h3>
          <div class="space-y-3">
            <div class="flex items-center justify-between py-2 border-b border-slate-50">
              <p class="text-[13px] text-slate-500">Year Level</p>
              <p class="text-[13px] font-semibold text-slate-900">{{ profile.year_level ? 'Year ' + profile.year_level : '-' }}</p>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-slate-50">
              <p class="text-[13px] text-slate-500">Enrollment Date</p>
              <p class="text-[13px] font-semibold text-slate-900">{{ formatDate(profile.enrollment_date) }}</p>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-slate-50">
              <p class="text-[13px] text-slate-500">Guardian Name</p>
              <p class="text-[13px] font-semibold text-slate-900">{{ profile.guardian_name || '-' }}</p>
            </div>
            <div class="flex items-center justify-between py-2">
              <p class="text-[13px] text-slate-500">Guardian Phone</p>
              <p class="text-[13px] font-semibold text-slate-900">{{ profile.guardian_phone || '-' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Enrolled Classes -->
      <div class="card overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
          <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider flex items-center gap-2">
            <div class="w-1.5 h-1.5 rounded-full bg-[#d4a017]"></div>
            Enrolled Classes
          </h3>
        </div>
        <table class="modern-table">
          <thead><tr><th class="text-left">Class</th><th class="text-left">Section</th><th class="text-left">Academic Year</th></tr></thead>
          <tbody>
            <tr v-for="c in profile.enrolled_classes" :key="c.id">
              <td class="font-semibold text-slate-900">{{ c.name }}</td>
              <td>{{ c.section || '-' }}</td>
              <td><span class="badge bg-slate-100 text-slate-600 ring-1 ring-slate-200">{{ c.academic_year }}</span></td>
            </tr>
            <tr v-if="!profile.enrolled_classes?.length">
              <td colspan="3" class="!text-center !py-8 text-slate-400">Not enrolled in any classes.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </template>
  </div>
</template>
