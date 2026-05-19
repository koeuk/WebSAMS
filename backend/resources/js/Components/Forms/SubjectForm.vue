<template>
    <ModalForm :open="open" :title="isEdit ? 'Edit Subject' : 'Create Subject'"
        :description="isEdit ? `Update ${subject?.name}` : 'Add a new subject to a course'"
        size="md" @update:open="emit('update:open', $event)">
        <form @submit.prevent="submit" class="space-y-4 py-2">
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Course *</Label>
                <Select v-model="form.course_id" required>
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
                <Input v-model="form.name" type="text" required />
                <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
            </div>
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5">Code *</Label>
                <Input v-model="form.code" type="text" required placeholder="e.g. CS101" />
                <p v-if="form.errors.code" class="text-[12px] text-rose-500 mt-1">{{ form.errors.code }}</p>
            </div>
            <div class="flex justify-end gap-2 pt-4">
                <Button variant="outline" type="button" @click="close">Cancel</Button>
                <Button type="submit" :disabled="form.processing">
                    {{ isEdit ? 'Save Changes' : 'Create Subject' }}
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
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select'

const props = defineProps({
    open: { type: Boolean, required: true },
    subject: { type: Object, default: null },
    courses: { type: Array, required: true },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.subject)

const form = useForm({ course_id: '', name: '', code: '' })

watch(() => props.subject, (s) => {
    if (s) {
        form.course_id = String(s.course_id)
        form.name = s.name
        form.code = s.code
    } else {
        form.reset()
    }
}, { immediate: true })

const close = () => emit('update:open', false)
const submit = () => {
    if (isEdit.value) {
        form.put(`/admin/subjects/${props.subject.id}`, { onSuccess: close })
    } else {
        form.post('/admin/subjects', { onSuccess: () => { close(); form.reset() } })
    }
}
</script>
