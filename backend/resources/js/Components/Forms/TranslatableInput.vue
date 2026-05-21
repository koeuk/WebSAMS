<template>
    <div>
        <div class="flex items-center justify-between mb-1.5 gap-3">
            <label v-if="label" class="text-[13px] font-medium text-slate-600">
                {{ label }}<span v-if="required" class="text-rose-500 ml-0.5">*</span>
            </label>
            <span v-else></span>
            <!-- Sliding tabs -->
            <div class="relative inline-flex p-1 bg-slate-100 rounded-lg">
                <div
                    class="absolute top-1 bottom-1 left-1 bg-white shadow-sm rounded-md transition-transform duration-300 ease-out"
                    :style="{
                        width: `calc((100% - 8px) / ${locales.length})`,
                        transform: `translateX(${activeIndex * 100}%)`,
                    }"
                />
                <button
                    v-for="loc in locales"
                    :key="loc.code"
                    type="button"
                    @click="active = loc.code"
                    :class="[
                        'relative z-10 cursor-pointer min-w-[52px] px-3 py-1 text-[11px] font-semibold uppercase tracking-wider rounded-md transition-colors',
                        active === loc.code ? 'text-slate-900' : 'text-slate-500 hover:text-slate-700',
                    ]"
                >
                    {{ loc.code }}
                    <span
                        v-if="!(modelValue?.[loc.code] ?? '').trim() && loc.code === 'en'"
                        class="ml-1 text-rose-500"
                    >*</span>
                </button>
            </div>
        </div>

        <input
            :value="modelValue?.[active] ?? ''"
            @input="updateLocale(active, $event.target.value)"
            :type="type"
            :placeholder="placeholderFor(active)"
            class="w-full px-3 py-2 text-[13.5px] text-slate-900 placeholder:text-slate-400 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 transition-colors"
        />
    </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { useEnabledLocales } from '@/Composables/useTranslate'

const enabledLocales = useEnabledLocales()

const props = defineProps({
    modelValue:  { type: Object, default: () => ({}) },
    type:        { type: String, default: 'text' },
    placeholder: { type: [String, Object], default: '' },
    label:       { type: String, default: '' },
    required:    { type: Boolean, default: false },
    locales:     { type: Array, default: null },
})

const emit = defineEmits(['update:modelValue'])

const locales = computed(() => props.locales ?? enabledLocales.value)

const active = ref(locales.value[0]?.code ?? 'en')

watch(locales, (list) => {
    if (!list.find((l) => l.code === active.value)) active.value = list[0]?.code ?? 'en'
})

const activeIndex = computed(() =>
    Math.max(0, locales.value.findIndex((l) => l.code === active.value))
)

const placeholderFor = (code) => {
    if (typeof props.placeholder === 'string') return props.placeholder
    return props.placeholder?.[code] ?? ''
}

const updateLocale = (code, value) => {
    emit('update:modelValue', { ...(props.modelValue ?? {}), [code]: value })
}
</script>
