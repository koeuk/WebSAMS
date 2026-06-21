<template>
  <Popover v-model:open="open">
    <PopoverTrigger as-child>
      <button
        type="button"
        role="combobox"
        :aria-expanded="open"
        class="select-modern flex cursor-pointer items-center justify-between gap-2"
        :class="props.class"
      >
        <span :class="modelValue ? 'text-slate-900' : 'text-slate-400'">{{ selectedLabel }}</span>
        <ChevronsUpDown class="h-4 w-4 shrink-0 text-slate-400" />
      </button>
    </PopoverTrigger>
    <PopoverContent class="p-0 w-auto min-w-[var(--reka-popover-trigger-width)] max-w-[calc(100vw-2rem)]" align="start" :side-offset="6">
      <Command>
        <CommandInput :placeholder="searchPlaceholder" class="h-9" />
        <CommandList>
          <CommandEmpty>{{ emptyText }}</CommandEmpty>
          <CommandGroup>
            <CommandItem
              v-for="option in options"
              :key="option.value"
              :value="option.label"
              class="cursor-pointer whitespace-nowrap"
              @select="select(option.value)"
            >
              <Check
                class="mr-2 h-4 w-4 shrink-0 transition-opacity"
                :class="modelValue === option.value ? 'opacity-100' : 'opacity-0'"
              />
              {{ option.label }}
            </CommandItem>
          </CommandGroup>
        </CommandList>
      </Command>
    </PopoverContent>
  </Popover>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Check, ChevronsUpDown } from 'lucide-vue-next'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'
import {
  Command,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList,
} from '@/components/ui/command'

interface Option {
  value: string
  label: string
}

const props = withDefaults(defineProps<{
  modelValue: string
  options: Option[]
  placeholder?: string
  searchPlaceholder?: string
  emptyText?: string
  class?: string
}>(), {
  placeholder: 'Select...',
  searchPlaceholder: 'Search...',
  emptyText: 'No results found.',
})

const emit = defineEmits<{ 'update:modelValue': [value: string] }>()

const open = ref(false)

const selectedLabel = computed(
  () => props.options.find(o => o.value === props.modelValue)?.label ?? props.placeholder
)

function select(value: string) {
  emit('update:modelValue', value)
  open.value = false
}
</script>
