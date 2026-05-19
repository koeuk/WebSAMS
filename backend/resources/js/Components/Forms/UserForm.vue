<template>
    <ModalForm
        :open="open"
        @update:open="emit('update:open', $event)"
        :title="isEdit ? 'Edit User' : 'Create User'"
        :description="isEdit ? `Update ${user?.name}'s information.` : 'Add a new user to the system.'"
        size="lg"
    >
        <form @submit.prevent="submit">
            <div class="max-h-[70vh] overflow-y-auto pr-1 space-y-6 py-2">

                <!-- Basic Info -->
                <div>
                    <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                        Basic Info
                    </h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name *</Label>
                                <Input v-model="form.name" type="text" required />
                                <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Email *</Label>
                                <Input v-model="form.email" type="email" required />
                                <p v-if="form.errors.email" class="text-[12px] text-rose-500 mt-1">{{ form.errors.email }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">
                                    Password
                                    <span v-if="isEdit" class="text-slate-400">(leave blank to keep)</span>
                                    <span v-else>*</span>
                                </Label>
                                <Input v-model="form.password" type="password" :required="!isEdit" />
                                <p v-if="form.errors.password" class="text-[12px] text-rose-500 mt-1">{{ form.errors.password }}</p>
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Role *</Label>
                                <Select v-model="form.role">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select role" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="role in props.roles" :key="role" :value="role">
                                            {{ role.charAt(0).toUpperCase() + role.slice(1) }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">ID Number</Label>
                                <Input v-model="form.id_number" type="text" :placeholder="form.role === 'student' ? 'STU-2025-001' : 'TCH-001'" />
                                <p v-if="form.errors.id_number" class="text-[12px] text-rose-500 mt-1">{{ form.errors.id_number }}</p>
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Gender</Label>
                                <Select v-model="form.gender">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="g in props.genders" :key="g.value" :value="g.value">
                                            {{ g.name.charAt(0).toUpperCase() + g.name.slice(1).toLowerCase() }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Status</Label>
                                <Select v-model="form.status">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select status" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="s in props.statuses" :key="s.value" :value="s.value">
                                            {{ s.name.charAt(0).toUpperCase() + s.name.slice(1).toLowerCase() }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Date of Birth</Label>
                                <DatePicker v-model="form.date_of_birth" placeholder="Pick date of birth" />
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Phone</Label>
                                <Input v-model="form.phone" type="text" />
                            </div>
                        </div>
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Address</Label>
                            <Textarea v-model="form.address" rows="2" />
                        </div>
                    </div>
                </div>

                <!-- Student Info -->
                <template v-if="form.role === 'student'">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                            Student Info
                        </h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Year Level</Label>
                                    <Select v-model="form.year_level">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Select" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="1">Year 1</SelectItem>
                                            <SelectItem value="2">Year 2</SelectItem>
                                            <SelectItem value="3">Year 3</SelectItem>
                                            <SelectItem value="4">Year 4</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Enrollment Date</Label>
                                    <DatePicker v-model="form.enrollment_date" placeholder="Pick enrollment date" />
                                </div>
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Guardian Name</Label>
                                    <Input v-model="form.guardian_name" type="text" />
                                </div>
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Guardian Phone</Label>
                                <Input v-model="form.guardian_phone" type="text" />
                            </div>
                        </div>
                    </div>
                </template>

<script setup>
import { computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ModalForm from '@/Components/ModalForm.vue';
import DatePicker from '@/Components/DatePicker.vue';
import { Input } from '@/Components/ui/input';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import { Textarea } from '@/Components/ui/textarea';
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select';

const props = defineProps({
    open:     { type: Boolean, required: true },
    user:     { type: Object,  default: null },
    roles:    { type: Array,   default: () => [] },
    genders:  { type: Array,   default: () => [] },
    statuses: { type: Array,   default: () => [] },
});

const emit = defineEmits(['update:open']);

const isEdit = computed(() => !!props.user);

const form = useForm({
    name: '',
    email: '',
    password: '',
    role: 'student',
    phone: '',
    id_number: '',
    gender: '',
    date_of_birth: '',
    address: '',
    status: 'active',
    guardian_name: '',
    guardian_phone: '',
    enrollment_date: '',
    department: '',
    qualification: '',
    hire_date: '',
});

watch(() => props.user, (u) => {
    if (u) {
        form.name = u.name;
        form.email = u.email;
        form.password = '';
        form.role = u.role;
        form.phone = u.phone || '';
        form.id_number = u.id_number || '';
        form.gender = u.gender?.value ?? u.gender ?? '';
        form.date_of_birth = u.date_of_birth?.split('T')[0] || '';
        form.address = u.address || '';
        form.status = u.status?.value ?? u.status ?? 'active';
        form.guardian_name = u.guardian_name || '';
        form.guardian_phone = u.guardian_phone || '';
        form.enrollment_date = u.enrollment_date?.split('T')[0] || '';
        form.department = u.department || '';
        form.qualification = u.qualification || '';
        form.hire_date = u.hire_date?.split('T')[0] || '';
    } else {
        form.reset();
        form.role = 'student';
        form.status = 'active';
    }
}, { immediate: true });

const close = () => emit('update:open', false);

const submit = () => {
    if (isEdit.value) {
        form.put(`/admin/users/${props.user.id}`, { onSuccess: close });
    } else {
        form.post('/admin/users', { onSuccess: () => { close(); form.reset(); } });
    }
};
</script>
