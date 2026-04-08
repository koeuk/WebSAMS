<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';

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
    university_name: props.settings.university_name || '',
    university_address: props.settings.university_address || '',
    university_phone: props.settings.university_phone || '',
    university_email: props.settings.university_email || '',
    university_website: props.settings.university_website || '',
    university_logo: null,
});

const submit = () => {
    form.post('/admin/settings', {
        _method: 'PUT',
        forceFormData: true,
    });
};
</script>

<template>
    <AdminLayout>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">University Settings</h2>

        <FlashMessage />

        <div class="max-w-xl">
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">University Logo</label>
                        <div class="flex items-center gap-4">
                            <img v-if="logoPreview" :src="logoPreview" class="h-20 object-contain border rounded" />
                            <div v-else class="h-20 w-20 bg-gray-200 flex items-center justify-center text-gray-400 text-xs rounded">No logo</div>
                            <input type="file" accept="image/*" @change="handleLogo" class="text-sm" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">University Name</label>
                        <input v-model="form.university_name" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g. BELTEI International University" />
                        <p v-if="form.errors.university_name" class="text-sm text-red-600 mt-1">{{ form.errors.university_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea v-model="form.university_address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input v-model="form.university_phone" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input v-model="form.university_email" type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
                            <p v-if="form.errors.university_email" class="text-sm text-red-600 mt-1">{{ form.errors.university_email }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                        <input v-model="form.university_website" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="e.g. https://beltei.edu.kh" />
                    </div>

                    <button type="submit" :disabled="form.processing" class="px-4 py-2 text-sm font-medium text-white bg-beltei rounded-md hover:bg-beltei-dark disabled:opacity-50">
                        {{ form.processing ? 'Saving...' : 'Save Settings' }}
                    </button>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
