<template>
    <ModalForm :open="open" size="lg" @update:open="emit('update:open', $event)">
        <template #title>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                    <BookOpen class="w-4 h-4 text-slate-600" />
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">{{ isEdit ? __('Edit Course') : __('Create Course') }}</h2>
                    <p class="text-xs text-slate-500">{{ isEdit ? __('Update {name}', { name: course?.name }) : __('Add a new course') }}</p>
                </div>
            </div>
        </template>

        <div class="space-y-4">
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Name') }} <span class="text-rose-500">*</span></Label>
                <TranslatableInput
                    v-model="form.name"
                    :placeholder="{ en: 'e.g. Computer Science', km: 'ឧ. វិទ្យាសាស្ត្រ​កុំព្យូទ័រ', zh: '例如:计算机科学' }"
                    @update:model-value="touch('name')"
                />
                <p v-if="error('name') || nameError" class="text-[12px] text-rose-500 mt-1">{{ error('name') || nameError }}</p>
            </div>
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Code') }} <span class="text-rose-500">*</span></Label>
                <Input v-model="form.code" type="text" :placeholder="__('e.g. CS')" @update:model-value="touch('code')" />
                <p v-if="error('code') || form.errors.code" class="text-[12px] text-rose-500 mt-1">{{ error('code') || form.errors.code }}</p>
            </div>
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Description') }}</Label>
                <TranslatableRichText
                    v-model="form.description"
                    :placeholder="__('Optional description…')"
                />
            </div>
        </div>

        <template #footer>
            <Button variant="outline" type="button" @click="close">{{ __('Cancel') }}</Button>
            <Button type="button" :disabled="!canSubmit || form.processing" @click="submit">
                <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                {{ isEdit ? __('Save Changes') : __('Create Course') }}
            </Button>
        </template>
    </ModalForm>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { BookOpen, Loader2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import ModalForm from '@/Components/ModalForm.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import TranslatableInput from '@/Components/Forms/TranslatableInput.vue'
import TranslatableRichText from '@/Components/Forms/TranslatableRichText.vue'
import { useFormValidation } from '@/Composables/useFormValidation'
import { __ } from '@/Composables/useTranslate'

const props = defineProps({
    open:   { type: Boolean, required: true },
    course: { type: Object, default: null },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.course)

const emptyTranslations = () => ({ en: '', km: '', zh: '' })

const form = useForm({
    name: emptyTranslations(),
    code: '',
    description: emptyTranslations(),
})

const nameError = computed(() => {
    const en = (form.name?.en ?? '').trim()
    if (!en) return __('English name is required')
    if (en.length < 2) return __('At least 2 characters')
    return null
})

const { touch, markAllTouched, reset, error } = useFormValidation({
    name: () => nameError.value,
    code: () => !form.code?.trim() ? __('Code is required') : null,
})

const readTranslations = (value) => {
    if (!value) return emptyTranslations()
    if (typeof value === 'string') return { ...emptyTranslations(), en: value }
    return { ...emptyTranslations(), ...value }
}

watch(() => props.course, (c) => {
    reset()
    if (c) {
        form.name = readTranslations(c.name_translations ?? c.name)
        form.code = c.code
        form.description = readTranslations(c.description_translations ?? c.description)
    } else {
        form.reset()
        form.name = emptyTranslations()
        form.description = emptyTranslations()
    }
}, { immediate: true })

const canSubmit = computed(() => !nameError.value && !!form.code?.trim())

const close = () => emit('update:open', false)

const submit = () => {
    if (!markAllTouched()) return
    if (isEdit.value) {
        form.put(route('admin.courses.update', props.course.id), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success(__('Course updated successfully')); close() },
            onError: () => { toast.error(__('Failed to update course')) },
        })
    } else {
        form.post(route('admin.courses.store'), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success(__('Course created successfully')); close(); form.reset() },
            onError: () => { toast.error(__('Failed to create course')) },
        })
    }
}
</script>
