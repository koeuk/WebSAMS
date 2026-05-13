<script setup lang="ts">
import { ref, onMounted } from 'vue'

definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const profile = ref<any>(null)
const loading = ref(true)

onMounted(async () => {
  try { profile.value = await apiFetch('/teacher/profile') } catch {}
  loading.value = false
})

const formatDate = (d: string) => d
  ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
  : '-'
</script>

<template>
  <div class="animate-fade-in">
    <div class="mb-7">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">My Profile</h2>
      <p class="text-sm text-slate-500 mt-1">Your personal and teaching information</p>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">Loading...</div>

    <template v-else-if="profile">
      <!-- Hero card -->
      <div class="card p-6 mb-5 animate-fade-in-up">
        <div class="flex items-center gap-5">
          <div class="relative shrink-0">
            <div class="absolute inset-0 rounded-2xl" style="background: linear-gradient(135deg, #d4a017, #1e3a6e); padding: 2px; border-radius: 18px;">
              <div class="w-full h-full rounded-2xl bg-white" style="border-radius: 16px;"></div>
            </div>
            <img v-if="profile.profile_photo" :src="`http://127.0.0.1:8001/storage/${profile.profile_photo}`" class="relative h-20 w-20 rounded-2xl object-cover" style="border-radius: 16px;" />
            <div v-else class="relative h-20 w-20 rounded-2xl flex items-center justify-center text-white text-2xl font-extrabold" style="border-radius: 16px; background: linear-gradient(135deg, #1e3a6e, #2a4f8f);">
              {{ profile.name?.charAt(0) }}
            </div>
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="text-xl font-bold text-slate-900 leading-tight">{{ profile.name }}</h3>
            <p class="text-[13px] text-slate-500 mt-0.5">{{ profile.email }}</p>
            <p class="text-[13px] font-mono text-slate-400 mt-0.5">{{ profile.id_number || 'No ID assigned' }}</p>
            <span v-if="profile.department" class="inline-block mt-2 badge bg-blue-50 text-blue-700 ring-1 ring-blue-200">
              {{ profile.department }}
            </span>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5 stagger-children">
        <!-- Personal Info -->
        <div class="card p-6 animate-fade-in-up">
          <h4 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></span>
            Personal Information
          </h4>
          <dl class="space-y-0 divide-y divide-slate-50">
            <div v-for="row in [
              { label: 'Phone', value: profile.phone || '-' },
              { label: 'Gender', value: profile.gender || '-', capitalize: true },
              { label: 'Date of Birth', value: formatDate(profile.date_of_birth) },
              { label: 'Address', value: profile.address || '-' },
            ]" :key="row.label" class="flex items-center justify-between py-3">
              <dt class="text-[13px] text-slate-500">{{ row.label }}</dt>
              <dd class="text-[13px] font-semibold text-slate-800 text-right max-w-[220px] truncate" :class="row.capitalize ? 'capitalize' : ''">{{ row.value }}</dd>
            </div>
          </dl>
        </div>

        <!-- Teaching Info -->
        <div class="card p-6 animate-fade-in-up">
          <h4 class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
            Teaching Information
          </h4>
          <dl class="space-y-0 divide-y divide-slate-50">
            <div v-for="row in [
              { label: 'Department', value: profile.department || '-' },
              { label: 'Qualification', value: profile.qualification || '-' },
              { label: 'Hire Date', value: formatDate(profile.hire_date) },
            ]" :key="row.label" class="flex items-center justify-between py-3">
              <dt class="text-[13px] text-slate-500">{{ row.label }}</dt>
              <dd class="text-[13px] font-semibold text-slate-800 text-right max-w-[220px] truncate">{{ row.value }}</dd>
            </div>
          </dl>
        </div>
      </div>

      <!-- Teaching Classes -->
      <div class="card overflow-hidden animate-fade-in-up">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
          <span class="w-1.5 h-1.5 rounded-full" style="background:#d4a017;"></span>
          <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">Teaching Classes</h3>
        </div>
        <table class="modern-table">
          <thead><tr><th class="text-left">Class</th><th class="text-left">Subject</th></tr></thead>
          <tbody>
            <tr v-for="c in profile.teaching_classes" :key="c.id">
              <td class="font-semibold text-slate-900">{{ c.class_name || '-' }}</td>
              <td class="text-slate-600">{{ c.subject_name || '-' }}</td>
            </tr>
            <tr v-if="!profile.teaching_classes?.length">
              <td colspan="2" class="!text-center !py-8 text-slate-400">No classes assigned.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </template>
  </div>
</template>
