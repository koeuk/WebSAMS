<template>
  <div>
    <!-- Sliding tabs -->
    <div class="relative inline-flex p-1 mb-1.5 bg-slate-100 rounded-lg">
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

<script setup lang="ts">
import { computed, ref } from 'vue'
import RichTextEditor from '@/components/RichTextEditor.vue'

const props = defineProps<{
  modelValue: Record<string, string> | null | undefined
  placeholder?: string | Record<string, string>
  locales?: { code: string; name: string }[]
}>()

const locales = computed(() => props.locales ?? [
  { code: 'km', name: 'ខ្មែរ' },
  { code: 'en', name: 'English' },
  { code: 'zh', name: '中文' },
])

const emit = defineEmits<{ 'update:modelValue': [value: Record<string, string>] }>()

const active = ref(locales.value[0]?.code ?? 'en')

const activeIndex = computed(() =>
  Math.max(0, locales.value.findIndex((l) => l.code === active.value))
)

const placeholderFor = (code: string) => {
  if (typeof props.placeholder === 'string') return props.placeholder
  return props.placeholder?.[code] ?? ''
}

const updateLocale = (code: string, value: string) => {
  emit('update:modelValue', { ...(props.modelValue ?? {}), [code]: value })
}
</script>
