<template>
    <AdminLayout>
        <Modal
            :show="isOpen"
            title="Delete Time Slot"
            :message="`Delete '${timeSlot.name}' (${timeSlot.start_time} – ${timeSlot.end_time})?`"
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
