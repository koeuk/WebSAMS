<template>
    <div
        v-if="pagination"
        class="bg-white border border-slate-100 rounded-2xl px-5 py-3 mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
    >
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

        <nav class="flex items-center gap-1">
            <!-- Prev -->
            <Link
                v-if="prevUrl"
                :href="prevUrl"
                class="w-8 h-8 inline-flex items-center justify-center rounded-lg border bg-white text-slate-600 border-slate-200 hover:bg-slate-50 transition-colors"
                preserve-scroll
                preserve-state
                aria-label="Previous page"
            >
                <ChevronLeft class="w-4 h-4" />
            </Link>
            <span
                v-else
                class="w-8 h-8 inline-flex items-center justify-center rounded-lg border bg-slate-50 text-slate-300 border-slate-100 cursor-not-allowed"
                aria-disabled="true"
            >
                <ChevronLeft class="w-4 h-4" />
            </span>

            <!-- Numbered pages (only when there's more than 1 page) -->
            <template v-if="numberLinks.length > 1">
                <template v-for="link in numberLinks">
                    <Link
                        v-if="link.url"
                        :key="`a-${link.label}`"
                        :href="link.url"
                        class="min-w-8 h-8 px-2.5 inline-flex items-center justify-center rounded-lg border text-sm font-medium transition-colors"
                        :class="link.active
                            ? 'bg-beltei text-white border-beltei shadow-sm'
                            : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                        preserve-scroll
                        preserve-state
                    ><span v-html="link.label" /></Link>
                    <span
                        v-else
                        :key="`b-${link.label}`"
                        class="min-w-8 h-8 px-2.5 inline-flex items-center justify-center text-sm text-slate-300"
                        v-html="link.label"
                    />
                </template>
            </template>

            <!-- Next -->
            <Link
                v-if="nextUrl"
                :href="nextUrl"
                class="w-8 h-8 inline-flex items-center justify-center rounded-lg border bg-white text-slate-600 border-slate-200 hover:bg-slate-50 transition-colors"
                preserve-scroll
                preserve-state
                aria-label="Next page"
            >
                <ChevronRight class="w-4 h-4" />
            </Link>
            <span
                v-else
                class="w-8 h-8 inline-flex items-center justify-center rounded-lg border bg-slate-50 text-slate-300 border-slate-100 cursor-not-allowed"
                aria-disabled="true"
            >
                <ChevronRight class="w-4 h-4" />
            </span>
        </nav>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select'

const props = defineProps({
    pagination: { type: Object, required: true },
    options:    { type: Array,  default: () => [10, 15, 20, 50, 100] },
})

const links = computed(() => props.pagination?.links || [])
const prevUrl = computed(() => links.value[0]?.url || null)
const nextUrl = computed(() => links.value[links.value.length - 1]?.url || null)
const numberLinks = computed(() => links.value.slice(1, -1))

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
