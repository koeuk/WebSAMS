<template>
    <Popover :open="open" @update:open="open = $event">
        <PopoverTrigger as-child>
            <button
                type="button"
                class="flex cursor-pointer items-center justify-between gap-2 h-[38px] min-w-[140px] px-3 py-2 text-[13px] font-medium border border-slate-200 rounded-lg bg-white text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400"
                :class="{ 'border-blue-400 text-blue-700 bg-blue-50/60': isSelected }"
            >
                <span class="truncate max-w-[140px]">{{ selectedLabel }}</span>
                <ChevronsUpDown class="w-3.5 h-3.5 shrink-0 text-slate-400" />
            </button>
        </PopoverTrigger>

        <PopoverContent align="start" :side-offset="4" class="w-[200px] p-0">
            <Command>
                <CommandInput :placeholder="`Search...`" class="h-9" />
                <CommandList>
                    <CommandEmpty>No results found.</CommandEmpty>
                    <CommandGroup>
                        <CommandItem value="__all__" @select="select('')">
                            <span class="text-slate-500">{{ placeholder }}</span>
                            <Check v-if="!isSelected" class="ml-auto w-3.5 h-3.5 text-blue-500" />
                        </CommandItem>
                        <CommandItem
                            v-for="opt in options"
                            :key="opt.value"
                            :value="String(opt.value)"
                            @select="select(opt.value)"
                        >
                            <span class="truncate" :class="String(modelValue) === String(opt.value) ? 'font-medium text-blue-700' : ''">{{ opt.label }}</span>
                            <Check v-if="String(modelValue) === String(opt.value)" class="ml-auto w-3.5 h-3.5 text-blue-500 shrink-0" />
                        </CommandItem>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Check, ChevronsUpDown } from 'lucide-vue-next';
import { Popover, PopoverTrigger, PopoverContent } from '@/Components/ui/popover';
import { Command, CommandInput, CommandList, CommandEmpty, CommandGroup, CommandItem } from '@/Components/ui/command';

const props = defineProps({
    modelValue: { type: [String, Number], default: '' },
    options:    { type: Array, default: () => [] },
    placeholder: { type: String, default: 'Select...' },
});
const emit = defineEmits(['update:modelValue']);

const open = ref(false);

const selectedLabel = computed(() => {
    const opt = props.options.find(o => String(o.value) === String(props.modelValue));
    return opt?.label ?? props.placeholder;
});

const isSelected = computed(() =>
    props.modelValue !== '' && props.modelValue !== null && props.modelValue !== undefined
);

const select = (val) => {
    emit('update:modelValue', val);
    open.value = false;
};
</script>
