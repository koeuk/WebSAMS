<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const classes = ref<any[]>([])
const timeSlots = ref<any[]>([])
const loading = ref(true)

const selectedClass = ref('')
const selectedSlot = ref('')
const selectedDate = ref(new Date().toISOString().split('T')[0])
const duration = ref(15)
const generating = ref(false)
const activeSession = ref<any>(null)

onMounted(async () => {
  try {
    ;[classes.value, timeSlots.value] = await Promise.all([
      apiFetch('/teacher/classes'),
      apiFetch('/teacher/time-slots'),
    ])
  } catch {}
  loading.value = false
})

const generate = async () => {
  if (!selectedClass.value) return
  generating.value = true
  try {
    activeSession.value = await apiFetch('/teacher/qr-sessions', {
      method: 'POST',
      body: {
        class_subject_id: selectedClass.value,
        time_slot_id: selectedSlot.value || null,
        date: selectedDate.value,
        duration_minutes: duration.value,
      },
    })
  } catch {}
  generating.value = false
}

const deactivate = async () => {
  if (!activeSession.value) return
  try {
    await apiFetch(`/teacher/qr-sessions/${activeSession.value.id}`, { method: 'DELETE' })
    activeSession.value = null
  } catch {}
}

const expiresIn = computed(() => {
  if (!activeSession.value?.expires_at) return ''
  const diff = new Date(activeSession.value.expires_at).getTime() - Date.now()
  if (diff <= 0) return 'Expired'
  const mins = Math.floor(diff / 60000)
  const secs = Math.floor((diff % 60000) / 1000)
  return `${mins}m ${secs}s`
})

const classOptions = computed(() =>
  classes.value.map((c: any) => ({
    value: String(c.id),
    label: `${c.class_name} — ${c.subject_name}`,
  }))
)
const timeSlotOptions = computed(() => [
  { value: '', label: 'None' },
  ...timeSlots.value.map((t: any) => ({
    value: String(t.id),
    label: `${t.name} (${t.start_time}–${t.end_time})`,
  })),
])

const qrUrl = computed(() =>
  activeSession.value?.token
    ? `${window?.location?.origin?.replace('3001', '3002') ?? 'http://localhost:3002'}/qr?token=${activeSession.value.token}`
    : ''
)
</script>

<template>
  <div class="animate-fade-in">
    <div class="mb-7">
      <h2 class="text-2xl font-bold text-slate-900 tracking-tight">QR Attendance</h2>
      <p class="text-sm text-slate-500 mt-1">Generate a QR code for students to scan</p>
    </div>

    <div v-if="loading" class="card p-12 text-center text-slate-400 text-sm">Loading...</div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Config card -->
      <div class="card p-6 animate-fade-in-up">
        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-5">Session Setup</h3>
        <div class="space-y-4">
          <div>
            <label class="block text-[12px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Class / Subject</label>
            <AppCombobox
              v-model="selectedClass"
              :options="classOptions"
              placeholder="Select a class..."
              search-placeholder="Search class..."
              class="w-full"
            />
          </div>
          <div>
            <label class="block text-[12px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Date</label>
            <input v-model="selectedDate" type="date" class="input-modern w-full" />
          </div>
          <div>
            <label class="block text-[12px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Time Slot (optional)</label>
            <AppCombobox
              v-model="selectedSlot"
              :options="timeSlotOptions"
              placeholder="None"
              search-placeholder="Search time slot..."
              class="w-full"
            />
          </div>
          <div>
            <label class="block text-[12px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Valid for (minutes)</label>
            <input v-model.number="duration" type="number" min="5" max="120" class="input-modern w-full" />
          </div>
          <button
            @click="generate"
            :disabled="!selectedClass || generating"
            class="btn-primary w-full justify-center py-3 text-[14px] mt-2"
          >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="5" height="5"/><rect x="16" y="3" width="5" height="5"/><rect x="3" y="16" width="5" height="5"/></svg>
            {{ generating ? 'Generating...' : 'Generate QR Code' }}
          </button>
        </div>
      </div>

      <!-- QR display card -->
      <div class="card p-6 animate-fade-in-up flex flex-col items-center justify-center min-h-[320px]">
        <template v-if="activeSession">
          <div class="text-center mb-4">
            <span class="badge bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 mb-2">Active · {{ expiresIn }}</span>
            <p class="text-[12px] text-slate-400">Show this to your students</p>
          </div>
          <!-- QR rendered via external service using token-based URL -->
          <img
            :src="`https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(qrUrl)}`"
            alt="QR Code"
            class="rounded-xl shadow-lg mb-4 w-48 h-48"
          />
          <p class="text-[11px] font-mono text-slate-400 text-center break-all px-4 mb-4">{{ activeSession.token }}</p>
          <button @click="deactivate" class="btn-secondary py-2 px-5 text-[13px] text-rose-600 hover:text-rose-700 hover:bg-rose-50">
            Deactivate
          </button>
        </template>
        <template v-else>
          <div class="text-center text-slate-300">
            <svg class="w-20 h-20 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
              <rect x="3" y="3" width="5" height="5"/><rect x="16" y="3" width="5" height="5"/><rect x="3" y="16" width="5" height="5"/>
              <path d="M21 16h-3v2h-2v3h2v-2h3v-3zM21 21v-2M14 14h3v3h-3z"/><path d="M3 14h2v2H3z"/>
            </svg>
            <p class="text-[13px] text-slate-400">Configure and generate a QR code</p>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>
