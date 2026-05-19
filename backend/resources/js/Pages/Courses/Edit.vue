<template>
    <AdminLayout>
        <CourseForm :open="true" :course="course" @update:open="onClose" />
    </AdminLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import CourseForm from '@/Components/Forms/CourseForm.vue';

defineProps({ course: Object });

const onClose = (val) => { if (!val) router.visit('/admin/courses'); };
</script>
