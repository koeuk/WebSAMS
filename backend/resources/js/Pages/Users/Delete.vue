<template>
    <AdminLayout>
        <Modal
            :show="isOpen"
            title="Delete User"
            :message="`Delete '${user.name}' (${user.email})? This action cannot be undone.`"
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
