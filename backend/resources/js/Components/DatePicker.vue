<template>
    <Popover :open="open" @update:open="(v) => open = v">
        <PopoverTrigger as-child>
            <button
                type="button"
                class="flex items-center gap-2 h-[38px] px-3 py-2 text-[13px] border border-slate-200 rounded-lg bg-white hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 min-w-[160px] w-full"
                :class="modelValue ? 'text-slate-700 font-medium' : 'text-slate-400'"
            >
                <CalendarIcon class="w-4 h-4 shrink-0" :class="modelValue ? 'text-blue-500' : 'text-slate-400'" />
                <span class="flex-1 text-left">{{ displayValue || placeholder }}</span>
            </button>
        </PopoverTrigger>

        <PopoverContent
            :side-offset="4"
            align="start"
            class="z-50 w-[272px] rounded-xl border border-slate-200 bg-white shadow-xl shadow-slate-200/60 p-3"
        >
            <!-- Month navigation -->
            <div class="flex items-center justify-between mb-3">
                <button type="button" @click="prevMonth" class="p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                    <ChevronLeft class="w-4 h-4 text-slate-500" />
                </button>
                <span class="text-[13px] font-semibold text-slate-800">{{ monthLabel }}</span>
                <button type="button" @click="nextMonth" class="p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
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
                    class="h-8 flex items-center justify-center text-[13px] rounded-lg transition-colors"
                    :class="{
                        'text-slate-200 cursor-default pointer-events-none': !cell.current,
                        'bg-blue-500 text-white font-semibold hover:bg-blue-600': cell.date === modelValue,
                        'text-blue-600 font-semibold ring-1 ring-blue-300 hover:bg-blue-50': cell.date === todayStr && cell.date !== modelValue,
                        'text-slate-700 hover:bg-slate-100': cell.current && cell.date !== modelValue && cell.date !== todayStr,
                    }"
                    @click="select(cell.date)"
                >
                    {{ cell.day }}
                </button>
            </div>

            <!-- Footer -->
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                <button type="button" @click="select(todayStr)"
                    class="text-[12px] font-medium text-blue-500 hover:text-blue-700 transition-colors">
                    Today
                </button>
                <button v-if="modelValue" type="button" @click="clear"
                    class="text-[12px] text-slate-400 hover:text-slate-600 transition-colors">
                    Clear
                </button>
            </div>
        </PopoverContent>
    </Popover>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Popover, PopoverTrigger, PopoverContent } from '@/Components/ui/popover';
import { Calendar as CalendarIcon, ChevronLeft, ChevronRight } from 'lucide-vue-next';

const props = defineProps({
    modelValue:  { type: String, default: '' },
    placeholder: { type: String, default: 'Pick a date' },
});
const emit = defineEmits(['update:modelValue']);

const open = ref(false);

const now = new Date();
const viewYear = ref(now.getFullYear());
const viewMonth = ref(now.getMonth());

watch(() => props.modelValue, (val) => {
    if (val) {
        const d = new Date(val + 'T00:00:00');
        viewYear.value = d.getFullYear();
        viewMonth.value = d.getMonth();
    }
}, { immediate: true });

const todayStr = (() => {
    const d = new Date();
    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
})();

const monthLabel = computed(() =>
    new Date(viewYear.value, viewMonth.value, 1).toLocaleDateString('en-US', { month: 'long', year: 'numeric' })
);

const calendarDays = computed(() => {
    const year = viewYear.value;
    const month = viewMonth.value;
    const firstDow = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const prevDays = new Date(year, month, 0).getDate();

    const cells = [];
    for (let i = firstDow - 1; i >= 0; i--)
        cells.push({ day: prevDays - i, current: false, date: null });
    for (let d = 1; d <= daysInMonth; d++) {
        const date = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
        cells.push({ day: d, current: true, date });
    }
    while (cells.length < 42)
        cells.push({ day: cells.length - firstDow - daysInMonth + 1, current: false, date: null });
    return cells;
});

const displayValue = computed(() => {
    if (!props.modelValue) return null;
    return new Date(props.modelValue + 'T00:00:00').toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
});

const prevMonth = () => {
    if (viewMonth.value === 0) { viewMonth.value = 11; viewYear.value--; }
    else viewMonth.value--;
};
const nextMonth = () => {
    if (viewMonth.value === 11) { viewMonth.value = 0; viewYear.value++; }
    else viewMonth.value++;
};

const select = (date) => {
    if (!date) return;
    emit('update:modelValue', date);
    open.value = false;
};

const clear = () => {
    emit('update:modelValue', '');
    open.value = false;
};
</script>
