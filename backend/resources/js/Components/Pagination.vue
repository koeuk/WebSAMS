<template>
    <div v-if="pagination" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6">
        <div class="flex flex-wrap items-center gap-4 text-[13px] text-slate-500">
            <span v-if="pagination.total > 0">
                Showing
                <span class="font-semibold text-slate-700">{{ pagination.from }}</span>
                to
                <span class="font-semibold text-slate-700">{{ pagination.to }}</span>
                of
                <span class="font-semibold text-slate-700">{{ pagination.total }}</span>
            </span>
            <span v-else>No results</span>

            <div class="flex items-center gap-2">
                <span>Per page</span>
                <Select :model-value="String(pagination.per_page)" @update:model-value="onPerPageChange">
                    <SelectTrigger class="h-8 w-[78px]">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="opt in options" :key="opt" :value="String(opt)">{{ opt }}</SelectItem>
                    </SelectContent>
                </Select>
            </div>
        </div>

        <nav v-if="pagination.links && pagination.links.length > 3" class="flex items-center justify-center gap-1">
            <template v-for="link in pagination.links">
                <Link
                    v-if="link.url"
                    :key="`a-${link.label}`"
                    :href="link.url"
                    class="px-3.5 py-1.5 text-sm font-medium rounded-lg border transition-colors"
                    :class="link.active
                        ? 'bg-beltei text-white border-beltei shadow-sm'
                        : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                    preserve-scroll
                    preserve-state
                ><span v-html="link.label" /></Link>
                <span
                    v-else
                    :key="`b-${link.label}`"
                    class="px-3.5 py-1.5 text-sm text-slate-300"
                    v-html="link.label"
                />
            </template>
        </nav>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select'

defineProps({
    pagination: { type: Object, required: true },
    options:    { type: Array,  default: () => [10, 15, 20, 50, 100] },
})

const onPerPageChange = (value) => {
    const params = Object.fromEntries(new URLSearchParams(window.location.search))
    router.visit(window.location.pathname, {
        data: { ...params, per_page: value, page: 1 },
        preserveScroll: true,
        preserveState: true,
        replace: true,
    })
}
</script>
