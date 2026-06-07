<template>
    <div
        ref="pageRef"
        class="login-page-wrapper flex items-center justify-center min-h-screen bg-linear-to-br from-slate-100 via-blue-50/30 to-slate-100"
        :style="spotlightStyle"
    >
        <div class="login-atmosphere" aria-hidden="true"></div>
        <div class="login-spotlight" aria-hidden="true"></div>

        <div class="w-full max-w-md login-entrance login-entrance-delay-1 relative">
            <Card class="shadow-xl border-0 login-entrance login-entrance-delay-2" style="box-shadow: 0 20px 60px rgba(30, 58, 110, 0.12), 0 4px 16px rgba(0, 0, 0, 0.06);">
                <CardContent class="p-10">
                    <div class="text-center mb-8">
                        <div class="w-20 h-20 rounded-2xl overflow-hidden shadow-lg mb-4 inline-block">
                            <img :src="logoSrc" @error="logoError = true" alt="BELTEI International University" class="w-full h-full object-cover" />
                        </div>
                        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('WebSAMS') }}</h1>
                        <p class="text-[13px] text-slate-500 mt-1">{{ __('Student Attendance Management System') }}</p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-5">
                        <div>
                            <Label for="email" class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Email') }}</Label>
                            <Input
                                id="email" v-model="form.email" type="email" required autofocus
                                placeholder="admin@websams.com"
                            />
                            <p v-if="form.errors.email" class="text-[12px] text-rose-500 mt-1">{{ form.errors.email }}</p>
                        </div>

                        <div>
                            <Label for="password" class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Password') }}</Label>
                            <Input
                                id="password" v-model="form.password" type="password" required
                                :placeholder="__('Enter your password')"
                            />
                        </div>

                        <Button type="submit" :disabled="form.processing" class="w-full justify-center py-3!">
                            <svg v-if="!form.processing" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            <svg v-else class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            {{ form.processing ? __('Signing in...') : __('Sign In') }}
                        </Button>
                    </form>
                </CardContent>
            </Card>

            <p class="text-center text-[12px] text-slate-400 mt-6 login-entrance login-entrance-delay-3">{{ __('BELTEI International University') }}</p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { useMouseInElement } from '@vueuse/core';
import { __ } from '@/Composables/useTranslate';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Card, CardContent } from '@/Components/ui/card';

const pageRef = ref(null);
const { elementX, elementY, isOutside } = useMouseInElement(pageRef);

const spotlightStyle = computed(() => ({
    '--mouse-x': `${elementX.value}px`,
    '--mouse-y': `${elementY.value}px`,
    '--mouse-opacity': isOutside.value ? 0 : 1,
}));

const page = usePage();
const logoError = ref(false);
const logoSrc = computed(() => (!logoError.value && page.props.university_logo) ? page.props.university_logo : '/logo1.png');

const form = useForm({ email: '', password: '' });

const submit = () => {
    form.post('/login', { onFinish: () => form.reset('password') });
};
</script>

<style scoped>
.login-page-wrapper {
    position: relative;
    isolation: isolate;
}

.login-atmosphere,
.login-spotlight {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: -1;
    overflow: clip;
    -webkit-mask-image: linear-gradient(180deg, #000 0%, #000 60%, transparent 100%);
            mask-image: linear-gradient(180deg, #000 0%, #000 60%, transparent 100%);
}

.login-atmosphere::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(15, 23, 42, 0.08) 1px, transparent 1px);
    background-size: 18px 18px;
}

.login-atmosphere::after {
    content: '';
    position: absolute;
    inset: 0;
    background:
        radial-gradient(at top right, rgba(212, 160, 23, 0.18), transparent 55%),
        radial-gradient(at bottom left, rgba(125, 175, 145, 0.14), transparent 55%);
}

.login-spotlight {
    background: radial-gradient(
        600px circle at var(--mouse-x, 50%) var(--mouse-y, 50%),
        rgba(30, 58, 110, 0.22),
        transparent 50%
    );
    opacity: var(--mouse-opacity, 0);
    transition: opacity 220ms ease-out;
}

@keyframes login-entrance {
    from {
        opacity: 0;
        transform: translateY(6px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.login-entrance {
    animation: login-entrance 600ms cubic-bezier(0.22, 1, 0.36, 1) both;
}

.login-entrance-delay-1 { animation-delay: 80ms; }
.login-entrance-delay-2 { animation-delay: 180ms; }
.login-entrance-delay-3 { animation-delay: 280ms; }

@media (prefers-reduced-motion: reduce) {
    .login-spotlight { display: none; }
    .login-entrance { animation: none; }
}
</style>
