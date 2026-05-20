<template>
    <Form :validation-schema="schema" v-slot="{ setErrors, meta }" :initial-values="form">
        <ModalForm :open="open" size="lg" @update:open="emit('update:open', $event)">
            <template #title>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                        <UserRound class="w-4 h-4 text-slate-600" />
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">{{ isEdit ? 'Edit User' : 'Create User' }}</h2>
                        <p class="text-xs text-slate-500">{{ isEdit ? `Update ${user?.name}'s information` : 'Add a new user to the system' }}</p>
                    </div>
                </div>
            </template>

            <div class="space-y-6">
                <!-- Basic Info -->
                <div>
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                        Basic Info
                    </h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Name <span class="text-rose-500">*</span></Label>
                                <Field name="name" v-slot="{ field, errorMessage }">
                                    <Input v-bind="field" v-model="form.name" type="text" />
                                    <p v-if="errorMessage" class="text-[12px] text-rose-500 mt-1">{{ errorMessage }}</p>
                                </Field>
                                <p v-if="form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Email <span class="text-rose-500">*</span></Label>
                                <Field name="email" v-slot="{ field, errorMessage }">
                                    <Input v-bind="field" v-model="form.email" type="email" />
                                    <p v-if="errorMessage" class="text-[12px] text-rose-500 mt-1">{{ errorMessage }}</p>
                                </Field>
                                <p v-if="form.errors.email" class="text-[12px] text-rose-500 mt-1">{{ form.errors.email }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">
                                    Password
                                    <span v-if="isEdit" class="text-slate-400 font-normal">(leave blank to keep)</span>
                                    <span v-else class="text-rose-500">*</span>
                                </Label>
                                <Input v-model="form.password" type="password" :placeholder="isEdit ? '••••••••' : ''" />
                                <p v-if="form.errors.password" class="text-[12px] text-rose-500 mt-1">{{ form.errors.password }}</p>
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Role <span class="text-rose-500">*</span></Label>
                                <Select v-model="form.role">
                                    <SelectTrigger class="w-full"><SelectValue placeholder="Select role" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="r in roles" :key="r" :value="r">
                                            {{ r.charAt(0).toUpperCase() + r.slice(1) }}
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
                                    <SelectTrigger class="w-full"><SelectValue placeholder="Select" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="g in genders" :key="g" :value="g">
                                            {{ g.charAt(0).toUpperCase() + g.slice(1).toLowerCase() }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Status</Label>
                                <Select v-model="form.status">
                                    <SelectTrigger class="w-full"><SelectValue placeholder="Select status" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="s in statuses" :key="s" :value="s">
                                            {{ s.charAt(0).toUpperCase() + s.slice(1).toLowerCase() }}
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
                            <RichTextEditor v-model="form.address" placeholder="Enter address…" />
                        </div>
                    </div>
                </div>

                <!-- Student Info -->
                <template v-if="form.role === 'student'">
                    <div>
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                            Student Info
                        </h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Year Level</Label>
                                    <Select v-model="form.year_level">
                                        <SelectTrigger class="w-full"><SelectValue placeholder="Select" /></SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="yl in yearLevels" :key="yl.value" :value="yl.value">
                                                {{ yl.label }}
                                            </SelectItem>
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

                <!-- Teacher Info -->
                <template v-if="form.role === 'teacher'">
                    <div>
                        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                            <div class="w-1.5 h-1.5 rounded-full bg-blue-400"></div>
                            Teacher Info
                        </h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Department</Label>
                                    <Input v-model="form.department" type="text" placeholder="e.g. Computer Science" />
                                </div>
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Qualification</Label>
                                    <Input v-model="form.qualification" type="text" placeholder="e.g. M.Sc." />
                                </div>
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">Hire Date</Label>
                                <DatePicker v-model="form.hire_date" placeholder="Pick hire date" />
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <template #footer>
                <Button variant="outline" type="button" @click="close">Cancel</Button>
                <Button type="button" :disabled="!meta.valid || form.processing" @click="submit(setErrors)">
                    <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                    {{ isEdit ? 'Save Changes' : 'Create User' }}
                </Button>
            </template>
        </ModalForm>
    </Form>
</template>

<script setup>
import { computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Form, Field } from 'vee-validate'
import * as yup from 'yup'
import { UserRound, Loader2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import ModalForm from '@/Components/ModalForm.vue'
import DatePicker from '@/Components/DatePicker.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import RichTextEditor from '@/Components/RichTextEditor.vue'
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select'

const props = defineProps({
    open:        { type: Boolean, required: true },
    user:        { type: Object,  default: null },
    roles:       { type: Array,   default: () => [] },
    genders:     { type: Array,   default: () => [] },
    statuses:    { type: Array,   default: () => [] },
    yearLevels:  { type: Array,   default: () => [] },
})
const emit = defineEmits(['update:open'])
const isEdit = computed(() => !!props.user)

const schema = yup.object({
    name:  yup.string().required('Name is required'),
    email: yup.string().email('Invalid email').required('Email is required'),
})

const form = useForm({
    name: '', email: '', password: '', role: 'student',
    phone: '', id_number: '', gender: '', date_of_birth: '', address: '', status: 'active',
    guardian_name: '', guardian_phone: '', enrollment_date: '',
    department: '', qualification: '', hire_date: '',
})

watch(() => props.user, (u) => {
    if (u) {
        form.name = u.name; form.email = u.email; form.password = ''; form.role = u.role
        form.phone = u.phone || ''; form.id_number = u.id_number || ''
        form.gender = u.gender ?? ''
        form.date_of_birth = u.date_of_birth?.split('T')[0] || ''
        form.address = u.address || ''
        form.status = u.status ?? 'active'
        form.guardian_name = u.guardian_name || ''; form.guardian_phone = u.guardian_phone || ''
        form.enrollment_date = u.enrollment_date?.split('T')[0] || ''
        form.department = u.department || ''; form.qualification = u.qualification || ''
        form.hire_date = u.hire_date?.split('T')[0] || ''
    } else {
        form.reset(); form.role = 'student'; form.status = 'active'
    }
}, { immediate: true })

const close = () => emit('update:open', false)

const submit = (setErrors) => {
    if (isEdit.value) {
        form.put(route('admin.users.update', props.user.id), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success('User updated successfully'); close() },
            onError: (errors) => { toast.error('Failed to update user'); setErrors(errors) },
        })
    } else {
        form.post(route('admin.users.store'), {
            preserveScroll: true, preserveState: true,
            onSuccess: () => { toast.success('User created successfully'); close(); form.reset(); form.role = 'student'; form.status = 'active' },
            onError: (errors) => { toast.error('Failed to create user'); setErrors(errors) },
        })
    }
}
</script>
