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
                </button>
            </div>
        </div>

        <!-- Keep all editors mounted; only the active one is visible. -->
        <div class="relative">
            <div v-for="loc in locales" :key="loc.code" v-show="active === loc.code">
                <RichTextEditor
                    :model-value="modelValue?.[loc.code] ?? ''"
                    @update:model-value="(v) => updateLocale(loc.code, v)"
                    :placeholder="placeholderFor(loc.code)"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import RichTextEditor from '@/Components/RichTextEditor.vue'
import { useEnabledLocales } from '@/Composables/useTranslate'

const enabledLocales = useEnabledLocales()

const props = defineProps({
    modelValue:  { type: Object, default: () => ({}) },
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
