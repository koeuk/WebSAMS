<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
});
</script>

<template>
    <nav v-if="links && links.length > 3" class="flex items-center justify-center gap-1 mt-6">
        <template v-for="link in links" :key="link.label">
            <Link
                v-if="link.url"
                :href="link.url"
                class="px-3 py-1 text-sm rounded-md border transition-colors"
                :class="link.active
                    ? 'bg-beltei text-white border-beltei'
                    : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                v-html="link.label"
                preserve-scroll
            />
            <span
                v-else
                class="px-3 py-1 text-sm text-gray-400"
                v-html="link.label"
            />
        </template>
    </nav>
</template>
