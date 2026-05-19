<template>
    <Sheet :open="open" @update:open="emit('update:open', $event)">
        <SheetContent side="right" :style="widthStyle" class="overflow-y-auto flex flex-col gap-0 p-1 !w-auto !max-w-none">
            <div class="px-6 py-5 border-b">
                <h2 class="text-lg font-semibold text-slate-900 tracking-tight">{{ title }}</h2>
                <p v-if="description" class="text-sm text-slate-500 mt-0.5">{{ description }}</p>
            </div>
            <div class="flex-1 px-4 py-5 overflow-y-auto">
                <slot />
            </div>
        </SheetContent>
    </Sheet>
</template>

<script setup>
import { computed } from 'vue';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetDescription } from '@/Components/ui/sheet';

const props = defineProps({
    open: { type: Boolean, required: true },
    title: { type: String, required: true },
    description: { type: String, default: '' },
    size: { type: String, default: 'md' },
    width: { type: String, default: '' },
});

const emit = defineEmits(['update:open']);

const sizeMap = {
    sm:  '420px',
    md:  '560px',
    lg:  '720px',
    xl:  '900px',
    '2xl': '1100px',
};

const widthStyle = computed(() => {
    const w = props.width || sizeMap[props.size] || '560px';
    return { width: w, maxWidth: '95vw' };
});
</script>
