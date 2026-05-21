<template>
    <AdminLayout>
        <Modal
            :show="isOpen"
            :title="__('Delete User')"
            :message="__(`Delete '{name}' ({email})? This action cannot be undone.`, { name: user.name, email: user.email })"
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

const props = defineProps({ user: Object })

const isOpen = ref(true)
const form = useForm({})

const submit = () => {
    form.delete(route('admin.users.destroy', props.user.id), {
        preserveScroll: true,
        onSuccess: () => { isOpen.value = false },
    })
}

const handleCancel = () => {
    isOpen.value = false
    setTimeout(() => router.visit(route('admin.users.index'), { preserveState: true, preserveScroll: true }), 150)
}
</script>
