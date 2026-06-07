<template>
    <ModalForm :open="open" size="lg" @update:open="emit('update:open', $event)">
        <template #title>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                    <UserRound class="w-4 h-4 text-slate-600" />
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">{{ isEdit ? __('Edit User') : __('Create User') }}</h2>
                    <p class="text-xs text-slate-500">{{ isEdit ? __("Update {name}'s information", { name: user?.name }) : __('Add a new user to the system') }}</p>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Basic Info -->
            <div>
                <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-beltei-gold"></div>
                    {{ __('Basic Info') }}
                </h3>
                <div class="space-y-4">
                    <!-- Profile photo -->
                    <div class="flex items-center gap-4">
                        <div class="relative w-16 h-16 rounded-full overflow-hidden ring-2 ring-slate-200 bg-slate-100 flex items-center justify-center shrink-0">
                            <img v-if="photoPreview" :src="photoPreview" alt="" class="w-full h-full object-cover" />
                            <UserRound v-else class="w-7 h-7 text-slate-300" />
                        </div>
                        <div>
                            <input ref="photoInput" type="file" accept="image/*" class="hidden" @change="onPhotoChange" />
                            <div class="flex items-center gap-2">
                                <Button variant="outline" type="button" class="h-8 px-3 text-xs gap-1.5" @click="photoInput?.click()">
                                    <Upload class="w-3.5 h-3.5" /> {{ __('Upload photo') }}
                                </Button>
                                <Button v-if="form.profile_photo" variant="ghost" type="button" class="h-8 px-2.5 text-xs text-slate-500" @click="clearPhoto">{{ __('Remove') }}</Button>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1.5">{{ __('JPG or PNG, up to 2MB.') }}</p>
                            <p v-if="form.errors.profile_photo" class="text-[12px] text-rose-500 mt-1">{{ form.errors.profile_photo }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Name') }} <span class="text-rose-500">*</span></Label>
                            <Input v-model="form.name" type="text" @update:model-value="touch('name')" />
                            <p v-if="error('name') || form.errors.name" class="text-[12px] text-rose-500 mt-1">{{ error('name') || form.errors.name }}</p>
                        </div>
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Email') }} <span class="text-rose-500">*</span></Label>
                            <Input v-model="form.email" type="email" @update:model-value="touch('email')" />
                            <p v-if="error('email') || form.errors.email" class="text-[12px] text-rose-500 mt-1">{{ error('email') || form.errors.email }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">
                                {{ __('Password') }}
                                <span v-if="isEdit" class="text-slate-400 font-normal">{{ __('(leave blank to keep)') }}</span>
                                <span v-else class="text-rose-500">*</span>
                            </Label>
                            <Input v-model="form.password" type="password" :placeholder="isEdit ? '••••••••' : ''" @update:model-value="touch('password')" />
                            <p v-if="error('password') || form.errors.password" class="text-[12px] text-rose-500 mt-1">{{ error('password') || form.errors.password }}</p>
                        </div>
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Role') }} <span class="text-rose-500">*</span></Label>
                            <Select v-model="form.role">
                                <SelectTrigger class="w-full"><SelectValue :placeholder="__('Select role')" /></SelectTrigger>
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
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('ID Number') }}</Label>
                            <Input v-model="form.id_number" type="text" :placeholder="form.role === 'student' ? 'STU-2025-001' : 'TCH-001'" />
                            <p v-if="form.errors.id_number" class="text-[12px] text-rose-500 mt-1">{{ form.errors.id_number }}</p>
                        </div>
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Gender') }}</Label>
                            <Select v-model="form.gender">
                                <SelectTrigger class="w-full"><SelectValue :placeholder="__('Select')" /></SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="g in genders" :key="g" :value="g">
                                        {{ g.charAt(0).toUpperCase() + g.slice(1).toLowerCase() }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Status') }}</Label>
                            <Select v-model="form.status">
                                <SelectTrigger class="w-full"><SelectValue :placeholder="__('Select status')" /></SelectTrigger>
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
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Date of Birth') }}</Label>
                            <DatePicker v-model="form.date_of_birth" :placeholder="__('Pick date of birth')" />
                        </div>
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Phone') }}</Label>
                            <Input v-model="form.phone" type="text" />
                        </div>
                    </div>
                    <div>
                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Address') }}</Label>
                        <RichTextEditor v-model="form.address" :placeholder="__('Enter address…')" />
                    </div>
                </div>
            </div>

            <!-- Student Info -->
            <template v-if="form.role === 'student'">
                <div>
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                        {{ __('Student Info') }}
                    </h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Year Level') }}</Label>
                                <Select v-model="form.year_level">
                                    <SelectTrigger class="w-full"><SelectValue :placeholder="__('Select')" /></SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="yl in yearLevels" :key="yl.value" :value="yl.value">
                                            {{ yl.label }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Enrollment Date') }}</Label>
                                <DatePicker v-model="form.enrollment_date" :placeholder="__('Pick enrollment date')" />
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Guardian Name') }}</Label>
                                <Input v-model="form.guardian_name" type="text" />
                            </div>
                        </div>
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Guardian Phone') }}</Label>
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
                        {{ __('Teacher Info') }}
                    </h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Department') }}</Label>
                                <Input v-model="form.department" type="text" :placeholder="__('e.g. Computer Science')" />
                            </div>
                            <div>
                                <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Qualification') }}</Label>
                                <Input v-model="form.qualification" type="text" :placeholder="__('e.g. M.Sc.')" />
                            </div>
                        </div>
                        <div>
                            <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Hire Date') }}</Label>
                            <DatePicker v-model="form.hire_date" :placeholder="__('Pick hire date')" />
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <template #footer>
            <Button variant="outline" type="button" @click="close">{{ __('Cancel') }}</Button>
            <Button type="button" :disabled="!canSubmit || form.processing" @click="submit">
                <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                {{ isEdit ? __('Save Changes') : __('Create User') }}
            </Button>
        </template>
    </ModalForm>
</template>

<script setup>
import { computed, watch, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { UserRound, Loader2, Upload } from 'lucide-vue-next'
import { toast } from 'vue-sonner'
import ModalForm from '@/Components/ModalForm.vue'
import DatePicker from '@/Components/DatePicker.vue'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Button } from '@/Components/ui/button'
import RichTextEditor from '@/Components/RichTextEditor.vue'
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select'
import { useFormValidation } from '@/Composables/useFormValidation'
import { __ } from '@/Composables/useTranslate'

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

const form = useForm({
    name: '', email: '', password: '', role: 'student',
    phone: '', id_number: '', gender: '', date_of_birth: '', address: '', status: 'active',
    guardian_name: '', guardian_phone: '', enrollment_date: '',
    department: '', qualification: '', hire_date: '',
    profile_photo: null,
})

// Profile photo upload (preview = newly chosen file, else the existing stored photo)
const photoInput = ref(null)
const photoPreview = ref(null)
const existingPhotoUrl = () => props.user?.profile_photo ? `/storage/${props.user.profile_photo}` : null
const onPhotoChange = (e) => {
    const file = e.target.files?.[0]
    if (!file) return
    form.profile_photo = file
    photoPreview.value = URL.createObjectURL(file)
}
const clearPhoto = () => {
    form.profile_photo = null
    photoPreview.value = existingPhotoUrl()
    if (photoInput.value) photoInput.value.value = ''
}

const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/

const { touch, markAllTouched, reset, error } = useFormValidation({
    name:     () => !form.name?.trim() ? __('Name is required') : null,
    email:    () => {
        if (!form.email?.trim()) return __('Email is required')
        if (!emailRe.test(form.email)) return __('Invalid email')
        return null
    },
    password: () => (!isEdit.value && !form.password) ? __('Password is required') : null,
})

watch(() => props.user, (u) => {
    reset()
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
    form.profile_photo = null
    photoPreview.value = existingPhotoUrl()
}, { immediate: true })

const canSubmit = computed(() => {
    if (!form.name?.trim()) return false
    if (!form.email?.trim() || !emailRe.test(form.email)) return false
    if (!isEdit.value && !form.password) return false
    return true
})

const close = () => emit('update:open', false)

const submit = () => {
    if (!markAllTouched()) return
    if (isEdit.value) {
        // POST + method spoofing so the file upload survives (PHP can't parse multipart PUT)
        form.transform((data) => ({ ...data, _method: 'put' })).post(route('admin.users.update', props.user.id), {
            preserveScroll: true, preserveState: true, forceFormData: true,
            onSuccess: () => { close() },
            onError: () => { toast.error(__('Failed to update user')) },
        })
    } else {
        form.transform((data) => data).post(route('admin.users.store'), {
            preserveScroll: true, preserveState: true, forceFormData: true,
            onSuccess: () => { close(); form.reset(); form.role = 'student'; form.status = 'active' },
            onError: () => { toast.error(__('Failed to create user')) },
        })
    }
}
</script>
