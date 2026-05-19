<template>
    <div ref="containerRef" class="relative">
        <!-- Trigger -->
        <button
            type="button"
            @click="open = !open"
            class="flex items-center justify-between gap-2 w-full px-3.5 py-2 text-[13.5px] font-medium text-slate-700 bg-[#f8fafc] border border-slate-200 rounded-xl hover:bg-white hover:border-slate-300 transition-all shadow-[0_1px_2px_rgba(0,0,0,0.04)] cursor-pointer"
            :class="open ? 'bg-white border-blue-400 shadow-[0_0_0_3px_rgba(59,130,246,0.10)]' : ''"
        >
            <span :class="!modelValue ? 'text-slate-400' : 'text-slate-800'">{{ selectedLabel }}</span>
            <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <polyline points="6 9 12 15 18 9"/>
            </svg>
        </button>

        <!-- Dropdown panel -->
        <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0 scale-95 translate-y-1"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 translate-y-1"
        >
            <div v-if="open" class="absolute z-50 top-full mt-2 min-w-full w-max max-w-[260px] bg-white rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.10),0_2px_8px_rgba(0,0,0,0.06)] border border-slate-100 overflow-hidden">
                <!-- Search -->
                <div v-if="searchable" class="px-3 pt-3 pb-2">
                    <div class="flex items-center gap-2 px-3 py-2 bg-slate-50 rounded-xl border border-slate-100">
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                        <input v-model="search" type="text" placeholder="Search..." class="text-[13px] text-slate-700 bg-transparent outline-none placeholder-slate-400 w-full" />
                    </div>
                </div>

                <!-- Options -->
                <div class="py-1.5 max-h-56 overflow-y-auto">
                    <div
                        v-for="option in filtered"
                        :key="option.value"
                        @click="select(option.value)"
                        class="flex items-center justify-between px-4 py-2.5 cursor-pointer transition-colors group"
                        :class="isSelected(option.value)
                            ? 'bg-slate-100 text-slate-900'
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                    >
                        <span class="text-[13.5px] font-medium">{{ option.label }}</span>
                        <svg
                            v-if="!isSelected(option.value)"
                            class="w-3.5 h-3.5 text-slate-300 group-hover:text-slate-400 shrink-0 ml-3 transition-colors"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                        >
                            <path d="M7 17L17 7M17 7H7M17 7v10"/>
                        </svg>
                        <svg
                            v-else
                            class="w-3.5 h-3.5 text-blue-500 shrink-0 ml-3"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                        >
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <div v-if="filtered?.length === 0" class="px-4 py-3 text-[13px] text-slate-400 text-center">
                        No options found
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    modelValue: [String, Number],
    options: Array, // [{ value, label }]
    placeholder: { type: String, default: 'Select...' },
    searchable: { type: Boolean, default: false },
});

const emit = defineEmits(['update:modelValue', 'change']);

const open = ref(false);
const search = ref('');
const containerRef = ref(null);

const selectedLabel = computed(() => {
    const found = props.options?.find(o => String(o.value) === String(props.modelValue));
    return found ? found.label : props.placeholder;
});

const isSelected = (val) => String(props.modelValue) === String(val);

const filtered = computed(() => {
    if (!search.value) return props.options;
    return props.options.filter(o => o.label.toLowerCase().includes(search.value.toLowerCase()));
});

const select = (value) => {
    emit('update:modelValue', value);
    emit('change', value);
    open.value = false;
    search.value = '';
};

const handleOutside = (e) => {
    if (containerRef.value && !containerRef.value.contains(e.target)) {
        open.value = false;
        search.value = '';
    }
};

onMounted(() => document.addEventListener('mousedown', handleOutside));
onUnmounted(() => document.removeEventListener('mousedown', handleOutside));
</script>
