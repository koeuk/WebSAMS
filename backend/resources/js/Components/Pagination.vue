<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
});
</script>

<template>
    <nav v-if="links && links.length > 3" class="flex items-center justify-center gap-1 mt-6">
        <template v-for="link in links">
            <Link
                v-if="link.url"
                :key="`a-${link.label}`"
                :href="link.url"
                class="px-3.5 py-1.5 text-sm font-medium rounded-lg border transition-colors"
                :class="link.active
                    ? 'bg-beltei text-white border-beltei shadow-sm'
                    : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                preserve-scroll
            ><span v-html="link.label" /></Link>
            <span
                v-else
                :key="`b-${link.label}`"
                class="px-3.5 py-1.5 text-sm text-slate-300"
                v-html="link.label"
            />
        </template>
    </nav>
</template>
