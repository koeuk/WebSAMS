<template>
    <AdminLayout>
        <Modal
            :show="isOpen"
            :title="__('Delete Class')"
            :message="__(`Delete '{name}'? All enrolled students and subjects will be unlinked.`, { name: schoolClass.name })"
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

const props = defineProps({ schoolClass: Object })

const isOpen = ref(true)
const form = useForm({})

const submit = () => {
    form.delete(route('admin.classes.destroy', props.schoolClass.id), {
        preserveScroll: true,
        onSuccess: () => { isOpen.value = false },
    })
}

const handleCancel = () => {
    isOpen.value = false
    setTimeout(() => router.visit(route('admin.classes.index'), { preserveState: true, preserveScroll: true }), 150)
}
</script>
