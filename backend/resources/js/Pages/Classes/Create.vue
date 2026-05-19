<template>
    <AdminLayout>
        <ClassForm :open="true" @update:open="onClose" />
    </AdminLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ClassForm from '@/Components/Forms/ClassForm.vue';

const onClose = (val) => { if (!val) router.visit('/admin/classes'); };
</script>
