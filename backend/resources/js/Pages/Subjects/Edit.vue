<template>
    <AdminLayout>
        <SubjectForm :open="true" :subject="subject" :courses="courses" @update:open="onClose" />
    </AdminLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SubjectForm from '@/Components/Forms/SubjectForm.vue';

defineProps({ subject: Object, courses: Array });

const onClose = (val) => { if (!val) router.visit('/admin/subjects'); };
</script>
