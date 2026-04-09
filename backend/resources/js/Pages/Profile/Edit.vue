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
    name: props.user.name, email: props.user.email, phone: props.user.phone || '',
    current_password: '', new_password: '', new_password_confirmation: '', profile_photo: null,
});

const submit = () => {
    form.post('/admin/profile', { _method: 'PUT', forceFormData: true });
};
</script>

<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">My Profile</h2>
                <p class="text-sm text-slate-500 mt-1">Manage your personal information and password</p>
            </div>

            <FlashMessage />

            <div class="max-w-xl space-y-6">
                <div class="card p-8">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                                Profile Information
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Photo</label>
                                    <div class="flex items-center gap-4">
                                        <img v-if="photoPreview" :src="photoPreview" class="h-20 w-20 rounded-xl object-cover ring-2 ring-slate-100" />
                                        <div v-else class="h-20 w-20 rounded-xl bg-gradient-to-br from-beltei to-beltei-dark flex items-center justify-center text-white text-2xl font-bold ring-2 ring-slate-100">
                                            {{ user.name?.charAt(0) }}
                                        </div>
                                        <label class="btn-secondary cursor-pointer text-[13px]">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Upload
                                            <input type="file" accept="image/*" @change="handlePhoto" class="hidden" />
                                        </label>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name</label>
                                        <input v-model="form.name" type="text" required class="input-modern" />
                                        <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Email</label>
                                        <input v-model="form.email" type="email" required class="input-modern" />
                                        <p v-if="form.errors.email" class="text-[12px] text-rose-500 mt-1">{{ form.errors.email }}</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Phone</label>
                                    <input v-model="form.phone" type="text" class="input-modern" />
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-rose-400"></div>
                                Change Password
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Current Password</label>
                                    <input v-model="form.current_password" type="password" class="input-modern" />
                                    <p v-if="form.errors.current_password" class="text-[12px] text-rose-500 mt-1">{{ form.errors.current_password }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">New Password</label>
                                        <input v-model="form.new_password" type="password" class="input-modern" />
                                        <p v-if="form.errors.new_password" class="text-[12px] text-rose-500 mt-1">{{ form.errors.new_password }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-[13px] font-medium text-slate-600 mb-1.5">Confirm New Password</label>
                                        <input v-model="form.new_password_confirmation" type="password" class="input-modern" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" :disabled="form.processing" class="btn-primary">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                                {{ form.processing ? 'Saving...' : 'Save Profile' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
