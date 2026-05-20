<template>
    <ModalForm :open="open" size="lg" @update:open="emit('update:open', $event)">
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
                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name <span class="text-rose-500">*</span></Label>
                <Input v-model="form.name" type="text" placeholder="e.g. Semester 1" />
                <p v-if="errors.name || form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ errors.name || form.errors.name }}</p>
            </div>
            <div>
                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Academic Year <span class="text-rose-500">*</span></Label>
                <Input v-model="form.academic_year" type="text" placeholder="e.g. 2025-2026" />
                <p v-if="errors.academic_year || form.errors.academic_year" class="text-[12px] text-rose-500 mt-1">{{ errors.academic_year || form.errors.academic_year }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Start Date <span class="text-rose-500">*</span></Label>
                    <DatePicker v-model="form.start_date" placeholder="Pick start date" />
                    <p v-if="errors.start_date || form.errors.start_date" class="text-[12px] text-rose-500 mt-1">{{ errors.start_date || form.errors.start_date }}</p>
                </div>
                <div>
                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">End Date <span class="text-rose-500">*</span></Label>
                    <DatePicker v-model="form.end_date" placeholder="Pick end date" />
                    <p v-if="errors.end_date || form.errors.end_date" class="text-[12px] text-rose-500 mt-1">{{ errors.end_date || form.errors.end_date }}</p>
                </div>
            </div>
        </div>

        <template #footer>
            <Button variant="outline" type="button" @click="close">Cancel</Button>
            <Button type="button" :disabled="!canSubmit || form.processing" @click="submit">
                <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                {{ isEdit ? 'Save Changes' : 'Create Semester' }}
            </Button>
        </template>
    </ModalForm>
</template>

<script setup>
import { computed, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
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

const form = useForm({ name: '', academic_year: '', start_date: '', end_date: '' })
const errors = ref({})

watch(() => props.semester, (s) => {
    errors.value = {}
    if (s) { form.name = s.name; form.academic_year = s.academic_year || ''; form.start_date = s.start_date?.split('T')[0] || ''; form.end_date = s.end_date?.split('T')[0] || '' }
    else form.reset()
}, { immediate: true })

const canSubmit = computed(() => !!form.name?.trim() && !!form.academic_year?.trim() && !!form.start_date && !!form.end_date)

const validate = () => {
    const e = {}
    if (!form.name?.trim()) e.name = 'Name is required'
    if (!form.academic_year?.trim()) e.academic_year = 'Academic year is required'
    if (!form.start_date) e.start_date = 'Start date is required'
    if (!form.end_date) e.end_date = 'End date is required'
    errors.value = e
    return Object.keys(e).length === 0
}

const close = () => emit('update:open', false)

const submit = () => {
    if (!validate()) return
    if (isEdit.value) {
        form.put(route('admin.semesters.update', props.semester.id), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success('Semester updated successfully'); close() },
            onError: () => { toast.error('Failed to update semester') },
        })
    } else {
        form.post(route('admin.semesters.store'), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success('Semester created successfully'); close(); form.reset() },
            onError: () => { toast.error('Failed to create semester') },
        })
    }
}
</script>
