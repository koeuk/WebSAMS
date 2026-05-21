<template>
    <ModalForm :open="open" size="lg" @update:open="emit('update:open', $event)">
        <template #title>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                    <School class="w-4 h-4 text-slate-600" />
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">{{ isEdit ? __('Edit Class') : __('Create Class') }}</h2>
                    <p class="text-xs text-slate-500">{{ isEdit ? __('Update {name}', { name: schoolClass?.name }) : __('Add a new class') }}</p>
                </div>
            </div>
        </template>

        <div class="space-y-4">
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">{{ __('Name') }} <span class="text-rose-500">*</span></Label>
                <TranslatableInput
                    v-model="form.name"
                    :placeholder="{ en: 'e.g. Year 1 - A', km: 'ឧ. ឆ្នាំ​ទី​១ - A', zh: '例如:一年级 - A' }"
                    @update:model-value="touch('name')"
                />
                <p v-if="error('name') || nameError" class="text-[12px] text-rose-500 mt-1">{{ error('name') || nameError }}</p>
            </div>
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">{{ __('Section') }}</Label>
                <Input v-model="form.section" type="text" :placeholder="__('e.g. A')" />
            </div>
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">{{ __('Academic Year') }} <span class="text-rose-500">*</span></Label>
                <Input v-model="form.academic_year" type="text" :placeholder="__('e.g. 2025-2026')" @update:model-value="touch('academic_year')" />
                <p v-if="error('academic_year') || form.errors.academic_year" class="text-[12px] text-rose-500 mt-1">{{ error('academic_year') || form.errors.academic_year }}</p>
            </div>
        </div>

        <template #footer>
            <Button variant="outline" type="button" @click="close">{{ __('Cancel') }}</Button>
            <Button type="button" :disabled="!canSubmit || form.processing" @click="submit">
                <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                {{ isEdit ? __('Save Changes') : __('Create Class') }}
            </Button>
        </template>
    </ModalForm>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { School, Loader2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import ModalForm from '@/Components/ModalForm.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import TranslatableInput from '@/Components/Forms/TranslatableInput.vue'
import { useFormValidation } from '@/Composables/useFormValidation'
import { __ } from '@/Composables/useTranslate'

const props = defineProps({
    open:        { type: Boolean, required: true },
    schoolClass: { type: Object, default: null },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.schoolClass)

const emptyTranslations = () => ({ en: '', km: '', zh: '' })

const form = useForm({ name: emptyTranslations(), section: '', academic_year: '' })

const nameError = computed(() => {
    const en = (form.name?.en ?? '').trim()
    if (!en) return __('English name is required')
    return null
})

const { touch, markAllTouched, reset, error } = useFormValidation({
    name:          () => nameError.value,
    academic_year: () => !form.academic_year?.trim() ? __('Academic year is required') : null,
})

const readTranslations = (value) => {
    if (!value) return emptyTranslations()
    if (typeof value === 'string') return { ...emptyTranslations(), en: value }
    return { ...emptyTranslations(), ...value }
}

watch(() => props.schoolClass, (c) => {
    reset()
    if (c) {
        form.name = readTranslations(c.name_translations ?? c.name)
        form.section = c.section || ''
        form.academic_year = c.academic_year || ''
    } else {
        form.reset()
        form.name = emptyTranslations()
    }
}, { immediate: true })

const canSubmit = computed(() => !nameError.value && !!form.academic_year?.trim())

const close = () => emit('update:open', false)

const submit = () => {
    if (!markAllTouched()) return
    if (isEdit.value) {
        form.put(route('admin.classes.update', props.schoolClass.id), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success(__('Class updated successfully')); close() },
            onError: () => { toast.error(__('Failed to update class')) },
        })
    } else {
        form.post(route('admin.classes.store'), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success(__('Class created successfully')); close(); form.reset() },
            onError: () => { toast.error(__('Failed to create class')) },
        })
    }
}
</script>
