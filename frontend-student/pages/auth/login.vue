<script setup lang="ts">
import { ref } from 'vue'

definePageMeta({ layout: false, middleware: 'auth' })

const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)
const showPassword = ref(false)

const { login } = useAuth()

const submit = async () => {
  error.value = ''
  loading.value = true
  try {
    await login(email.value, password.value)
    navigateTo('/')
  } catch (e: any) {
    error.value = e?.data?.message || 'Invalid credentials.'
  } finally {
    loading.value = false
  }
}

const features = [
  { icon: 'check', text: 'Real-time attendance tracking' },
  { icon: 'chart', text: 'Attendance rate by subject' },
  { icon: 'bell', text: 'Instant absence notifications' },
]
</script>

<template>
  <div class="flex min-h-screen">

    <!-- Left panel — brand -->
    <div
      class="hidden lg:flex lg:w-[52%] flex-col justify-between p-12 relative overflow-hidden"
      style="background: linear-gradient(135deg, #0b1628 0%, #1a3260 60%, #1e3a6e 100%);"
    >
      <!-- Dot grid overlay -->
      <div
        class="absolute inset-0 opacity-[0.04]"
        style="background-image: radial-gradient(white 1px, transparent 1px); background-size: 28px 28px;"
      ></div>

      <!-- Decorative blobs -->
      <div class="absolute -top-32 -right-32 w-80 h-80 rounded-full opacity-[0.06]" style="background: radial-gradient(circle, #d4a017, transparent 70%);"></div>
      <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full opacity-[0.05]" style="background: radial-gradient(circle, #3b82f6, transparent 70%);"></div>

      <!-- Logo -->
      <div class="relative flex items-center gap-3">
        <div class="w-11 h-11 rounded-xl overflow-hidden shadow-lg">
          <img src="/logo1.png" alt="BELTEI" class="w-full h-full object-cover" />
        </div>
        <div>
          <h1 class="text-white font-bold text-base leading-tight tracking-tight">WebSAMS</h1>
          <p class="text-[10px] text-white/40 uppercase tracking-widest">Student Portal</p>
        </div>
      </div>

      <!-- Main copy -->
      <div class="relative">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-[11px] font-semibold text-white/60 border border-white/10 bg-white/5 mb-6">
          <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
          BELTEI International University
        </div>
        <h2 class="text-4xl font-bold text-white leading-[1.15] tracking-tight mb-4">
          Track your<br />attendance,<br /><span style="color: #d4a017;">stay on track.</span>
        </h2>
        <p class="text-slate-400 text-[15px] leading-relaxed max-w-xs">
          View your records, monitor your rate, and never miss an alert — all from one place.
        </p>
      </div>

      <!-- Feature bullets -->
      <div class="relative space-y-3">
        <div v-for="f in features" :key="f.text" class="flex items-center gap-3">
          <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" style="background: rgba(212,160,23,0.15);">
            <svg v-if="f.icon === 'check'" class="w-3.5 h-3.5" style="color: #d4a017;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <svg v-else-if="f.icon === 'chart'" class="w-3.5 h-3.5" style="color: #d4a017;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8M12 11v5M8 14v2" />
            </svg>
            <svg v-else-if="f.icon === 'bell'" class="w-3.5 h-3.5" style="color: #d4a017;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
          </div>
          <span class="text-[13px] text-slate-300">{{ f.text }}</span>
        </div>
      </div>
    </div>

    <!-- Right panel — form -->
    <div class="flex-1 flex items-center justify-center p-8 bg-slate-50">
      <div class="w-full max-w-sm animate-fade-in">

        <!-- Mobile logo -->
        <div class="flex lg:hidden items-center justify-center gap-3 mb-8">
          <div class="w-10 h-10 rounded-xl overflow-hidden">
            <img src="/logo1.png" alt="BELTEI" class="w-full h-full object-cover" />
          </div>
          <div>
            <h1 class="font-bold text-slate-900 text-base">WebSAMS</h1>
            <p class="text-[11px] text-slate-400">Student Portal</p>
          </div>
        </div>

        <div class="mb-8">
          <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Welcome back</h2>
          <p class="text-sm text-slate-500 mt-1">Sign in to your student account</p>
        </div>

        <div v-if="error" class="mb-5 flex items-center gap-2.5 px-4 py-3 rounded-xl bg-rose-50 border border-rose-200 text-[13px] text-rose-700">
          <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          {{ error }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
          <div>
            <label class="block text-[13px] font-semibold text-slate-600 mb-1.5">Email address</label>
            <input
              v-model="email" type="email" required autofocus
              class="input-modern"
              placeholder="student@websams.com"
            />
          </div>
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="block text-[13px] font-semibold text-slate-600">Password</label>
            </div>
            <div class="relative">
              <input
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                required
                class="input-modern pr-10"
                placeholder="••••••••"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors"
              >
                <svg v-if="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
              </button>
            </div>
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="btn-primary w-full justify-center py-3 text-[14px]"
          >
            <svg v-if="!loading" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
            <svg v-else class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
            {{ loading ? 'Signing in...' : 'Sign In' }}
          </button>
        </form>

        <p class="text-center text-[12px] text-slate-400 mt-8">
          BELTEI International University &copy; 2025
        </p>
      </div>
    </div>
  </div>
</template>
