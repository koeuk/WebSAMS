<template><span class="hidden" /></template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import { watch, onMounted, nextTick } from 'vue';
import { toast } from 'vue-sonner';

let lastFlashRef = null;

const page = usePage();

const fire = (flash) => {
    if (!flash || flash === lastFlashRef) return;
    lastFlashRef = flash;
    if (flash.success) toast.success(flash.success);
    if (flash.error)   toast.error(flash.error);
};

onMounted(() => nextTick(() => fire(page.props.flash)));

watch(
    () => page.props.flash,
    (flash) => fire(flash),
);
</script>
