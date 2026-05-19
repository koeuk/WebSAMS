<template>
    <ModalForm :open="open" :title="isEdit ? 'Edit Schedule' : 'Create Schedule'"
        :description="isEdit ? 'Update schedule entry.' : 'Add a new schedule entry.'"
        size="md" @update:open="emit('update:open', $event)">
        <form @submit.prevent="submit" class="space-y-4 py-2">
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Class - Subject *</Label>
                <Select v-model="form.class_subject_id">
                    <SelectTrigger class="w-full">
                        <SelectValue placeholder="Select class &amp; subject" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="cs in classSubjects" :key="cs.id" :value="String(cs.id)">
                            {{ cs.subject?.name }} — {{ cs.school_class?.name }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <p v-if="form.errors.class_subject_id" class="text-[12px] text-rose-500 mt-1">{{ form.errors.class_subject_id }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Time Slot *</Label>
                    <Select v-model="form.time_slot_id">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Select time slot" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="ts in timeSlots" :key="ts.id" :value="String(ts.id)">
                                {{ ts.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="form.errors.time_slot_id" class="text-[12px] text-rose-500 mt-1">{{ form.errors.time_slot_id }}</p>
                </div>
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Day *</Label>
                    <Select v-model="form.day_of_week">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Select day" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="mon">Monday</SelectItem>
                            <SelectItem value="tue">Tuesday</SelectItem>
                            <SelectItem value="wed">Wednesday</SelectItem>
                            <SelectItem value="thu">Thursday</SelectItem>
                            <SelectItem value="fri">Friday</SelectItem>
                            <SelectItem value="sat">Saturday</SelectItem>
                            <SelectItem value="sun">Sunday</SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="form.errors.day_of_week" class="text-[12px] text-rose-500 mt-1">{{ form.errors.day_of_week }}</p>
                </div>
            </div>
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Room</Label>
                <Input v-model="form.room" type="text" placeholder="e.g. Room 201" />
            </div>
            <div class="flex justify-end gap-2 pt-4">
                <Button variant="outline" type="button" @click="close">Cancel</Button>
                <Button type="submit" :disabled="form.processing" class="flex items-center gap-2">
                    <svg v-if="!isEdit" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                    {{ form.processing ? (isEdit ? 'Saving...' : 'Creating...') : (isEdit ? 'Save Changes' : 'Create Schedule') }}
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
import {
    Select, SelectTrigger, SelectContent, SelectItem, SelectValue,
    SelectGroup, SelectLabel,
} from '@/Components/ui/select'

const props = defineProps({
    open: { type: Boolean, required: true },
    schedule: { type: Object, default: null },
    classSubjects: { type: Array, default: () => [] },
    timeSlots: { type: Array, default: () => [] },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.schedule)

const form = useForm({ class_subject_id: '', time_slot_id: '', day_of_week: '', room: '' })

watch(() => props.schedule, (s) => {
    if (s) {
        form.class_subject_id = String(s.class_subject_id)
        form.time_slot_id = String(s.time_slot_id)
        form.day_of_week = s.day_of_week
        form.room = s.room || ''
    } else {
        form.reset()
    }
}, { immediate: true })

const close = () => emit('update:open', false)
const submit = () => {
    if (isEdit.value) {
        form.put(`/admin/schedules/${props.schedule.id}`, { onSuccess: close })
    } else {
        form.post('/admin/schedules', { onSuccess: () => { close(); form.reset() } })
    }
}
</script>
