<script setup>
defineProps({
    show: Boolean,
    title: { type: String, default: 'Confirm' },
    message: { type: String, default: 'Are you sure?' },
});

const emit = defineEmits(['confirm', 'cancel']);
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="emit('cancel')" />
                <div class="relative bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm border border-slate-200 animate-fade-in-up">
                    <div class="flex items-start gap-4 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-[15px] font-semibold text-slate-900">{{ title }}</h3>
                            <p class="text-sm text-slate-500 mt-1 leading-relaxed">{{ message }}</p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2.5">
                        <button
                            @click="emit('cancel')"
                            class="btn-secondary text-sm py-2 px-4"
                        >
                            Cancel
                        </button>
                        <button
                            @click="emit('confirm')"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-rose-500 rounded-[10px] hover:bg-rose-600 transition-all shadow-sm"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
