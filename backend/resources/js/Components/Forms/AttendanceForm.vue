<template>
    <ModalForm :open="open" size="lg" @update:open="emit('update:open', $event)">
        <template #title>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                    <ClipboardList class="w-4 h-4 text-slate-600" />
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">{{ isEdit ? __('Edit Attendance') : __('Create Attendance') }}</h2>
                    <p class="text-xs text-slate-500">{{ isEdit ? __('Update attendance record') : __('Record a new attendance entry') }}</p>
                </div>
            </div>
        </template>

        <div class="space-y-4">
            <!-- Class - Subject -->
            <div>
                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Class - Subject (Teacher)') }} <span class="text-rose-500">*</span></Label>
                <Select v-model="form.class_subject_id" @update:model-value="touch('class_subject_id')">
                    <SelectTrigger class="w-full"><SelectValue :placeholder="__('Select')" /></SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="cs in classSubjects" :key="cs.id" :value="String(cs.id)">{{ csLabel(cs) }}</SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="error('class_subject_id') || form.errors.class_subject_id" class="text-[12px] text-rose-500 mt-1">{{ error('class_subject_id') || form.errors.class_subject_id }}</p>
            </div>

            <!-- Student -->
            <div>
                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Student') }} <span class="text-rose-500">*</span></Label>
                <Select v-model="form.student_id" @update:model-value="touch('student_id')">
                    <SelectTrigger class="w-full"><SelectValue :placeholder="__('Select Student')" /></SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="s in students" :key="s.id" :value="String(s.id)">{{ s.name }} ({{ s.email }})</SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="error('student_id') || form.errors.student_id" class="text-[12px] text-rose-500 mt-1">{{ error('student_id') || form.errors.student_id }}</p>
            </div>

            <!-- Date + Time Slot -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Date') }} <span class="text-rose-500">*</span></Label>
                    <DatePicker v-model="form.date" :placeholder="__('Select date')" @update:model-value="touch('date')" />
                    <p v-if="error('date') || form.errors.date" class="text-[12px] text-rose-500 mt-1">{{ error('date') || form.errors.date }}</p>
                </div>
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Time Slot') }} <span class="text-rose-500">*</span></Label>
                    <Select v-model="form.time_slot_id" @update:model-value="touch('time_slot_id')">
                        <SelectTrigger class="w-full"><SelectValue :placeholder="__('Select Time Slot')" /></SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="ts in timeSlots" :key="ts.id" :value="String(ts.id)">{{ ts.name }} ({{ ts.start_time?.slice(0,5) }} - {{ ts.end_time?.slice(0,5) }})</SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="error('time_slot_id') || form.errors.time_slot_id" class="text-[12px] text-rose-500 mt-1">{{ error('time_slot_id') || form.errors.time_slot_id }}</p>
                </div>
            </div>

            <!-- Status -->
            <div>
                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Status') }} <span class="text-rose-500">*</span></Label>
                <Select v-model="form.status" @update:model-value="touch('status')">
                    <SelectTrigger class="w-full"><SelectValue :placeholder="__('Select Status')" /></SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="s in statuses" :key="s.value" :value="s.value">{{ s.label }}</SelectItem>
                    </SelectContent>
                </Select>
            </div>

            <!-- Remarks -->
            <div>
                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Remarks') }}</Label>
                <RichTextEditor v-model="form.remarks" :placeholder="__('Optional remarks…')" />
            </div>
        </div>

        <template #footer>
            <Button variant="outline" type="button" @click="close">{{ __('Cancel') }}</Button>
            <Button type="button" :disabled="!canSubmit || form.processing" @click="submit">
                <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                {{ isEdit ? __('Save Changes') : __('Create Attendance') }}
            </Button>
        </template>
    </ModalForm>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { ClipboardList, Loader2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import ModalForm from '@/Components/ModalForm.vue'
import DatePicker from '@/Components/DatePicker.vue'
import RichTextEditor from '@/Components/RichTextEditor.vue'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select'
import { useFormValidation } from '@/Composables/useFormValidation'
import { __ } from '@/Composables/useTranslate'

const props = defineProps({
    open:          { type: Boolean, required: true },
    record:        { type: Object, default: null },
    classSubjects: { type: Array, default: () => [] },
    students:      { type: Array, default: () => [] },
    timeSlots:     { type: Array, default: () => [] },
    statuses:      { type: Array, default: () => [] },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.record)

const today = () => new Date().toISOString().split('T')[0]

const form = useForm({
    class_subject_id: '',
    student_id: '',
    date: today(),
    time_slot_id: '',
    status: 'present',
    remarks: '',
})

const { touch, markAllTouched, reset, error } = useFormValidation({
    class_subject_id: () => !form.class_subject_id ? __('Class - Subject is required') : null,
    student_id:       () => !form.student_id ? __('Student is required') : null,
    date:             () => !form.date ? __('Date is required') : null,
    time_slot_id:     () => !form.time_slot_id ? __('Time slot is required') : null,
    status:           () => !form.status ? __('Status is required') : null,
})

watch(() => props.record, (r) => {
    reset()
    form.clearErrors()
    if (r) {
        form.class_subject_id = r.class_subject_id != null ? String(r.class_subject_id) : ''
        form.student_id       = r.student_id != null ? String(r.student_id) : ''
        form.date             = r.date ? String(r.date).split('T')[0] : today()
        form.time_slot_id     = r.time_slot_id != null ? String(r.time_slot_id) : ''
        form.status           = r.status || 'present'
        form.remarks          = r.remarks || ''
    } else {
        form.reset()
    }
}, { immediate: true })

const csLabel = (cs) => `${cs.school_class?.name} - ${cs.subject?.name} (${cs.teacher?.name})`

const canSubmit = computed(() =>
    !!form.class_subject_id && !!form.student_id && !!form.date && !!form.time_slot_id && !!form.status
)

const close = () => emit('update:open', false)

const submit = () => {
    if (!markAllTouched()) return
    if (isEdit.value) {
        form.put(`/admin/attendance/${props.record.id}`, {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { close() },
            onError: () => { toast.error(__('Failed to update attendance')) },
        })
    } else {
        form.post('/admin/attendance', {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { close(); form.reset() },
            onError: () => { toast.error(__('Failed to record attendance')) },
        })
    }
}
</script>
