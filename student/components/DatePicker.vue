<template>
    <Popover :open="open" @update:open="(v) => open = v">
        <PopoverTrigger as-child>
            <button
                type="button"
                class="flex cursor-pointer items-center gap-2 h-[38px] px-3 py-2 text-[13px] border border-line-strong rounded-lg bg-white hover:bg-paper hover:border-[#c9c0ad] transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1e3a6e]/15 focus:border-beltei min-w-[160px] w-full"
                :class="modelValue ? 'text-slate-700 font-medium' : 'text-slate-400'"
            >
                <CalendarIcon class="w-4 h-4 shrink-0" :class="modelValue ? 'text-beltei-gold' : 'text-slate-400'" />
                <span class="flex-1 text-left">{{ displayValue || placeholder }}</span>
            </button>
        </PopoverTrigger>

        <PopoverContent
            :side-offset="4"
            align="start"
            class="z-50 w-[272px] rounded-xl border border-slate-200 bg-white shadow-xl shadow-slate-200/60 p-3 animate-fade-in"
        >
            <!-- Month navigation -->
            <div class="flex items-center justify-between mb-3">
                <button type="button" @click="prevMonth" class="p-1.5 cursor-pointer rounded-lg hover:bg-slate-100 transition-colors">
                    <ChevronLeft class="w-4 h-4 text-slate-500" />
                </button>
                <span class="text-[13px] font-semibold text-slate-800">{{ monthLabel }}</span>
                <button type="button" @click="nextMonth" class="p-1.5 cursor-pointer rounded-lg hover:bg-slate-100 transition-colors">
                    <ChevronRight class="w-4 h-4 text-slate-500" />
                </button>
            </div>

            <!-- Weekday headers -->
            <div class="grid grid-cols-7 mb-1">
                <div v-for="d in ['Su','Mo','Tu','We','Th','Fr','Sa']" :key="d"
                    class="text-center text-[11px] font-semibold text-slate-400 py-1">{{ d }}</div>
            </div>

            <!-- Day grid -->
            <div class="grid grid-cols-7 gap-0.5">
                <button
                    v-for="(cell, i) in calendarDays"
                    :key="i"
                    type="button"
                    :disabled="!cell.current"
                    class="h-8 flex cursor-pointer items-center justify-center text-[13px] rounded-lg transition-colors"
                    :class="{
                        'text-slate-200 cursor-default pointer-events-none': !cell.current,
                        'bg-beltei text-white font-semibold hover:bg-beltei-dark': cell.date === modelValue,
                        'text-beltei font-semibold ring-1 ring-[#c8a415]/55 hover:bg-[#faf3dc]': cell.date === todayStr && cell.date !== modelValue,
                        'text-slate-700 hover:bg-paper': cell.current && cell.date !== modelValue && cell.date !== todayStr,
                    }"
                    @click="select(cell.date)"
                >
                    {{ cell.day }}
                </button>
            </div>

            <!-- Footer -->
            <div class="mt-3 pt-3 border-t border-line flex items-center justify-between">
                <button type="button" @click="select(todayStr)"
                    class="cursor-pointer text-[12px] font-semibold text-beltei hover:text-beltei-dark transition-colors">
                    Today
                </button>
                <button v-if="modelValue" type="button" @click="clear"
                    class="cursor-pointer text-[12px] text-slate-400 hover:text-slate-600 transition-colors">
                    Clear
                </button>
            </div>
        </PopoverContent>
    </Popover>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Calendar as CalendarIcon, ChevronLeft, ChevronRight } from 'lucide-vue-next'

const props = defineProps<{
    modelValue?: string
    placeholder?: string
}>()
const emit = defineEmits<{ 'update:modelValue': [value: string] }>()

const placeholder = computed(() => props.placeholder ?? 'Pick a date')
const open = ref(false)

const now = new Date()
const viewYear = ref(now.getFullYear())
const viewMonth = ref(now.getMonth())

watch(() => props.modelValue, (val) => {
    if (val) {
        const d = new Date(val + 'T00:00:00')
        viewYear.value = d.getFullYear()
        viewMonth.value = d.getMonth()
    }
}, { immediate: true })

const todayStr = (() => {
    const d = new Date()
    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`
})()

const monthLabel = computed(() =>
    new Date(viewYear.value, viewMonth.value, 1).toLocaleDateString('en-US', { month: 'long', year: 'numeric' })
)

const calendarDays = computed(() => {
    const year = viewYear.value
    const month = viewMonth.value
    const firstDow = new Date(year, month, 1).getDay()
    const daysInMonth = new Date(year, month + 1, 0).getDate()
    const prevDays = new Date(year, month, 0).getDate()

    const cells: { day: number; current: boolean; date: string | null }[] = []
    for (let i = firstDow - 1; i >= 0; i--)
        cells.push({ day: prevDays - i, current: false, date: null })
    for (let d = 1; d <= daysInMonth; d++) {
        const date = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`
        cells.push({ day: d, current: true, date })
    }
    while (cells.length < 42)
        cells.push({ day: cells.length - firstDow - daysInMonth + 1, current: false, date: null })
    return cells
})

const displayValue = computed(() => {
    if (!props.modelValue) return null
    return new Date(props.modelValue + 'T00:00:00').toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
})

const prevMonth = () => {
    if (viewMonth.value === 0) { viewMonth.value = 11; viewYear.value-- }
    else viewMonth.value--
}
const nextMonth = () => {
    if (viewMonth.value === 11) { viewMonth.value = 0; viewYear.value++ }
    else viewMonth.value++
}

const select = (date: string | null) => {
    if (!date) return
    emit('update:modelValue', date)
    open.value = false
}

const clear = () => {
    emit('update:modelValue', '')
    open.value = false
}
</script>
