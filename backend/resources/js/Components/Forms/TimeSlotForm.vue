<template>
    <ModalForm :open="open" :title="isEdit ? 'Edit Time Slot' : 'Create Time Slot'"
        :description="isEdit ? `Update ${timeSlot?.name}` : 'Add a new class time period.'"
        size="md" @update:open="emit('update:open', $event)">
        <form @submit.prevent="submit" class="space-y-4 py-2">
            <div>
                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                <Input v-model="form.name" type="text" required placeholder="e.g. Morning 1" />
                <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Start Time *</Label>
                    <Input v-model="form.start_time" type="time" required />
                    <p v-if="form.errors.start_time" class="text-[12px] text-rose-500 mt-1">{{ form.errors.start_time }}</p>
                </div>
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">End Time *</Label>
                    <Input v-model="form.end_time" type="time" required />
                    <p v-if="form.errors.end_time" class="text-[12px] text-rose-500 mt-1">{{ form.errors.end_time }}</p>
                </div>
            </div>
            <div>
                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Type</Label>
                <Select v-model="form.type">
                    <SelectTrigger class="w-full">
                        <SelectValue placeholder="Select type..." />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem value="morning">Morning</SelectItem>
                        <SelectItem value="afternoon">Afternoon</SelectItem>
                        <SelectItem value="evening">Evening</SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="form.errors.type" class="text-[12px] text-rose-500 mt-1">{{ form.errors.type }}</p>
            </div>
            <div class="flex justify-end gap-2 pt-4">
                <Button variant="outline" type="button" @click="close">Cancel</Button>
                <Button type="submit" :disabled="form.processing" class="flex items-center gap-2">
                    <svg v-if="!isEdit" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                    {{ form.processing ? (isEdit ? 'Saving...' : 'Creating...') : (isEdit ? 'Save Changes' : 'Create Time Slot') }}
                </Button>
            </div>
        </form>
    </ModalForm>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import ModalForm from '@/Components/ModalForm.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select'

const props = defineProps({
    open: { type: Boolean, required: true },
    timeSlot: { type: Object, default: null },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.timeSlot)

const form = useForm({ name: '', start_time: '', end_time: '', type: 'morning' })

watch(() => props.timeSlot, (ts) => {
    if (ts) {
        form.name = ts.name
        form.start_time = ts.start_time?.slice(0, 5) || ''
        form.end_time = ts.end_time?.slice(0, 5) || ''
        form.type = ts.type || 'morning'
    } else {
        form.reset()
        form.type = 'morning'
    }
}, { immediate: true })

const close = () => emit('update:open', false)
const submit = () => {
    if (isEdit.value) {
        form.put(`/admin/time-slots/${props.timeSlot.id}`, { onSuccess: close })
    } else {
        form.post('/admin/time-slots', { onSuccess: () => { close(); form.reset(); form.type = 'morning' } })
    }
}
</script>
