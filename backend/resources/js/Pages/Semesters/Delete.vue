<template>
    <AdminLayout>
        <Modal
            :show="isOpen"
            title="Delete Semester"
            :message="`Delete '${semester.name}' (${semester.academic_year})?`"
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

const props = defineProps({ semester: Object })

const isOpen = ref(true)
const form = useForm({})

const submit = () => {
    form.delete(route('admin.semesters.destroy', props.semester.id), {
        preserveScroll: true,
        onSuccess: () => { isOpen.value = false },
    })
}

const handleCancel = () => {
    isOpen.value = false
    setTimeout(() => router.visit(route('admin.semesters.index'), { preserveState: true, preserveScroll: true }), 150)
}
</script>
