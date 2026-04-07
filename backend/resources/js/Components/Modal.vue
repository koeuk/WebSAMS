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
        <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="fixed inset-0 bg-black/50" @click="emit('cancel')" />
            <div class="relative bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ title }}</h3>
                <p class="text-sm text-gray-600 mb-6">{{ message }}</p>
                <div class="flex justify-end gap-3">
                    <button
                        @click="emit('cancel')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button
                        @click="emit('confirm')"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700"
                    >
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </Teleport>
</template>
