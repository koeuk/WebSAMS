<template>
    <Form :validation-schema="schema" v-slot="{ setErrors, meta }" :initial-values="form">
        <ModalForm :open="open" size="lg" @update:open="emit('update:open', $event)">
            <template #title>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                        <School class="w-4 h-4 text-slate-600" />
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">{{ isEdit ? 'Edit Class' : 'Create Class' }}</h2>
                        <p class="text-xs text-slate-500">{{ isEdit ? `Update ${schoolClass?.name}` : 'Add a new class' }}</p>
                    </div>
                </div>
            </template>

            <div class="space-y-4">
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Name *</Label>
                    <Field name="name" v-slot="{ field, errorMessage }">
                        <Input v-bind="field" v-model="form.name" type="text" placeholder="e.g. Year 1-A" />
                        <p v-if="errorMessage" class="text-[12px] text-rose-500 mt-1">{{ errorMessage }}</p>
                    </Field>
                    <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
                </div>
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Section</Label>
                    <Input v-model="form.section" type="text" placeholder="e.g. A" />
                </div>
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Academic Year *</Label>
                    <Field name="academic_year" v-slot="{ field, errorMessage }">
                        <Input v-bind="field" v-model="form.academic_year" type="text" placeholder="e.g. 2025-2026" />
                        <p v-if="errorMessage" class="text-[12px] text-rose-500 mt-1">{{ errorMessage }}</p>
                    </Field>
                    <p v-if="form.errors.academic_year" class="text-[12px] text-rose-500 mt-1">{{ form.errors.academic_year }}</p>
                </div>
            </div>

            <template #footer>
                <Button variant="outline" type="button" @click="close">Cancel</Button>
                <Button type="button" :disabled="!meta.valid || form.processing" @click="submit(setErrors)">
                    <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                    {{ isEdit ? 'Save Changes' : 'Create Class' }}
                </Button>
            </template>
        </ModalForm>
    </Form>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Form, Field } from 'vee-validate'
import * as yup from 'yup'
import { School, Loader2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import ModalForm from '@/Components/ModalForm.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'

const props = defineProps({
    open:        { type: Boolean, required: true },
    schoolClass: { type: Object, default: null },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.schoolClass)

const schema = yup.object({
    name:          yup.string().required('Name is required'),
    academic_year: yup.string().required('Academic year is required'),
})

const form = useForm({ name: '', section: '', academic_year: '' })

watch(() => props.schoolClass, (c) => {
    if (c) { form.name = c.name; form.section = c.section || ''; form.academic_year = c.academic_year || '' }
    else form.reset()
}, { immediate: true })

const close = () => emit('update:open', false)

const submit = (setErrors) => {
    if (isEdit.value) {
        form.put(route('admin.classes.update', props.schoolClass.id), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success('Class updated successfully'); close() },
            onError: (errors) => { toast.error('Failed to update class'); setErrors(errors) },
        })
    } else {
        form.post(route('admin.classes.store'), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success('Class created successfully'); close(); form.reset() },
            onError: (errors) => { toast.error('Failed to create class'); setErrors(errors) },
        })
    }
}
</script>
