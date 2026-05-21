<template>
    <AdminLayout>
        <Modal
            :show="isOpen"
            :title="__('Delete Course')"
            :message="__(`Delete '{name}' ({code})? This will also remove all subjects under it.`, { name: course.name, code: course.code })"
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

const props = defineProps({ course: Object })

const isOpen = ref(true)
const form = useForm({})

const submit = () => {
    form.delete(route('admin.courses.destroy', props.course.id), {
        preserveScroll: true,
        onSuccess: () => { isOpen.value = false },
    })
}

const handleCancel = () => {
    isOpen.value = false
    setTimeout(() => router.visit(route('admin.courses.index'), { preserveState: true, preserveScroll: true }), 150)
}
</script>
