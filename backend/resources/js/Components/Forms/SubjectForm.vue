<template>
    <Form :validation-schema="schema" v-slot="{ setErrors, meta }" :initial-values="form">
        <ModalForm :open="open" size="lg" @update:open="emit('update:open', $event)">
            <template #title>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                        <BookMarked class="w-4 h-4 text-slate-600" />
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">{{ isEdit ? 'Edit Subject' : 'Create Subject' }}</h2>
                        <p class="text-xs text-slate-500">{{ isEdit ? `Update ${subject?.name}` : 'Add a new subject' }}</p>
                    </div>
                </div>
            </template>

            <div class="space-y-4">
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Course *</Label>
                    <Select v-model="form.course_id">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Select a course" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="c in courses" :key="c.id" :value="String(c.id)">
                                {{ c.name }} ({{ c.code }})
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="form.errors.course_id" class="text-[12px] text-rose-500 mt-1">{{ form.errors.course_id }}</p>
                </div>
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                    <Field name="name" v-slot="{ field, errorMessage }">
                        <Input v-bind="field" v-model="form.name" type="text" placeholder="e.g. Introduction to Programming" />
                        <p v-if="errorMessage" class="text-[12px] text-rose-500 mt-1">{{ errorMessage }}</p>
                    </Field>
                    <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
                </div>
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Code *</Label>
                    <Field name="code" v-slot="{ field, errorMessage }">
                        <Input v-bind="field" v-model="form.code" type="text" placeholder="e.g. CS101" />
                        <p v-if="errorMessage" class="text-[12px] text-rose-500 mt-1">{{ errorMessage }}</p>
                    </Field>
                    <p v-if="form.errors.code" class="text-[12px] text-rose-500 mt-1">{{ form.errors.code }}</p>
                </div>
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Description</Label>
                    <Textarea v-model="form.description" rows="3" placeholder="Optional description…" />
                </div>
            </div>

            <template #footer>
                <Button variant="outline" type="button" @click="close">Cancel</Button>
                <Button type="button" :disabled="!meta.valid || form.processing" @click="submit(setErrors)">
                    <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                    {{ isEdit ? 'Save Changes' : 'Create Subject' }}
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
import { BookMarked, Loader2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import ModalForm from '@/Components/ModalForm.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Textarea } from '@/Components/ui/textarea'
import { Button } from '@/Components/ui/button'
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select'

const props = defineProps({
    open:    { type: Boolean, required: true },
    subject: { type: Object, default: null },
    courses: { type: Array, required: true },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.subject)

const schema = yup.object({
    name: yup.string().required('Name is required'),
    code: yup.string().required('Code is required'),
})

const form = useForm({ course_id: '', name: '', code: '', description: '' })

watch(() => props.subject, (s) => {
    if (s) { form.course_id = String(s.course_id); form.name = s.name; form.code = s.code; form.description = s.description || '' }
    else form.reset()
}, { immediate: true })

const close = () => emit('update:open', false)

const submit = (setErrors) => {
    if (isEdit.value) {
        form.put(route('admin.subjects.update', props.subject.id), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success('Subject updated successfully'); close() },
            onError: (errors) => { toast.error('Failed to update subject'); setErrors(errors) },
        })
    } else {
        form.post(route('admin.subjects.store'), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success('Subject created successfully'); close(); form.reset() },
            onError: (errors) => { toast.error('Failed to create subject'); setErrors(errors) },
        })
    }
}
</script>
