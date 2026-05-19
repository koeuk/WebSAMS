<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">University Settings</h2>
                <p class="text-sm text-slate-500 mt-1">Configure your institution's information</p>
            </div>

            <FlashMessage />

            <div class="max-w-xl">
                <Card>
                    <CardContent class="p-8">
                        <form @submit.prevent="submit" class="space-y-5">
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">University Logo</Label>
                                <div class="flex items-center gap-4">
                                    <img v-if="logoPreview" :src="logoPreview" class="h-20 object-contain rounded-xl ring-2 ring-slate-100" />
                                    <div v-else class="h-20 w-20 bg-slate-100 flex items-center justify-center text-slate-400 text-xs rounded-xl">No logo</div>
                                    <Button as="label" variant="outline" class="cursor-pointer text-[13px]">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        Upload
                                        <input type="file" accept="image/*" @change="handleLogo" class="hidden" />
                                    </Button>
                                </div>
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">University Name</Label>
                                <Input v-model="form.university_name" type="text" placeholder="e.g. BELTEI International University" />
                                <p v-if="form.errors.university_name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.university_name }}</p>
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Address</Label>
                                <Textarea v-model="form.university_address" rows="2" />
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Phone</Label>
                                    <Input v-model="form.university_phone" type="text" />
                                </div>
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Email</Label>
                                    <Input v-model="form.university_email" type="email" />
                                    <p v-if="form.errors.university_email" class="text-[12px] text-rose-500 mt-1">{{ form.errors.university_email }}</p>
                                </div>
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Website</Label>
                                <Input v-model="form.university_website" type="text" placeholder="e.g. https://beltei.edu.kh" />
                            </div>
                            <div class="pt-2">
                                <Button type="submit" :disabled="form.processing">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                                    {{ form.processing ? 'Saving...' : 'Save Settings' }}
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
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Textarea } from '@/Components/ui/textarea';
import { Card, CardContent } from '@/Components/ui/card';

const props = defineProps({ settings: Object });

const logoPreview = ref(props.settings.university_logo ? `/storage/${props.settings.university_logo}` : null);
const handleLogo = (e) => {
    const file = e.target.files[0];
    form.university_logo = file;
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => { logoPreview.value = e.target.result; };
        reader.readAsDataURL(file);
    }
};

const form = useForm({
    _method: 'PUT',
    university_name: props.settings.university_name || '', university_address: props.settings.university_address || '',
    university_phone: props.settings.university_phone || '', university_email: props.settings.university_email || '',
    university_website: props.settings.university_website || '', university_logo: null,
});

const submit = () => { form.post('/admin/settings', { forceFormData: true }); };
</script>
