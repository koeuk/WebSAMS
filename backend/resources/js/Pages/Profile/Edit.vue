<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('My Profile') }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ __('Manage your personal information and password') }}</p>
            </div>

            <FlashMessage />

            <div class="max-w-xl space-y-6">
                <Card>
                    <CardContent class="p-8">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                                    {{ __('Profile Information') }}
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Photo') }}</Label>
                                        <div class="flex items-center gap-4">
                                            <img v-if="photoPreview" :src="photoPreview" class="h-20 w-20 rounded-xl object-cover ring-2 ring-slate-100" />
                                            <div v-else class="h-20 w-20 rounded-xl bg-gradient-to-br from-beltei to-beltei-dark flex items-center justify-center text-white text-2xl font-bold ring-2 ring-slate-100">
                                                {{ user.name?.charAt(0) }}
                                            </div>
                                            <Button as="label" variant="outline" class="cursor-pointer text-[13px]">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                {{ __('Upload') }}
                                                <input type="file" accept="image/*" @change="handlePhoto" class="hidden" />
                                            </Button>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Name') }}</Label>
                                            <Input v-model="form.name" type="text" required />
                                            <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
                                        </div>
                                        <div>
                                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Email') }}</Label>
                                            <Input v-model="form.email" type="email" required />
                                            <p v-if="form.errors.email" class="text-[12px] text-rose-500 mt-1">{{ form.errors.email }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Phone') }}</Label>
                                        <Input v-model="form.phone" type="text" />
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-rose-400"></div>
                                    {{ __('Change Password') }}
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Current Password') }}</Label>
                                        <Input v-model="form.current_password" type="password" />
                                        <p v-if="form.errors.current_password" class="text-[12px] text-rose-500 mt-1">{{ form.errors.current_password }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('New Password') }}</Label>
                                            <Input v-model="form.new_password" type="password" />
                                            <p v-if="form.errors.new_password" class="text-[12px] text-rose-500 mt-1">{{ form.errors.new_password }}</p>
                                        </div>
                                        <div>
                                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Confirm New Password') }}</Label>
                                            <Input v-model="form.new_password_confirmation" type="password" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-2">
                                <Button type="submit" :disabled="form.processing">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                                    {{ form.processing ? __('Saving...') : __('Save Profile') }}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Card, CardContent } from '@/Components/ui/card';
import { __ } from '@/Composables/useTranslate';

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
    _method: 'put',
    name: props.user.name, email: props.user.email, phone: props.user.phone || '',
    current_password: '', new_password: '', new_password_confirmation: '', profile_photo: null,
});

const submit = () => {
    form.post('/admin/profile', { forceFormData: true });
};
</script>
