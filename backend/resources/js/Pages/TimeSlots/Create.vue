<template>
    <AdminLayout>
        <TimeSlotForm :open="true" @update:open="onClose" />
    </AdminLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import TimeSlotForm from '@/Components/Forms/TimeSlotForm.vue';

const onClose = (val) => { if (!val) router.visit('/admin/time-slots'); };
</script>
