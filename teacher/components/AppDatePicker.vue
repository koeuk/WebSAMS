<template>
  <Popover>
    <PopoverTrigger as-child>
      <button
        type="button"
        class="input-modern flex cursor-pointer items-center gap-2"
        :class="props.class"
      >
        <CalendarIcon class="h-4 w-4 text-slate-400 shrink-0" />
        <span :class="calendarValue ? 'text-slate-900' : 'text-slate-400'">
          {{ displayLabel }}
        </span>
      </button>
    </PopoverTrigger>
    <PopoverContent class="w-auto p-0" align="start" :side-offset="6">
      <Calendar v-model="calendarValue" initial-focus />
    </PopoverContent>
  </Popover>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { CalendarDate, DateFormatter, getLocalTimeZone, parseDate, today } from '@internationalized/date'
import type { DateValue } from 'reka-ui'
import { CalendarIcon } from 'lucide-vue-next'
import { Calendar } from '@/components/ui/calendar'
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover'

const props = withDefaults(defineProps<{
  modelValue?: string
  placeholder?: string
  class?: string
}>(), {
  placeholder: 'Pick a date',
})

const emit = defineEmits<{ 'update:modelValue': [value: string] }>()

const df = new DateFormatter('en-US', { dateStyle: 'medium' })

const calendarValue = computed<DateValue | undefined>({
  get() {
    return props.modelValue ? parseDate(props.modelValue) : undefined
  },
  set(val) {
    emit('update:modelValue', val ? val.toString() : '')
  },
})

const displayLabel = computed(() =>
  calendarValue.value
    ? df.format(calendarValue.value.toDate(getLocalTimeZone()))
    : props.placeholder
)
</script>
