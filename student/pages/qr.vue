<template>
  <div class="animate-fade-in">
    <div class="mb-7 text-center">
      <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-beltei-gold mb-1.5">{{ __('Check In') }}</p>
      <h2 class="page-title text-[28px] sm:text-[32px]">{{ __('QR Attendance') }}</h2>
      <div class="gold-rule mt-3 mx-auto" style="background: linear-gradient(90deg, rgba(200,164,21,0), #c8a415 50%, rgba(200,164,21,0));"></div>
      <p class="text-sm text-slate-500 mt-3">{{ __("Scan your teacher's QR code or enter the token manually") }}</p>
    </div>

    <div class="max-w-md mx-auto">
      <div class="card stat-card stat-card-navy p-8 text-center animate-fade-in-up">
        <div class="relative w-20 h-20 mx-auto mb-6">
          <div class="absolute -inset-1.5 rounded-3xl bg-[#c8a415]/10 ring-1 ring-[#c8a415]/20"></div>
          <div class="relative w-20 h-20 rounded-2xl flex items-center justify-center ring-1 ring-[#c8a415]/30" style="background: linear-gradient(135deg, #1e3a6e, #2a4f8f);">
            <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <rect x="3" y="3" width="5" height="5"/><rect x="16" y="3" width="5" height="5"/><rect x="3" y="16" width="5" height="5"/>
              <path d="M21 16h-3v2h-2v3h2v-2h3v-3zM21 21v-2M14 14h3v3h-3z"/><path d="M3 14h2v2H3z"/>
            </svg>
          </div>
        </div>

        <div v-if="success" class="mb-5 flex items-center gap-2.5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-[13px] text-emerald-700 text-left">
          <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
          {{ success }}
        </div>

        <div v-if="error" class="mb-5 flex items-center gap-2.5 px-4 py-3 rounded-xl bg-rose-50 border border-rose-200 text-[13px] text-rose-700 text-left">
          <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          {{ error }}
        </div>

        <div class="text-left mb-4 space-y-1.5">
          <Label class="text-[12px] font-semibold text-slate-500 uppercase tracking-wider">{{ __('QR Token') }}</Label>
          <Input
            v-model="token"
            type="text"
            class="font-mono text-[13px]"
            :placeholder="__('Paste or type the QR token...')"
            @keyup.enter="attend"
          />
        </div>

        <Button
          @click="attend"
          :disabled="!token.trim() || loading"
          class="w-full justify-center py-3 h-auto text-[14px] flex items-center gap-2"
        >
          <svg v-if="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
          <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/></svg>
          {{ loading ? __('Recording...') : __('Mark Attendance') }}
        </Button>

        <p class="mt-5 text-[12px] text-slate-400">
          {{ __('When your teacher shows a QR code, you can also scan it with your phone camera.') }}<br/>
          {{ __('The token from the QR will auto-fill this page.') }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

definePageMeta({ middleware: 'auth' })

const { apiFetch } = useApi()
const route = useRoute()

const token = ref((route.query.token as string) ?? '')
const loading = ref(false)
const success = ref('')
const error = ref('')

const attend = async () => {
  if (!token.value.trim()) return
  loading.value = true
  success.value = ''
  error.value = ''
  try {
    const res = await apiFetch('/student/attend', { method: 'POST', body: { token: token.value.trim() } })
    success.value = (res as any).message ?? __('Attendance recorded!')
    token.value = ''
  } catch (e: any) {
    error.value = e?.data?.message ?? __('Failed to record attendance. The QR code may be invalid or expired.')
  }
  loading.value = false
}

onMounted(() => {
  if (token.value) attend()
})
</script>
