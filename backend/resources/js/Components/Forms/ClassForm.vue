<template>
    <ModalForm :open="open" :title="isEdit ? 'Edit Class' : 'Create Class'"
        :description="isEdit ? `Update ${schoolClass?.name}` : 'Add a new class to the system'"
        size="md" @update:open="emit('update:open', $event)">
        <form @submit.prevent="submit" class="space-y-4 py-2">
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Name *</Label>
                <Input v-model="form.name" type="text" required placeholder="e.g. Grade 10-A" />
                <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
            </div>
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Academic Year</Label>
                <Input v-model="form.academic_year" type="text" placeholder="e.g. 2025-2026" />
                <p v-if="form.errors.academic_year" class="text-[12px] text-rose-500 mt-1">{{ form.errors.academic_year }}</p>
            </div>
            <div class="flex justify-end gap-2 pt-4">
                <Button variant="outline" type="button" @click="close">Cancel</Button>
                <Button type="submit" :disabled="form.processing" class="flex items-center gap-2">
                    <svg v-if="!isEdit" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                    {{ form.processing ? (isEdit ? 'Saving...' : 'Creating...') : (isEdit ? 'Save Changes' : 'Create Class') }}
                </Button>
            </div>
        </form>
    </ModalForm>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import ModalForm from '@/Components/ModalForm.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'

const props = defineProps({
    open: { type: Boolean, required: true },
    schoolClass: { type: Object, default: null },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.schoolClass)

const form = useForm({ name: '', academic_year: '' })

watch(() => props.schoolClass, (c) => {
    if (c) {
        form.name = c.name
        form.academic_year = c.academic_year || ''
    } else {
        form.reset()
    }
}, { immediate: true })

const close = () => emit('update:open', false)
const submit = () => {
    if (isEdit.value) {
        form.put(`/admin/classes/${props.schoolClass.id}`, { onSuccess: close })
    } else {
        form.post('/admin/classes', { onSuccess: () => { close(); form.reset() } })
    }
}
</script>
