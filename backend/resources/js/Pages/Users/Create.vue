<template>
    <AdminLayout>
        <UserForm :open="true" :roles="roles" :genders="genders" :statuses="statuses" @update:open="onClose" />
    </AdminLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import UserForm from '@/Components/Forms/UserForm.vue';

defineProps({ roles: Array, genders: Array, statuses: Array });

const onClose = (val) => { if (!val) router.visit('/admin/users'); };
</script>
