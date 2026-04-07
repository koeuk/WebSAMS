<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const flash = computed(() => usePage().props.flash);
</script>

<template>
    <div v-if="flash?.success" class="mb-4 px-4 py-3 rounded-md bg-green-50 border border-green-200 text-sm text-green-800">
        {{ flash.success }}
    </div>
    <div v-if="flash?.error" class="mb-4 px-4 py-3 rounded-md bg-red-50 border border-red-200 text-sm text-red-800">
        {{ flash.error }}
    </div>
</template>
