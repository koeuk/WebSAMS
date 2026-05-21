<template>
    <ModalForm :open="open" size="lg" @update:open="emit('update:open', $event)">
        <template #title>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                    <CalendarClock class="w-4 h-4 text-slate-600" />
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">{{ isEdit ? __('Edit Schedule') : __('Create Schedule') }}</h2>
                    <p class="text-xs text-slate-500">{{ isEdit ? __('Update schedule entry') : __('Add a new schedule entry') }}</p>
                </div>
            </div>
        </template>

        <div class="space-y-4">
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">{{ __('Class – Subject') }} <span class="text-rose-500">*</span></Label>
                <Select v-model="form.class_subject_id" @update:model-value="touch('class_subject_id')">
                    <SelectTrigger class="w-full">
                        <SelectValue :placeholder="__('Select class & subject')" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="cs in classSubjects" :key="cs.id" :value="String(cs.id)">
                            {{ cs.subject?.name }} — {{ cs.school_class?.name }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="error('class_subject_id') || form.errors.class_subject_id" class="text-[12px] text-rose-500 mt-1">{{ error('class_subject_id') || form.errors.class_subject_id }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">{{ __('Time Slot') }} <span class="text-rose-500">*</span></Label>
                    <Select v-model="form.time_slot_id" @update:model-value="touch('time_slot_id')">
                        <SelectTrigger class="w-full">
                            <SelectValue :placeholder="__('Select time slot')" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="ts in timeSlots" :key="ts.id" :value="String(ts.id)">
                                {{ ts.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="error('time_slot_id') || form.errors.time_slot_id" class="text-[12px] text-rose-500 mt-1">{{ error('time_slot_id') || form.errors.time_slot_id }}</p>
                </div>
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">{{ __('Day') }} <span class="text-rose-500">*</span></Label>
                    <Select v-model="form.day_of_week" @update:model-value="touch('day_of_week')">
                        <SelectTrigger class="w-full">
                            <SelectValue :placeholder="__('Select day')" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="d in days" :key="d.value" :value="d.value">
                                {{ d.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="error('day_of_week') || form.errors.day_of_week" class="text-[12px] text-rose-500 mt-1">{{ error('day_of_week') || form.errors.day_of_week }}</p>
                </div>
            </div>
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">{{ __('Room') }}</Label>
                <Input v-model="form.room" type="text" :placeholder="__('e.g. Room 201')" />
            </div>
        </div>

        <template #footer>
            <Button variant="outline" type="button" @click="close">{{ __('Cancel') }}</Button>
            <Button type="button" :disabled="!canSubmit || form.processing" @click="submit">
                <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                {{ isEdit ? __('Save Changes') : __('Create Schedule') }}
            </Button>
        </template>
    </ModalForm>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { CalendarClock, Loader2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import ModalForm from '@/Components/ModalForm.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select'
import { useFormValidation } from '@/Composables/useFormValidation'
import { __ } from '@/Composables/useTranslate'

const props = defineProps({
    open:          { type: Boolean, required: true },
    schedule:      { type: Object, default: null },
    classSubjects: { type: Array, default: () => [] },
    timeSlots:     { type: Array, default: () => [] },
    days:          { type: Array, default: () => [] },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.schedule)

const form = useForm({ class_subject_id: '', time_slot_id: '', day_of_week: '', room: '' })

const { touch, markAllTouched, reset, error } = useFormValidation({
    class_subject_id: () => !form.class_subject_id ? __('Class – Subject is required') : null,
    time_slot_id:     () => !form.time_slot_id ? __('Time slot is required') : null,
    day_of_week:      () => !form.day_of_week ? __('Day is required') : null,
})

watch(() => props.schedule, (s) => {
    reset()
    if (s) { form.class_subject_id = String(s.class_subject_id); form.time_slot_id = String(s.time_slot_id); form.day_of_week = s.day_of_week; form.room = s.room || '' }
    else form.reset()
}, { immediate: true })

const canSubmit = computed(() => !!form.class_subject_id && !!form.time_slot_id && !!form.day_of_week)

const close = () => emit('update:open', false)

const submit = () => {
    if (!markAllTouched()) return
    if (isEdit.value) {
        form.put(route('admin.schedules.update', props.schedule.id), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { close() },
            onError: () => { toast.error(__('Failed to update schedule')) },
        })
    } else {
        form.post(route('admin.schedules.store'), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { close(); form.reset() },
            onError: () => { toast.error(__('Failed to create schedule')) },
        })
    }
}
</script>
