<template>
    <Popover :open="open" @update:open="open = $event">
        <PopoverTrigger as-child>
            <button
                type="button"
                class="flex items-center justify-between gap-2 w-full px-3.5 py-2 text-[13.5px] font-medium text-slate-700 bg-[#f8fafc] border border-slate-200 rounded-xl hover:bg-white hover:border-slate-300 transition-all shadow-[0_1px_2px_rgba(0,0,0,0.04)] cursor-pointer"
                :class="open ? 'bg-white border-blue-400 shadow-[0_0_0_3px_rgba(59,130,246,0.10)]' : ''"
            >
                <span :class="!modelValue ? 'text-slate-400' : 'text-slate-800'">{{ selectedLabel }}</span>
                <ChevronDown class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''" />
            </button>
        </PopoverTrigger>

        <PopoverContent align="start" :side-offset="4" class="w-[var(--reka-popover-trigger-width)] min-w-[160px] p-0">
            <Command>
                <CommandInput v-if="searchable" placeholder="Search..." class="h-9" />
                <CommandList>
                    <CommandEmpty>No options found.</CommandEmpty>
                    <CommandGroup>
                        <CommandItem
                            v-for="opt in options"
                            :key="opt.value"
                            :value="String(opt.value)"
                            @select="select(opt.value)"
                        >
                            <span class="text-[13.5px] font-medium" :class="isSelected(opt.value) ? 'text-slate-900' : 'text-slate-600'">{{ opt.label }}</span>
                            <Check v-if="isSelected(opt.value)" class="ml-auto w-3.5 h-3.5 text-blue-500 shrink-0" />
                        </CommandItem>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Check, ChevronDown } from 'lucide-vue-next';
import { Popover, PopoverTrigger, PopoverContent } from '@/Components/ui/popover';
import { Command, CommandInput, CommandList, CommandEmpty, CommandGroup, CommandItem } from '@/Components/ui/command';

const props = defineProps({
    modelValue:  { type: [String, Number], default: '' },
    options:     { type: Array, default: () => [] },
    placeholder: { type: String, default: 'Select...' },
    searchable:  { type: Boolean, default: false },
});
const emit = defineEmits(['update:modelValue', 'change']);

const open = ref(false);

const selectedLabel = computed(() => {
    const found = props.options?.find(o => String(o.value) === String(props.modelValue));
    return found ? found.label : props.placeholder;
});

const isSelected = (val) => String(props.modelValue) === String(val);

const select = (value) => {
    emit('update:modelValue', value);
    emit('change', value);
    open.value = false;
};
</script>
