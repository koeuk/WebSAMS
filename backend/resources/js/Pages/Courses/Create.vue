<template>
    <AdminLayout>
        <CourseForm :open="true" @update:open="onClose" />
    </AdminLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import CourseForm from '@/Components/Forms/CourseForm.vue';

const onClose = (val) => { if (!val) router.visit('/admin/courses'); };
</script>
