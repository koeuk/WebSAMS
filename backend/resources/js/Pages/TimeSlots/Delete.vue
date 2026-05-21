<template>
    <AdminLayout>
        <Modal
            :show="isOpen"
            :title="__('Delete Time Slot')"
            :message="__(`Delete '{name}' ({start} – {end})?`, { name: timeSlot.name, start: timeSlot.start_time, end: timeSlot.end_time })"
            :loading="form.processing"
            @confirm="submit"
            @cancel="handleCancel"
        />
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Modal from '@/Components/Modal.vue'
import { __ } from '@/Composables/useTranslate'

const props = defineProps({ timeSlot: Object })

const isOpen = ref(true)
const form = useForm({})

const submit = () => {
    form.delete(route('admin.time-slots.destroy', props.timeSlot.id), {
        preserveScroll: true,
        onSuccess: () => { isOpen.value = false },
    })
}

const handleCancel = () => {
    isOpen.value = false
    setTimeout(() => router.visit(route('admin.time-slots.index'), { preserveState: true, preserveScroll: true }), 150)
}
</script>
