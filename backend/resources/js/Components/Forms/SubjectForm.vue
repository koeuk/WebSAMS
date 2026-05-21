<template>
    <ModalForm :open="open" size="lg" @update:open="emit('update:open', $event)">
        <template #title>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                    <BookMarked class="w-4 h-4 text-slate-600" />
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">{{ isEdit ? __('Edit Subject') : __('Create Subject') }}</h2>
                    <p class="text-xs text-slate-500">{{ isEdit ? __('Update {name}', { name: subject?.name }) : __('Add a new subject') }}</p>
                </div>
            </div>
        </template>

        <div class="space-y-4">
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Course') }} <span class="text-rose-500">*</span></Label>
                <Select v-model="form.course_id" @update:model-value="touch('course_id')">
                    <SelectTrigger class="w-full">
                        <SelectValue :placeholder="__('Select a course')" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="c in courses" :key="c.id" :value="String(c.id)">
                            {{ c.name }} ({{ c.code }})
                        </SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="error('course_id') || form.errors.course_id" class="text-[12px] text-rose-500 mt-1">{{ error('course_id') || form.errors.course_id }}</p>
            </div>
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Name') }} <span class="text-rose-500">*</span></Label>
                <TranslatableInput
                    v-model="form.name"
                    :placeholder="{ en: 'e.g. Introduction to Programming', km: 'ឧ. មូលដ្ឋាន​ការ​សរសេរ​កម្មវិធី', zh: '例如:编程入门' }"
                    @update:model-value="touch('name')"
                />
                <p v-if="error('name') || nameError" class="text-[12px] text-rose-500 mt-1">{{ error('name') || nameError }}</p>
            </div>
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Code') }} <span class="text-rose-500">*</span></Label>
                <Input v-model="form.code" type="text" :placeholder="__('e.g. CS101')" @update:model-value="touch('code')" />
                <p v-if="error('code') || form.errors.code" class="text-[12px] text-rose-500 mt-1">{{ error('code') || form.errors.code }}</p>
            </div>
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Description') }}</Label>
                <RichTextEditor v-model="form.description" :placeholder="__('Optional description…')" />
            </div>
        </div>

        <template #footer>
            <Button variant="outline" type="button" @click="close">{{ __('Cancel') }}</Button>
            <Button type="button" :disabled="!canSubmit || form.processing" @click="submit">
                <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                {{ isEdit ? __('Save Changes') : __('Create Subject') }}
            </Button>
        </template>
    </ModalForm>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { BookMarked, Loader2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import ModalForm from '@/Components/ModalForm.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import RichTextEditor from '@/Components/RichTextEditor.vue'
import TranslatableInput from '@/Components/Forms/TranslatableInput.vue'
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select'
import { useFormValidation } from '@/Composables/useFormValidation'
import { __ } from '@/Composables/useTranslate'

const props = defineProps({
    open:    { type: Boolean, required: true },
    subject: { type: Object, default: null },
    courses: { type: Array, required: true },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.subject)

const emptyTranslations = () => ({ en: '', km: '', zh: '' })

const form = useForm({
    course_id: '',
    name: emptyTranslations(),
    code: '',
    description: '',
})

const nameError = computed(() => {
    const en = (form.name?.en ?? '').trim()
    if (!en) return __('English name is required')
    return null
})

const { touch, markAllTouched, reset, error } = useFormValidation({
    course_id: () => !form.course_id ? __('Course is required') : null,
    name:      () => nameError.value,
    code:      () => !form.code?.trim() ? __('Code is required') : null,
})

const readTranslations = (value) => {
    if (!value) return emptyTranslations()
    if (typeof value === 'string') return { ...emptyTranslations(), en: value }
    return { ...emptyTranslations(), ...value }
}

watch(() => props.subject, (s) => {
    reset()
    if (s) {
        form.course_id = String(s.course_id)
        form.name = readTranslations(s.name_translations ?? s.name)
        form.code = s.code
        form.description = s.description || ''
    } else {
        form.reset()
        form.name = emptyTranslations()
    }
}, { immediate: true })

const canSubmit = computed(() => !!form.course_id && !nameError.value && !!form.code?.trim())

const close = () => emit('update:open', false)

const submit = () => {
    if (!markAllTouched()) return
    if (isEdit.value) {
        form.put(route('admin.subjects.update', props.subject.id), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success(__('Subject updated successfully')); close() },
            onError: () => { toast.error(__('Failed to update subject')) },
        })
    } else {
        form.post(route('admin.subjects.store'), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success(__('Subject created successfully')); close(); form.reset() },
            onError: () => { toast.error(__('Failed to create subject')) },
        })
    }
}
</script>
