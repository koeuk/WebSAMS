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
        <span
          v-if="!(modelValue?.[loc.code] ?? '').trim() && loc.code === 'en'"
          class="ml-1 text-rose-500"
        >*</span>
      </button>
    </div>

    <input
      :value="modelValue?.[active] ?? ''"
      @input="updateLocale(active, ($event.target as HTMLInputElement).value)"
      :type="type"
      :placeholder="placeholderFor(active)"
      class="w-full px-3 py-2 text-[13.5px] text-slate-900 placeholder:text-slate-400 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 transition-colors"
    />
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'

const props = defineProps<{
  modelValue: Record<string, string> | null | undefined
  type?: string
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
