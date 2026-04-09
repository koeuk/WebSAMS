<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({ email: '', password: '' });

const submit = () => {
    form.post('/login', { onFinish: () => form.reset('password') });
};
</script>

<template>
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-slate-100 via-blue-50/30 to-slate-100">
        <div class="w-full max-w-md animate-fade-in">
            <div class="card p-10 shadow-xl border-0" style="box-shadow: 0 20px 60px rgba(30, 58, 110, 0.12), 0 4px 16px rgba(0, 0, 0, 0.06);">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-beltei-gold to-beltei-gold-dark shadow-lg shadow-beltei-gold/20 mb-4">
                        <img src="/logo.png" alt="BELTEI International University" class="h-14" />
                    </div>
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">WebSAMS</h1>
                    <p class="text-[13px] text-slate-500 mt-1">Student Attendance Management System</p>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label for="email" class="block text-[13px] font-medium text-slate-600 mb-1.5">Email</label>
                        <input
                            id="email" v-model="form.email" type="email" required autofocus
                            class="input-modern" placeholder="admin@websams.com"
                        />
                        <p v-if="form.errors.email" class="text-[12px] text-rose-500 mt-1">{{ form.errors.email }}</p>
                    </div>

                    <div>
                        <label for="password" class="block text-[13px] font-medium text-slate-600 mb-1.5">Password</label>
                        <input
                            id="password" v-model="form.password" type="password" required
                            class="input-modern" placeholder="Enter your password"
                        />
                    </div>

                    <button type="submit" :disabled="form.processing" class="btn-primary w-full justify-center !py-3">
                        <svg v-if="!form.processing" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        <svg v-else class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        {{ form.processing ? 'Signing in...' : 'Sign In' }}
                    </button>
                </form>
            </div>

            <p class="text-center text-[12px] text-slate-400 mt-6">BELTEI International University</p>
        </div>
    </div>
</template>
