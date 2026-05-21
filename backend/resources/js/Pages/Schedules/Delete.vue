<template>
    <AdminLayout>
        <Modal
            :show="isOpen"
            title="Delete Schedule"
            :message="`Delete schedule for '${schedule.class_subject?.subject?.name}' on ${dayLabel}?`"
            :loading="form.processing"
            @confirm="submit"
            @cancel="handleCancel"
        />
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'

const props = defineProps({ schedule: Object })

const isOpen = ref(true)
const form = useForm({})

const days = { mon: 'Monday', tue: 'Tuesday', wed: 'Wednesday', thu: 'Thursday', fri: 'Friday', sat: 'Saturday', sun: 'Sunday' }
const dayLabel = computed(() => days[props.schedule.day_of_week] ?? props.schedule.day_of_week)

const submit = () => {
    form.delete(route('admin.schedules.destroy', props.schedule.id), {
        preserveScroll: true,
        onSuccess: () => { isOpen.value = false },
    })
}

const handleCancel = () => {
    isOpen.value = false
    setTimeout(() => router.visit(route('admin.schedules.index'), { preserveState: true, preserveScroll: true }), 150)
}
</script>
