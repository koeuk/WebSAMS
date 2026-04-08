<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';

const props = defineProps({ user: Object });

const photoPreview = ref(props.user.profile_photo ? `/storage/${props.user.profile_photo}` : null);
const handlePhoto = (e) => {
    const file = e.target.files[0];
    form.profile_photo = file;
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => { photoPreview.value = e.target.result; };
        reader.readAsDataURL(file);
    }
};

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    phone: props.user.phone || '',
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
    profile_photo: null,
});

const submit = () => {
    form.post('/admin/profile', {
        _method: 'PUT',
        forceFormData: true,
    });
};
</script>

<template>
    <AdminLayout>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">My Profile</h2>

        <FlashMessage />

        <div class="max-w-xl space-y-6">
            <!-- Profile Info -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Profile Information</h3>
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                        <div class="flex items-center gap-4">
                            <img v-if="photoPreview" :src="photoPreview" class="h-20 w-20 rounded-full object-cover border" />
                            <div v-else class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center text-gray-400 text-2xl font-bold">
                                {{ user.name?.charAt(0) }}
                            </div>
                            <input type="file" accept="image/*" @change="handlePhoto" class="text-sm" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input v-model="form.name" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                            <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input v-model="form.email" type="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                            <p v-if="form.errors.email" class="text-sm text-red-600 mt-1">{{ form.errors.email }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input v-model="form.phone" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 border-b pb-2 pt-2">Change Password</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input v-model="form.current_password" type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                        <p v-if="form.errors.current_password" class="text-sm text-red-600 mt-1">{{ form.errors.current_password }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input v-model="form.new_password" type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                            <p v-if="form.errors.new_password" class="text-sm text-red-600 mt-1">{{ form.errors.new_password }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input v-model="form.new_password_confirmation" type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                        </div>
                    </div>

                    <button type="submit" :disabled="form.processing" class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark disabled:opacity-50">
                        {{ form.processing ? 'Saving...' : 'Save Profile' }}
                    </button>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
