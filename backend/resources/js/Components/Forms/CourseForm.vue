<template>
    <ModalForm :open="open" :title="isEdit ? 'Edit Course' : 'Create Course'"
        :description="isEdit ? `Update ${course?.name}` : 'Add a new course to the system'"
        size="md" @update:open="emit('update:open', $event)">
        <form @submit.prevent="submit" class="space-y-4 py-2">
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                <Input v-model="form.name" type="text" required />
                <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
            </div>
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Code *</Label>
                <Input v-model="form.code" type="text" required placeholder="e.g. CS" />
                <p v-if="form.errors.code" class="text-[12px] text-rose-500 mt-1">{{ form.errors.code }}</p>
            </div>
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Description</Label>
                <Textarea v-model="form.description" rows="3" />
            </div>
            <div class="flex justify-end gap-2 pt-4">
                <Button variant="outline" type="button" @click="close">Cancel</Button>
                <Button type="submit" :disabled="form.processing">
                    {{ isEdit ? 'Save Changes' : 'Create Course' }}
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
import { Textarea } from '@/Components/ui/textarea'
import { Button } from '@/Components/ui/button'

const props = defineProps({
    open: { type: Boolean, required: true },
    course: { type: Object, default: null },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.course)

const form = useForm({ name: '', code: '', description: '' })

watch(() => props.course, (c) => {
    if (c) {
        form.name = c.name
        form.code = c.code
        form.description = c.description || ''
    } else {
        form.reset()
    }
}, { immediate: true })

const close = () => emit('update:open', false)
const submit = () => {
    if (isEdit.value) {
        form.put(`/admin/courses/${props.course.id}`, { onSuccess: close })
    } else {
        form.post('/admin/courses', { onSuccess: () => { close(); form.reset() } })
    }
}
</script>
