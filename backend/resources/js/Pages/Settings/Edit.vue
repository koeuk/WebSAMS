<template>
    <AdminLayout>
        <div class="animate-fade-in">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">{{ __('Settings') }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ __('Configure your institution and system preferences') }}</p>
            </div>

            <div class="grid grid-cols-12 gap-6">
                <!-- Section nav -->
                <aside class="col-span-12 md:col-span-3">
                    <Card class="overflow-hidden">
                        <nav class="p-2">
                            <button
                                v-for="s in sections"
                                :key="s.key"
                                type="button"
                                @click="active = s.key"
                                :class="[
                                    'w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-medium transition-colors text-left',
                                    active === s.key
                                        ? 'bg-slate-900 text-white'
                                        : 'text-slate-600 hover:bg-slate-50',
                                ]"
                            >
                                <component :is="s.icon" class="w-4 h-4 shrink-0" />
                                {{ __(s.label) }}
                            </button>
                        </nav>
                    </Card>
                </aside>

                <!-- Section content -->
                <div class="col-span-12 md:col-span-9">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- General -->
                        <Card v-show="active === 'general'">
                            <CardContent class="p-8 space-y-5">
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('University Logo') }}</Label>
                                    <div class="flex items-center gap-4">
                                        <img v-if="logoPreview" :src="logoPreview" class="h-20 object-contain rounded-xl ring-2 ring-slate-100" />
                                        <div v-else class="h-20 w-20 bg-slate-100 flex items-center justify-center text-slate-400 text-xs rounded-xl">{{ __('No logo') }}</div>
                                        <Button as="label" variant="outline" class="cursor-pointer text-[13px]">
                                            <ImagePlus class="w-4 h-4" />
                                            {{ __('Upload') }}
                                            <input type="file" accept="image/*" @change="handleLogo" class="hidden" />
                                        </Button>
                                    </div>
                                </div>

                                <TranslatableInput
                                    v-model="form.university_name"
                                    :label="__('University Name')"
                                    :placeholder="{ en: 'e.g. BELTEI International University', km: 'ឧ. សាកលវិទ្យាល័យអន្តរជាតិ BELTEI', zh: '例如:BELTEI 国际大学' }"
                                />

                                <TranslatableRichText
                                    v-model="form.university_address"
                                    :label="__('Address')"
                                    :placeholder="__('Enter university address…')"
                                />

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Phone') }}</Label>
                                        <Input v-model="form.university_phone" type="text" />
                                    </div>
                                    <div>
                                        <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Email') }}</Label>
                                        <Input v-model="form.university_email" type="email" />
                                        <p v-if="form.errors.university_email" class="text-[12px] text-rose-500 mt-1">{{ form.errors.university_email }}</p>
                                    </div>
                                </div>

                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Website') }}</Label>
                                    <Input v-model="form.university_website" type="text" placeholder="https://beltei.edu.kh" />
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Academic -->
                        <Card v-show="active === 'academic'">
                            <CardContent class="p-8 space-y-5">
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Late Threshold (minutes)') }}</Label>
                                    <Input v-model.number="form.late_threshold_minutes" type="number" min="0" max="120" />
                                    <p class="text-[12px] text-slate-400 mt-1">{{ __('Mark a student late if check-in is past this many minutes.') }}</p>
                                </div>
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Attendance Token Expiry (minutes)') }}</Label>
                                    <Input v-model.number="form.token_expiry_minutes" type="number" min="1" max="1440" />
                                    <p class="text-[12px] text-slate-400 mt-1">{{ __('Default validity for teacher-generated attendance tokens.') }}</p>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Localization -->
                        <Card v-show="active === 'localization'">
                            <CardContent class="p-8 space-y-5">
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-2">{{ __('Enabled Languages') }}</Label>
                                    <p class="text-[12px] text-slate-400 mb-3">{{ __('Disabled languages will be hidden everywhere — translation tabs, language switcher, student & teacher portals. English is always enabled as a fallback.') }}</p>
                                    <div class="space-y-2">
                                        <label
                                            v-for="lang in allLanguages"
                                            :key="lang.code"
                                            class="flex items-center justify-between rounded-lg border border-slate-200 px-4 py-3 cursor-pointer hover:bg-slate-50 transition-colors"
                                            :class="{ 'opacity-60 cursor-not-allowed': lang.code === 'en' }"
                                        >
                                            <div class="flex items-center gap-3">
                                                <span class="inline-flex items-center justify-center min-w-[36px] px-2 py-1 rounded-md bg-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-700">{{ lang.code }}</span>
                                                <span class="text-[13px] font-medium text-slate-700">{{ lang.name }}</span>
                                                <span v-if="lang.code === 'en'" class="text-[11px] text-slate-400">{{ __('Always on') }}</span>
                                            </div>
                                            <button
                                                type="button"
                                                @click="toggleLanguage(lang.code)"
                                                :disabled="lang.code === 'en'"
                                                :class="[
                                                    'relative inline-flex h-6 w-11 items-center rounded-full transition-colors',
                                                    form.enabled_languages.includes(lang.code) ? 'bg-slate-900' : 'bg-slate-200',
                                                    lang.code === 'en' ? 'cursor-not-allowed' : '',
                                                ]"
                                            >
                                                <span
                                                    :class="[
                                                        'inline-block h-4 w-4 transform rounded-full bg-white transition-transform',
                                                        form.enabled_languages.includes(lang.code) ? 'translate-x-6' : 'translate-x-1',
                                                    ]"
                                                />
                                            </button>
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Default Language') }}</Label>
                                    <Select v-model="form.default_language">
                                        <SelectTrigger class="w-full">
                                            <SelectValue :placeholder="__('Select language')" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="lang in allLanguages.filter((l) => form.enabled_languages.includes(l.code))"
                                                :key="lang.code"
                                                :value="lang.code"
                                            >{{ lang.name }}</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Timezone') }}</Label>
                                    <Select v-model="form.timezone">
                                        <SelectTrigger class="w-full">
                                            <SelectValue :placeholder="__('Select timezone')" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="tz in timezones" :key="tz" :value="tz">{{ tz }}</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Date Format') }}</Label>
                                    <Select v-model="form.date_format">
                                        <SelectTrigger class="w-full">
                                            <SelectValue :placeholder="__('Select date format')" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="d in dateFormats" :key="d.value" :value="d.value">{{ d.label }}</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Appearance -->
                        <Card v-show="active === 'appearance'">
                            <CardContent class="p-8 space-y-5">
                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Primary Color') }}</Label>
                                    <div class="flex items-center gap-3">
                                        <input
                                            v-model="form.theme_primary_color"
                                            type="color"
                                            class="h-10 w-14 rounded-lg border border-slate-200 cursor-pointer bg-white"
                                        />
                                        <Input v-model="form.theme_primary_color" type="text" placeholder="#D4A017" class="w-32" />
                                        <div
                                            class="h-10 w-10 rounded-lg ring-2 ring-slate-100"
                                            :style="{ background: form.theme_primary_color }"
                                        />
                                    </div>
                                    <p v-if="form.errors.theme_primary_color" class="text-[12px] text-rose-500 mt-1">{{ form.errors.theme_primary_color }}</p>
                                </div>

                                <div class="flex items-center justify-between rounded-lg border border-slate-200 px-4 py-3">
                                    <div>
                                        <Label class="text-[13px] font-medium text-slate-700">{{ __('Dark Mode by Default') }}</Label>
                                        <p class="text-[12px] text-slate-400 mt-0.5">{{ __('New users will start in dark mode.') }}</p>
                                    </div>
                                    <button
                                        type="button"
                                        @click="form.dark_mode_default = !form.dark_mode_default"
                                        :class="[
                                            'relative inline-flex h-6 w-11 items-center rounded-full transition-colors',
                                            form.dark_mode_default ? 'bg-slate-900' : 'bg-slate-200',
                                        ]"
                                    >
                                        <span
                                            :class="[
                                                'inline-block h-4 w-4 transform rounded-full bg-white transition-transform',
                                                form.dark_mode_default ? 'translate-x-6' : 'translate-x-1',
                                            ]"
                                        />
                                    </button>
                                </div>

                                <div>
                                    <Label class="block text-[13px] font-medium text-slate-600 mb-1.5">{{ __('Favicon') }}</Label>
                                    <div class="flex items-center gap-4">
                                        <img v-if="faviconPreview" :src="faviconPreview" class="h-12 w-12 object-contain rounded-lg ring-2 ring-slate-100 bg-white" />
                                        <div v-else class="h-12 w-12 bg-slate-100 flex items-center justify-center text-slate-400 text-xs rounded-lg">{{ __('None') }}</div>
                                        <Button as="label" variant="outline" class="cursor-pointer text-[13px]">
                                            <ImagePlus class="w-4 h-4" />
                                            {{ __('Upload') }}
                                            <input type="file" accept="image/png,image/x-icon,image/svg+xml" @change="handleFavicon" class="hidden" />
                                        </Button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <div class="flex justify-end">
                            <Button type="submit" :disabled="form.processing">
                                <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                                <Check v-else class="w-4 h-4" />
                                {{ form.processing ? __('Saving…') : __('Save Settings') }}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'
import { Building2, GraduationCap, Globe, Palette, ImagePlus, Loader2, Check } from 'lucide-vue-next'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Input } from '@/Components/ui/input'
import { Button } from '@/Components/ui/button'
import { Label } from '@/Components/ui/label'
import { Card, CardContent } from '@/Components/ui/card'
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/Components/ui/select'
import TranslatableInput from '@/Components/Forms/TranslatableInput.vue'
import TranslatableRichText from '@/Components/Forms/TranslatableRichText.vue'
import { __ } from '@/Composables/useTranslate'

const props = defineProps({ settings: Object })

const sections = [
    { key: 'general',      label: 'General',      icon: Building2 },
    { key: 'academic',     label: 'Academic',     icon: GraduationCap },
    { key: 'localization', label: 'Localization', icon: Globe },
    { key: 'appearance',   label: 'Appearance',   icon: Palette },
]

const active = ref('general')

const emptyTranslations = () => ({ en: '', km: '', zh: '' })
const readTranslations = (v) => v && typeof v === 'object' ? { ...emptyTranslations(), ...v } : emptyTranslations()

const form = useForm({
    _method: 'PUT',
    university_name:        readTranslations(props.settings.university_name),
    university_address:     readTranslations(props.settings.university_address),
    university_phone:       props.settings.university_phone || '',
    university_email:       props.settings.university_email || '',
    university_website:     props.settings.university_website || '',
    university_logo:        null,
    favicon:                null,
    late_threshold_minutes: props.settings.late_threshold_minutes ?? 10,
    token_expiry_minutes:   props.settings.token_expiry_minutes ?? 30,
    default_language:       props.settings.default_language || 'en',
    enabled_languages:      Array.isArray(props.settings.enabled_languages) && props.settings.enabled_languages.length ? [...props.settings.enabled_languages] : ['en', 'km', 'zh'],
    timezone:               props.settings.timezone || 'UTC',
    date_format:            props.settings.date_format || 'Y-m-d',
    theme_primary_color:    props.settings.theme_primary_color || '#D4A017',
    dark_mode_default:      !!props.settings.dark_mode_default,
})

const logoPreview = ref(props.settings.university_logo ? `/storage/${props.settings.university_logo}` : null)
const faviconPreview = ref(props.settings.favicon ? `/storage/${props.settings.favicon}` : null)

const handleLogo = (e) => {
    const file = e.target.files[0]
    form.university_logo = file
    if (file) {
        const r = new FileReader()
        r.onload = (ev) => { logoPreview.value = ev.target.result }
        r.readAsDataURL(file)
    }
}

const handleFavicon = (e) => {
    const file = e.target.files[0]
    form.favicon = file
    if (file) {
        const r = new FileReader()
        r.onload = (ev) => { faviconPreview.value = ev.target.result }
        r.readAsDataURL(file)
    }
}

const allLanguages = [
    { code: 'en', name: 'English' },
    { code: 'km', name: 'ខ្មែរ (Khmer)' },
    { code: 'zh', name: '中文 (Chinese)' },
]

const toggleLanguage = (code) => {
    if (code === 'en') return
    const list = form.enabled_languages.includes(code)
        ? form.enabled_languages.filter((c) => c !== code)
        : [...form.enabled_languages, code]
    form.enabled_languages = list
    if (!list.includes(form.default_language)) {
        form.default_language = list[0] ?? 'en'
    }
}

const timezones = ['UTC', 'Asia/Phnom_Penh', 'Asia/Bangkok', 'Asia/Singapore', 'Asia/Shanghai', 'Asia/Tokyo', 'Europe/London', 'America/New_York', 'America/Los_Angeles']

const dateFormats = computed(() => {
    const now = new Date()
    const samples = [
        { value: 'Y-m-d',       label: now.toISOString().slice(0, 10) + '  (YYYY-MM-DD)' },
        { value: 'd/m/Y',       label: now.toLocaleDateString('en-GB') + '  (DD/MM/YYYY)' },
        { value: 'm/d/Y',       label: now.toLocaleDateString('en-US') + '  (MM/DD/YYYY)' },
        { value: 'd M Y',       label: now.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) + '  (DD MMM YYYY)' },
    ]
    return samples
})

const submit = () => {
    form.post('/admin/settings', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => { toast.success(__('Settings saved successfully')) },
        onError: () => { toast.error(__('Failed to save settings')) },
    })
}
</script>
