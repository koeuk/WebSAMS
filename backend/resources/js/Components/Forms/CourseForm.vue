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
                <Input v-model="form.name" type="text" :placeholder="__('e.g. Computer Science')" @update:model-value="touch('name')" />
                <p v-if="error('name') || form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ error('name') || form.errors.name }}</p>
            </div>
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Code') }} <span class="text-rose-500">*</span></Label>
                <Input v-model="form.code" type="text" :placeholder="__('e.g. CS')" @update:model-value="touch('code')" />
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
import RichTextEditor from '@/Components/RichTextEditor.vue'
import { useFormValidation } from '@/Composables/useFormValidation'
import { __ } from '@/Composables/useTranslate'

const props = defineProps({
    open:   { type: Boolean, required: true },
    course: { type: Object, default: null },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.course)

const form = useForm({ name: '', code: '', description: '' })

const { touch, markAllTouched, reset, error } = useFormValidation({
    name: () => {
        if (!form.name?.trim()) return __('Name is required')
        if (form.name.trim().length < 2) return __('At least 2 characters')
        return null
    },
    code: () => !form.code?.trim() ? __('Code is required') : null,
})

watch(() => props.course, (c) => {
    reset()
    if (c) { form.name = c.name; form.code = c.code; form.description = c.description || '' }
    else form.reset()
}, { immediate: true })

const canSubmit = computed(() => !!form.name?.trim() && form.name.trim().length >= 2 && !!form.code?.trim())

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
