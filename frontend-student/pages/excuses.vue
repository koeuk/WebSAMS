<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const attendance = ref<any>({ data: [] })
const excuses = ref<any>({ data: [] })
const loading = ref(true)
const showForm = ref(false)
const selectedAttendance = ref<any>(null)
const reason = ref('')
const submitting = ref(false)
const successMsg = ref('')

onMounted(async () => {
  try {
    ;[attendance.value, excuses.value] = await Promise.all([
      apiFetch('/student/attendance'),
      apiFetch('/student/excuse-requests'),
    ])
  } catch {}
  loading.value = false
})

const absences = computed(() =>
  (attendance.value?.data ?? []).filter((r: any) => r.status === 'absent')
)

const pendingExcuseIds = computed(() =>
  new Set((excuses.value?.data ?? []).map((e: any) => e.attendance_id))
)

const requests = computed(() => excuses.value?.data ?? [])

const openForm = (record: any) => {
  selectedAttendance.value = record
  reason.value = ''
  showForm.value = true
}

const submit = async () => {
  if (!selectedAttendance.value || !reason.value.trim()) return
  submitting.value = true
  try {
    await apiFetch('/student/excuse-requests', {
      method: 'POST',
      body: { attendance_id: selectedAttendance.value.id, reason: reason.value },
    })
    showForm.value = false
    successMsg.value = 'Excuse request submitted successfully.'
    excuses.value = await apiFetch('/student/excuse-requests')
    setTimeout(() => { successMsg.value = '' }, 4000)
  } catch {}
  submitting.value = false
}

const statusConfig: Record<string, string> = {
  pending:  'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
  approved: 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
  rejected: 'bg-rose-50 text-rose-700 ring-1 ring-rose-200',
}

const formatDate = (d: string) => d ? new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '-'
</script>

<template>
  <div class="animate-fade-in">
    <div class="mb-7">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Excuse Requests</h2>
      <p class="text-sm text-slate-500 mt-1">Submit excuses for absent attendance records</p>
    </div>

    <div v-if="successMsg" class="mb-5 flex items-center gap-2.5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-[13px] text-emerald-700 animate-fade-in">
      <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
      {{ successMsg }}
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">Loading...</div>

    <template v-else>
      <!-- Absences to excuse -->
      <div class="card overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
          <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
          <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">Absent Records</h3>
        </div>
        <table class="modern-table">
          <thead><tr><th class="text-left">Date</th><th class="text-left">Class</th><th class="text-left">Subject</th><th class="text-left">Action</th></tr></thead>
          <tbody>
            <tr v-for="r in absences" :key="r.id">
              <td class="font-semibold text-slate-900">{{ r.date }}</td>
              <td>{{ r.class_subject?.school_class?.name }}</td>
              <td>{{ r.class_subject?.subject?.name }}</td>
              <td>
                <button
                  v-if="!pendingExcuseIds.has(r.id)"
                  @click="openForm(r)"
                  class="btn-secondary py-1 px-3 text-[12px]"
                >Request Excuse</button>
                <span v-else class="badge bg-amber-50 text-amber-700 ring-1 ring-amber-200">Submitted</span>
              </td>
            </tr>
            <tr v-if="!absences.length">
              <td colspan="4" class="!text-center !py-10 text-slate-400">No absent records found.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Submitted requests -->
      <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
          <span class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></span>
          <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">My Requests</h3>
        </div>
        <table class="modern-table">
          <thead><tr><th class="text-left">Date</th><th class="text-left">Class</th><th class="text-left">Reason</th><th class="text-left">Status</th><th class="text-left">Note</th></tr></thead>
          <tbody>
            <tr v-for="e in requests" :key="e.id">
              <td class="font-semibold text-slate-900">{{ formatDate(e.attendance?.date) }}</td>
              <td>{{ e.attendance?.class_subject?.school_class?.name }}</td>
              <td class="max-w-[180px] truncate text-slate-500">{{ e.reason }}</td>
              <td><span class="badge" :class="statusConfig[e.status]">{{ e.status }}</span></td>
              <td class="text-slate-400">{{ e.reviewer_note || '—' }}</td>
            </tr>
            <tr v-if="!requests.length">
              <td colspan="5" class="!text-center !py-10 text-slate-400">No requests submitted yet.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </template>

    <!-- Form modal -->
    <Teleport to="body">
      <Transition name="modal">
        <div v-if="showForm" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm" @click.self="showForm = false">
          <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-1">Submit Excuse</h3>
            <p class="text-[13px] text-slate-500 mb-4">
              {{ selectedAttendance?.class_subject?.subject?.name }} — {{ selectedAttendance?.date }}
            </p>
            <label class="block text-[12px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Reason</label>
            <textarea v-model="reason" rows="4" class="input-modern w-full resize-none mb-5" placeholder="Explain why you were absent..."></textarea>
            <div class="flex gap-3">
              <button @click="submit" :disabled="submitting || !reason.trim()" class="btn-primary flex-1 justify-center py-2.5 text-[13px]">
                {{ submitting ? 'Submitting...' : 'Submit Request' }}
              </button>
              <button @click="showForm = false" class="btn-secondary py-2.5 px-5 text-[13px]">Cancel</button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<style scoped>
.modal-enter-active, .modal-leave-active { transition: opacity 0.2s; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
</style>
