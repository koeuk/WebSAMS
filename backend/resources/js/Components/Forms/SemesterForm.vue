<template>
    <ModalForm :open="open" size="lg" @update:open="emit('update:open', $event)">
        <template #title>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                    <CalendarDays class="w-4 h-4 text-slate-600" />
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">{{ isEdit ? __('Edit Semester') : __('Create Semester') }}</h2>
                    <p class="text-xs text-slate-500">{{ isEdit ? __('Update {name}', { name: semester?.name }) : __('Add a new academic semester') }}</p>
                </div>
            </div>
        </template>

        <div class="space-y-4">
            <div>
                <TranslatableInput
                    v-model="form.name"
                    :label="__('Name')"
                    required
                    :placeholder="{ en: 'e.g. Semester 1', km: 'ឧ. ឆមាស ១', zh: '例如:第一学期' }"
                    @update:model-value="touch('name')"
                />
                <p v-if="error('name') || nameError" class="text-[12px] text-rose-500 mt-1">{{ error('name') || nameError }}</p>
            </div>
            <div>
                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Academic Year') }} <span class="text-rose-500">*</span></Label>
                <Input v-model="form.academic_year" type="text" :placeholder="__('e.g. 2025-2026')" @update:model-value="touch('academic_year')" />
                <p v-if="error('academic_year') || form.errors.academic_year" class="text-[12px] text-rose-500 mt-1">{{ error('academic_year') || form.errors.academic_year }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Start Date') }} <span class="text-rose-500">*</span></Label>
                    <DatePicker v-model="form.start_date" :placeholder="__('Pick start date')" @update:model-value="touch('start_date')" />
                    <p v-if="error('start_date') || form.errors.start_date" class="text-[12px] text-rose-500 mt-1">{{ error('start_date') || form.errors.start_date }}</p>
                </div>
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('End Date') }} <span class="text-rose-500">*</span></Label>
                    <DatePicker v-model="form.end_date" :placeholder="__('Pick end date')" @update:model-value="touch('end_date')" />
                    <p v-if="error('end_date') || form.errors.end_date" class="text-[12px] text-rose-500 mt-1">{{ error('end_date') || form.errors.end_date }}</p>
                </div>
            </div>
        </div>

        <template #footer>
            <Button variant="outline" type="button" @click="close">{{ __('Cancel') }}</Button>
            <Button type="button" :disabled="!canSubmit || form.processing" @click="submit">
                <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                {{ isEdit ? __('Save Changes') : __('Create Semester') }}
            </Button>
        </template>
    </ModalForm>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { CalendarDays, Loader2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import ModalForm from '@/Components/ModalForm.vue'
import DatePicker from '@/Components/DatePicker.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import TranslatableInput from '@/Components/Forms/TranslatableInput.vue'
import { useFormValidation } from '@/Composables/useFormValidation'
import { __ } from '@/Composables/useTranslate'

const props = defineProps({
    open:     { type: Boolean, required: true },
    semester: { type: Object, default: null },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.semester)

const emptyTranslations = () => ({ en: '', km: '', zh: '' })

const form = useForm({ name: emptyTranslations(), academic_year: '', start_date: '', end_date: '' })

const nameError = computed(() => {
    const en = (form.name?.en ?? '').trim()
    if (!en) return __('English name is required')
    return null
})

const { touch, markAllTouched, reset, error } = useFormValidation({
    name:          () => nameError.value,
    academic_year: () => !form.academic_year?.trim() ? __('Academic year is required') : null,
    start_date:    () => !form.start_date ? __('Start date is required') : null,
    end_date:      () => !form.end_date ? __('End date is required') : null,
})

const readTranslations = (value) => {
    if (!value) return emptyTranslations()
    if (typeof value === 'string') return { ...emptyTranslations(), en: value }
    return { ...emptyTranslations(), ...value }
}

watch(() => props.semester, (s) => {
    reset()
    if (s) {
        form.name = readTranslations(s.name_translations ?? s.name)
        form.academic_year = s.academic_year || ''
        form.start_date = s.start_date?.split('T')[0] || ''
        form.end_date = s.end_date?.split('T')[0] || ''
    } else {
        form.reset()
        form.name = emptyTranslations()
    }
}, { immediate: true })

const canSubmit = computed(() => !nameError.value && !!form.academic_year?.trim() && !!form.start_date && !!form.end_date)

const close = () => emit('update:open', false)

const submit = () => {
    if (!markAllTouched()) return
    if (isEdit.value) {
        form.put(route('admin.semesters.update', props.semester.id), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success(__('Semester updated successfully')); close() },
            onError: () => { toast.error(__('Failed to update semester')) },
        })
    } else {
        form.post(route('admin.semesters.store'), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success(__('Semester created successfully')); close(); form.reset() },
            onError: () => { toast.error(__('Failed to create semester')) },
        })
    }
}
</script>
