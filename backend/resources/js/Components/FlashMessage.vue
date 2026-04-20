<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const flash = computed(() => usePage().props.flash);
</script>

<template>
    <div class="space-y-3 mb-5 empty:hidden">
        <div
            v-if="flash?.success"
            class="flex items-center gap-3 px-4 py-3.5 rounded-xl border animate-fade-in"
            style="background: #f0fdf4; border-color: #bbf7d0;"
        >
            <svg class="w-4 h-4 shrink-0" style="color: #16a34a;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm font-medium" style="color: #15803d;">{{ flash.success }}</p>
        </div>
        <div
            v-if="flash?.error"
            class="flex items-center gap-3 px-4 py-3.5 rounded-xl border animate-fade-in"
            style="background: #fff1f2; border-color: #fecdd3;"
        >
            <svg class="w-4 h-4 shrink-0" style="color: #dc2626;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm font-medium" style="color: #b91c1c;">{{ flash.error }}</p>
        </div>
    </div>
</template>
