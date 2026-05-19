<template>
    <AdminLayout>
        <ScheduleForm :open="true" :class-subjects="classSubjects" :time-slots="timeSlots" @update:open="onClose" />
    </AdminLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ScheduleForm from '@/Components/Forms/ScheduleForm.vue';

defineProps({ classSubjects: Array, timeSlots: Array });

const onClose = (val) => { if (!val) router.visit('/admin/schedules'); };
</script>
