<template>
    <ModalForm :open="open" size="lg" @update:open="emit('update:open', $event)">
        <template #title>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                    <CalendarClock class="w-4 h-4 text-slate-600" />
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">{{ isEdit ? 'Edit Schedule' : 'Create Schedule' }}</h2>
                    <p class="text-xs text-slate-500">{{ isEdit ? 'Update schedule entry' : 'Add a new schedule entry' }}</p>
                </div>
            </div>
        </template>

        <div class="space-y-4">
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Class – Subject <span class="text-rose-500">*</span></Label>
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
                <p v-if="errors.class_subject_id || form.errors.class_subject_id" class="text-[12px] text-rose-500 mt-1">{{ errors.class_subject_id || form.errors.class_subject_id }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Time Slot <span class="text-rose-500">*</span></Label>
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
                    <p v-if="errors.time_slot_id || form.errors.time_slot_id" class="text-[12px] text-rose-500 mt-1">{{ errors.time_slot_id || form.errors.time_slot_id }}</p>
                </div>
                <div>
                    <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Day <span class="text-rose-500">*</span></Label>
                    <Select v-model="form.day_of_week">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Select day" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="d in days" :key="d.value" :value="d.value">
                                {{ d.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="errors.day_of_week || form.errors.day_of_week" class="text-[12px] text-rose-500 mt-1">{{ errors.day_of_week || form.errors.day_of_week }}</p>
                </div>
            </div>
            <div>
                <Label class="text-[13px] font-medium text-slate-600 mb-1.5 block">Room</Label>
                <Input v-model="form.room" type="text" placeholder="e.g. Room 201" />
            </div>
        </div>

        <template #footer>
            <Button variant="outline" type="button" @click="close">Cancel</Button>
            <Button type="button" :disabled="!canSubmit || form.processing" @click="submit">
                <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                {{ isEdit ? 'Save Changes' : 'Create Schedule' }}
            </Button>
        </template>
    </ModalForm>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { CalendarClock, Loader2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import ModalForm from '@/Components/ModalForm.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select'

const props = defineProps({
    open:          { type: Boolean, required: true },
    schedule:      { type: Object, default: null },
    classSubjects: { type: Array, default: () => [] },
    timeSlots:     { type: Array, default: () => [] },
    days:          { type: Array, default: () => [] },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.schedule)

const form = useForm({ class_subject_id: '', time_slot_id: '', day_of_week: '', room: '' })
const errors = ref({})

watch(() => props.schedule, (s) => {
    errors.value = {}
    if (s) { form.class_subject_id = String(s.class_subject_id); form.time_slot_id = String(s.time_slot_id); form.day_of_week = s.day_of_week; form.room = s.room || '' }
    else form.reset()
}, { immediate: true })

const canSubmit = computed(() => !!form.class_subject_id && !!form.time_slot_id && !!form.day_of_week)

const validate = () => {
    const e = {}
    if (!form.class_subject_id) e.class_subject_id = 'Class – Subject is required'
    if (!form.time_slot_id) e.time_slot_id = 'Time slot is required'
    if (!form.day_of_week) e.day_of_week = 'Day is required'
    errors.value = e
    return Object.keys(e).length === 0
}

const close = () => emit('update:open', false)

const submit = () => {
    if (!validate()) return
    if (isEdit.value) {
        form.put(route('admin.schedules.update', props.schedule.id), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success('Schedule updated successfully'); close() },
            onError: () => { toast.error('Failed to update schedule') },
        })
    } else {
        form.post(route('admin.schedules.store'), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success('Schedule created successfully'); close(); form.reset() },
            onError: () => { toast.error('Failed to create schedule') },
        })
    }
}
</script>
