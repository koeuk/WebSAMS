<template>
    <Dialog :open="show" @update:open="(v) => !v && emit('cancel')">
        <DialogContent class="max-w-sm" @pointer-down-outside.prevent @interact-outside.prevent>
            <DialogHeader>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center shrink-0">
                        <AlertTriangle class="w-5 h-5 text-rose-500" />
                    </div>
                    <div class="pt-0.5">
                        <DialogTitle class="text-[15px] font-semibold text-slate-900 leading-snug">{{ title }}</DialogTitle>
                        <DialogDescription class="text-sm text-slate-500 mt-1 leading-relaxed">{{ message }}</DialogDescription>
                    </div>
                </div>
            </DialogHeader>
            <DialogFooter class="gap-2">
                <Button variant="outline" @click="emit('cancel')">Cancel</Button>
                <Button variant="destructive" @click="emit('confirm')">Delete</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<script setup>
import { AlertTriangle } from 'lucide-vue-next'
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter } from '@/Components/ui/dialog'
import { Button } from '@/Components/ui/button'

defineProps({
    show:    Boolean,
    title:   { type: String, default: 'Confirm' },
    message: { type: String, default: 'Are you sure?' },
})
const emit = defineEmits(['confirm', 'cancel'])
</script>
