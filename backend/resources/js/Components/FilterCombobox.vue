<template>
    <PopoverRoot :open="open" @update:open="handleOpen">
        <PopoverTrigger as-child>
            <button
                type="button"
                class="flex items-center justify-between gap-2 h-[38px] min-w-[140px] px-3 py-2 text-[13px] font-medium border border-slate-200 rounded-lg bg-white text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400"
                :class="{ 'border-blue-400 text-blue-700 bg-blue-50/60': isSelected }"
            >
                <span class="truncate max-w-[140px]">{{ selectedLabel }}</span>
                <ChevronsUpDown class="w-3.5 h-3.5 shrink-0 text-slate-400" />
            </button>
        </PopoverTrigger>

        <PopoverContent
            :side-offset="4"
            align="start"
            class="z-50 w-[200px] rounded-xl border border-slate-200 bg-white shadow-xl shadow-slate-200/60 p-0 overflow-hidden animate-fade-in"
        >
            <!-- Search input -->
            <div class="px-2 pt-2 pb-1 border-b border-slate-100">
                <div class="flex items-center gap-1.5 px-2 py-1.5 rounded-md bg-slate-50 border border-slate-200">
                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z"/>
                    </svg>
                    <input
                        ref="searchInput"
                        v-model="search"
                        type="text"
                        placeholder="Search..."
                        class="flex-1 bg-transparent text-[12px] text-slate-700 placeholder-slate-400 outline-none min-w-0"
                    />
                </div>
            </div>

            <!-- Options list -->
            <div class="max-h-[200px] overflow-y-auto py-1">
                <!-- Clear / All option -->
                <button
                    type="button"
                    class="w-full flex items-center justify-between gap-2 px-3 py-2 text-[13px] text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition-colors"
                    @click="select('')"
                >
                    <span>{{ placeholder }}</span>
                    <Check v-if="!isSelected" class="w-3.5 h-3.5 text-blue-500" />
                </button>

                <div v-if="filtered.length === 0" class="px-3 py-4 text-center text-[12px] text-slate-400">
                    No results found.
                </div>

                <button
                    v-for="opt in filtered"
                    :key="opt.value"
                    type="button"
                    class="w-full flex items-center justify-between gap-2 px-3 py-2 text-[13px] text-slate-700 hover:bg-slate-50 transition-colors"
                    :class="{ 'bg-blue-50/80 text-blue-700 font-medium': String(modelValue) === String(opt.value) }"
                    @click="select(opt.value)"
                >
                    <span class="truncate">{{ opt.label }}</span>
                    <Check v-if="String(modelValue) === String(opt.value)" class="w-3.5 h-3.5 text-blue-500 shrink-0" />
                </button>
            </div>
        </PopoverContent>
    </PopoverRoot>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue';
import { PopoverRoot, PopoverTrigger, PopoverContent } from 'reka-ui';
import { Check, ChevronsUpDown } from 'lucide-vue-next';

const props = defineProps({
    modelValue: { type: [String, Number], default: '' },
    options: { type: Array, default: () => [] }, // [{ value, label }]
    placeholder: { type: String, default: 'Select...' },
});
const emit = defineEmits(['update:modelValue']);

const open = ref(false);
const search = ref('');
const searchInput = ref(null);

const selectedLabel = computed(() => {
    const opt = props.options.find(o => String(o.value) === String(props.modelValue));
    return opt?.label ?? props.placeholder;
});

const isSelected = computed(() => props.modelValue !== '' && props.modelValue !== null && props.modelValue !== undefined);

const filtered = computed(() => {
    if (!search.value) return props.options;
    const q = search.value.toLowerCase();
    return props.options.filter(o => o.label.toLowerCase().includes(q));
});

const handleOpen = async (val) => {
    open.value = val;
    if (val) {
        search.value = '';
        await nextTick();
        searchInput.value?.focus();
    }
};

const select = (val) => {
    emit('update:modelValue', val);
    open.value = false;
    search.value = '';
};
</script>
