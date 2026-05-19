<template>
    <ModalForm :open="open" :title="isEdit ? 'Edit Semester' : 'Create Semester'"
        :description="isEdit ? `Update ${semester?.name}` : 'Add a new academic semester.'"
        size="md" @update:open="emit('update:open', $event)">
        <form @submit.prevent="submit" class="space-y-4 py-2">
            <div>
                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                <Input v-model="form.name" type="text" required placeholder="e.g. Semester 1" />
                <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
            </div>
            <div>
                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Academic Year *</Label>
                <Input v-model="form.academic_year" type="text" required placeholder="e.g. 2025-2026" />
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
            <div class="flex justify-end gap-2 pt-4">
                <Button variant="outline" type="button" @click="close">Cancel</Button>
                <Button type="submit" :disabled="form.processing" class="flex items-center gap-2">
                    <svg v-if="!isEdit" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                    {{ form.processing ? (isEdit ? 'Saving...' : 'Creating...') : (isEdit ? 'Save Changes' : 'Create Semester') }}
                </Button>
            </div>
        </form>
    </ModalForm>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import ModalForm from '@/Components/ModalForm.vue'
import DatePicker from '@/Components/DatePicker.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'

const props = defineProps({
    open: { type: Boolean, required: true },
    semester: { type: Object, default: null },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.semester)

const form = useForm({ name: '', academic_year: '', start_date: '', end_date: '' })

watch(() => props.semester, (s) => {
    if (s) {
        form.name = s.name
        form.academic_year = s.academic_year || ''
        form.start_date = s.start_date?.split('T')[0] || ''
        form.end_date = s.end_date?.split('T')[0] || ''
    } else {
        form.reset()
    }
}, { immediate: true })

const close = () => emit('update:open', false)
const submit = () => {
    if (isEdit.value) {
        form.put(`/admin/semesters/${props.semester.id}`, { onSuccess: close })
    } else {
        form.post('/admin/semesters', { onSuccess: () => { close(); form.reset() } })
    }
}
</script>
