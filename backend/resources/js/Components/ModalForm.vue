<template>
    <Sheet :open="open" @update:open="emit('update:open', $event)">
        <SheetContent side="right" :style="widthStyle" class="flex flex-col gap-0 p-0 !w-auto !max-w-none">
            <!-- Title slot -->
            <div class="px-6 py-5 border-b shrink-0">
                <slot name="title">
                    <h2 class="text-lg font-semibold text-slate-900 tracking-tight">{{ title }}</h2>
                    <p v-if="description" class="text-sm text-slate-500 mt-0.5">{{ description }}</p>
                </slot>
            </div>

            <!-- Body slot -->
            <div class="flex-1 px-6 py-5 overflow-y-auto">
                <slot />
            </div>

            <!-- Footer slot -->
            <div v-if="$slots.footer" class="px-6 py-4 border-t bg-slate-50/80 shrink-0 flex items-center justify-end gap-2">
                <slot name="footer" />
            </div>
        </SheetContent>
    </Sheet>
</template>

<script setup>
import { computed } from 'vue';
import { Sheet, SheetContent } from '@/Components/ui/sheet';

const props = defineProps({
    open:        { type: Boolean, required: true },
    title:       { type: String, default: '' },
    description: { type: String, default: '' },
    loading:     { type: Boolean, default: false },
    size:        { type: String, default: 'md' },
    width:       { type: String, default: '' },
});

const emit = defineEmits(['update:open']);

const sizeMap = {
    sm:    '420px',
    md:    '560px',
    lg:    '720px',
    xl:    '900px',
    '2xl': '1100px',
};

const widthStyle = computed(() => {
    const w = props.width || sizeMap[props.size] || '560px';
    return { width: w, maxWidth: '95vw' };
});
</script>
