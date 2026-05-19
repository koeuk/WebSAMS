<template>
    <AdminLayout>
        <SemesterForm :open="true" @update:open="onClose" />
    </AdminLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SemesterForm from '@/Components/Forms/SemesterForm.vue';

const onClose = (val) => { if (!val) router.visit('/admin/semesters'); };
</script>
