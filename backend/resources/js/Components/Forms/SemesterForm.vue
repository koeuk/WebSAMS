<template>
    <Form :validation-schema="schema" v-slot="{ setErrors, meta }" :initial-values="form">
        <ModalForm :open="open" size="md" @update:open="emit('update:open', $event)">
            <template #title>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                        <CalendarDays class="w-4 h-4 text-slate-600" />
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">{{ isEdit ? 'Edit Semester' : 'Create Semester' }}</h2>
                        <p class="text-xs text-slate-500">{{ isEdit ? `Update ${semester?.name}` : 'Add a new academic semester' }}</p>
                    </div>
                </div>
            </template>

            <div class="space-y-4">
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                    <Field name="name" v-slot="{ field, errorMessage }">
                        <Input v-bind="field" v-model="form.name" type="text" placeholder="e.g. Semester 1" />
                        <p v-if="errorMessage" class="text-[12px] text-rose-500 mt-1">{{ errorMessage }}</p>
                    </Field>
                    <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
                </div>
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Academic Year *</Label>
                    <Field name="academic_year" v-slot="{ field, errorMessage }">
                        <Input v-bind="field" v-model="form.academic_year" type="text" placeholder="e.g. 2025-2026" />
                        <p v-if="errorMessage" class="text-[12px] text-rose-500 mt-1">{{ errorMessage }}</p>
                    </Field>
                    <p v-if="form.errors.academic_year" class="text-[12px] text-rose-500 mt-1">{{ form.errors.academic_year }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Start Date *</Label>
                        <DatePicker v-model="form.start_date" placeholder="Pick start date" />
                        <p v-if="form.errors.start_date" class="text-[12px] text-rose-500 mt-1">{{ form.errors.start_date }}</p>
                    </div>
                    <div>
                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">End Date *</Label>
                        <DatePicker v-model="form.end_date" placeholder="Pick end date" />
                        <p v-if="form.errors.end_date" class="text-[12px] text-rose-500 mt-1">{{ form.errors.end_date }}</p>
                    </div>
                </div>
            </div>

            <template #footer>
                <Button variant="outline" type="button" @click="close">Cancel</Button>
                <Button type="button" :disabled="!meta.valid || form.processing" @click="submit(setErrors)">
                    <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                    {{ isEdit ? 'Save Changes' : 'Create Semester' }}
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
import { CalendarDays, Loader2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import ModalForm from '@/Components/ModalForm.vue'
import DatePicker from '@/Components/DatePicker.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'

const props = defineProps({
    open:     { type: Boolean, required: true },
    semester: { type: Object, default: null },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.semester)

const schema = yup.object({
    name:          yup.string().required('Name is required'),
    academic_year: yup.string().required('Academic year is required'),
})

const form = useForm({ name: '', academic_year: '', start_date: '', end_date: '' })

watch(() => props.semester, (s) => {
    if (s) { form.name = s.name; form.academic_year = s.academic_year || ''; form.start_date = s.start_date?.split('T')[0] || ''; form.end_date = s.end_date?.split('T')[0] || '' }
    else form.reset()
}, { immediate: true })

const close = () => emit('update:open', false)

const submit = (setErrors) => {
    if (isEdit.value) {
        form.put(route('admin.semesters.update', props.semester.id), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success('Semester updated successfully'); close() },
            onError: (errors) => { toast.error('Failed to update semester'); setErrors(errors) },
        })
    } else {
        form.post(route('admin.semesters.store'), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success('Semester created successfully'); close(); form.reset() },
            onError: (errors) => { toast.error('Failed to create semester'); setErrors(errors) },
        })
    }
}
</script>
