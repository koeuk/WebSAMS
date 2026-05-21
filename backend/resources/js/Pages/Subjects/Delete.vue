<template>
    <AdminLayout>
        <Modal
            :show="isOpen"
            :title="__('Delete Subject')"
            :message="__(`Delete '{name}' ({code})?`, { name: subject.name, code: subject.code })"
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

const props = defineProps({ subject: Object })

const isOpen = ref(true)
const form = useForm({})

const submit = () => {
    form.delete(route('admin.subjects.destroy', props.subject.id), {
        preserveScroll: true,
        onSuccess: () => { isOpen.value = false },
    })
}

const handleCancel = () => {
    isOpen.value = false
    setTimeout(() => router.visit(route('admin.subjects.index'), { preserveState: true, preserveScroll: true }), 150)
}
</script>
