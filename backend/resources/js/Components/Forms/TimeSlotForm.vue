<template>
    <ModalForm :open="open" size="lg" @update:open="emit('update:open', $event)">
        <template #title>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                    <Clock class="w-4 h-4 text-slate-600" />
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">{{ isEdit ? __('Edit Time Slot') : __('Create Time Slot') }}</h2>
                    <p class="text-xs text-slate-500">{{ isEdit ? __('Update {name}', { name: timeSlot?.name }) : __('Add a new class time period') }}</p>
                </div>
            </div>
        </template>

        <div class="space-y-4">
            <div>
                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Name') }} <span class="text-rose-500">*</span></Label>
                <Input v-model="form.name" type="text" :placeholder="__('e.g. Morning 1')" @update:model-value="touch('name')" />
                <p v-if="error('name') || form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ error('name') || form.errors.name }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Start Time') }} <span class="text-rose-500">*</span></Label>
                    <Input v-model="form.start_time" type="time" @update:model-value="touch('start_time')" />
                    <p v-if="error('start_time') || form.errors.start_time" class="text-[12px] text-rose-500 mt-1">{{ error('start_time') || form.errors.start_time }}</p>
                </div>
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('End Time') }} <span class="text-rose-500">*</span></Label>
                    <Input v-model="form.end_time" type="time" @update:model-value="touch('end_time')" />
                    <p v-if="error('end_time') || form.errors.end_time" class="text-[12px] text-rose-500 mt-1">{{ error('end_time') || form.errors.end_time }}</p>
                </div>
            </div>
            <div>
                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Type') }}</Label>
                <Select v-model="form.type">
                    <SelectTrigger class="w-full">
                        <SelectValue :placeholder="__('Select type…')" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="t in types" :key="t.value" :value="t.value">
                            {{ t.label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="form.errors.type" class="text-[12px] text-rose-500 mt-1">{{ form.errors.type }}</p>
            </div>
        </div>

        <template #footer>
            <Button variant="outline" type="button" @click="close">{{ __('Cancel') }}</Button>
            <Button type="button" :disabled="!canSubmit || form.processing" @click="submit">
                <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                {{ isEdit ? __('Save Changes') : __('Create Time Slot') }}
            </Button>
        </template>
    </ModalForm>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Clock, Loader2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import ModalForm from '@/Components/ModalForm.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select'
import { useFormValidation } from '@/Composables/useFormValidation'
import { __ } from '@/Composables/useTranslate'

const props = defineProps({
    open:     { type: Boolean, required: true },
    timeSlot: { type: Object, default: null },
    types:    { type: Array, default: () => [] },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.timeSlot)

const form = useForm({ name: '', start_time: '', end_time: '', type: 'morning' })

const { touch, markAllTouched, reset, error } = useFormValidation({
    name:       () => !form.name?.trim() ? __('Name is required') : null,
    start_time: () => !form.start_time ? __('Start time is required') : null,
    end_time:   () => !form.end_time ? __('End time is required') : null,
})

watch(() => props.timeSlot, (ts) => {
    reset()
    if (ts) { form.name = ts.name; form.start_time = ts.start_time?.slice(0, 5) || ''; form.end_time = ts.end_time?.slice(0, 5) || ''; form.type = ts.type || 'morning' }
    else { form.reset(); form.type = 'morning' }
}, { immediate: true })

const canSubmit = computed(() => !!form.name?.trim() && !!form.start_time && !!form.end_time)

const close = () => emit('update:open', false)

const submit = () => {
    if (!markAllTouched()) return
    if (isEdit.value) {
        form.put(route('admin.time-slots.update', props.timeSlot.id), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success(__('Time slot updated successfully')); close() },
            onError: () => { toast.error(__('Failed to update time slot')) },
        })
    } else {
        form.post(route('admin.time-slots.store'), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success(__('Time slot created successfully')); close(); form.reset(); form.type = 'morning' },
            onError: () => { toast.error(__('Failed to create time slot')) },
        })
    }
}
</script>
