<template><span class="hidden" /></template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import { watch, nextTick, onMounted } from 'vue';
import { toast } from 'vue-sonner';

const page = usePage();

const fire = (flash) => {
    if (!flash) return;
    if (flash.success) toast.success(flash.success);
    if (flash.error)   toast.error(flash.error);
};

onMounted(() => nextTick(() => fire(page.props.flash)));

watch(
    () => [page.props.flash?.success, page.props.flash?.error],
    ([s, e], [ps, pe]) => {
        if (s && s !== ps) toast.success(s);
        if (e && e !== pe) toast.error(e);
    },
);
</script>
