<template>
    <Form :validation-schema="schema" v-slot="{ setErrors, meta }" :initial-values="form">
        <ModalForm :open="open" size="lg" @update:open="emit('update:open', $event)">
            <template #title>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                        <Clock class="w-4 h-4 text-slate-600" />
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">{{ isEdit ? 'Edit Time Slot' : 'Create Time Slot' }}</h2>
                        <p class="text-xs text-slate-500">{{ isEdit ? `Update ${timeSlot?.name}` : 'Add a new class time period' }}</p>
                    </div>
                </div>
            </template>

            <div class="space-y-4">
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                    <Field name="name" v-slot="{ field, errorMessage }">
                        <Input v-bind="field" v-model="form.name" type="text" placeholder="e.g. Morning 1" />
                        <p v-if="errorMessage" class="text-[12px] text-rose-500 mt-1">{{ errorMessage }}</p>
                    </Field>
                    <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Start Time *</Label>
                        <Field name="start_time" v-slot="{ field, errorMessage }">
                            <Input v-bind="field" v-model="form.start_time" type="time" />
                            <p v-if="errorMessage" class="text-[12px] text-rose-500 mt-1">{{ errorMessage }}</p>
                        </Field>
                        <p v-if="form.errors.start_time" class="text-[12px] text-rose-500 mt-1">{{ form.errors.start_time }}</p>
                    </div>
                    <div>
                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">End Time *</Label>
                        <Field name="end_time" v-slot="{ field, errorMessage }">
                            <Input v-bind="field" v-model="form.end_time" type="time" />
                            <p v-if="errorMessage" class="text-[12px] text-rose-500 mt-1">{{ errorMessage }}</p>
                        </Field>
                        <p v-if="form.errors.end_time" class="text-[12px] text-rose-500 mt-1">{{ form.errors.end_time }}</p>
                    </div>
                </div>
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Type</Label>
                    <Select v-model="form.type">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Select type…" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="t in types" :key="t.value" :value="t.value">
                                {{ t.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="form.errors.type" class="text-[12px] text-rose-500 mt-1">{{ form.errors.type }}</p>
                </div>
            </div>

            <template #footer>
                <Button variant="outline" type="button" @click="close">Cancel</Button>
                <Button type="button" :disabled="!meta.valid || form.processing" @click="submit(setErrors)">
                    <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                    {{ isEdit ? 'Save Changes' : 'Create Time Slot' }}
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
import { Clock, Loader2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import ModalForm from '@/Components/ModalForm.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select'

const props = defineProps({
    open:     { type: Boolean, required: true },
    timeSlot: { type: Object, default: null },
    types:    { type: Array, default: () => [] },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.timeSlot)

const schema = yup.object({
    name:       yup.string().required('Name is required'),
    start_time: yup.string().required('Start time is required'),
    end_time:   yup.string().required('End time is required'),
})

const form = useForm({ name: '', start_time: '', end_time: '', type: 'morning' })

watch(() => props.timeSlot, (ts) => {
    if (ts) { form.name = ts.name; form.start_time = ts.start_time?.slice(0, 5) || ''; form.end_time = ts.end_time?.slice(0, 5) || ''; form.type = ts.type || 'morning' }
    else { form.reset(); form.type = 'morning' }
}, { immediate: true })

const close = () => emit('update:open', false)

const submit = (setErrors) => {
    if (isEdit.value) {
        form.put(route('admin.time-slots.update', props.timeSlot.id), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success('Time slot updated successfully'); close() },
            onError: (errors) => { toast.error('Failed to update time slot'); setErrors(errors) },
        })
    } else {
        form.post(route('admin.time-slots.store'), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success('Time slot created successfully'); close(); form.reset(); form.type = 'morning' },
            onError: (errors) => { toast.error('Failed to create time slot'); setErrors(errors) },
        })
    }
}
</script>
