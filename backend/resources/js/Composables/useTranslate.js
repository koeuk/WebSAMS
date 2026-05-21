import { ref, computed, watchEffect } from 'vue'
import { usePage } from '@inertiajs/vue3'
import en from '@/locale/en.json'
import km from '@/locale/km.json'
import zh from '@/locale/zh.json'

const messages = { en, km, zh }

const ALL_LOCALES = [
    { code: 'en', name: 'English' },
    { code: 'km', name: 'ខ្មែរ' },
    { code: 'zh', name: '中文' },
]

const enabledCodes = ref(['en', 'km', 'zh'])

const stored = typeof window !== 'undefined' ? window.localStorage.getItem('locale') : null
const locale = ref(stored && messages[stored] ? stored : 'en')

const setLocale = (code) => {
    if (!messages[code]) return
    if (!enabledCodes.value.includes(code)) return
    locale.value = code
    if (typeof window !== 'undefined') window.localStorage.setItem('locale', code)
}

const syncFromPageProps = () => {
    try {
        const page = usePage()
        watchEffect(() => {
            const list = page?.props?.enabled_languages
            if (Array.isArray(list) && list.length) {
                enabledCodes.value = list.filter((c) => messages[c])
                if (!enabledCodes.value.includes(locale.value)) {
                    setLocale(enabledCodes.value[0] ?? 'en')
                }
            }
        })
    } catch (_) {
        // usePage is only available inside Inertia pages; ignore otherwise.
    }
}

let synced = false
const ensureSynced = () => { if (!synced) { synced = true; syncFromPageProps() } }

const enabledLocales = computed(() => ALL_LOCALES.filter((l) => enabledCodes.value.includes(l.code)))

export const useI18n = () => {
    ensureSynced()
    return { locale, locales: enabledLocales, setLocale }
}

export const useLocale = () => locale

export const useEnabledLocales = () => {
    ensureSynced()
    return enabledLocales
}

export const __ = (key, params) => {
    const dict = messages[locale.value] ?? {}
    let str = dict[key] ?? key
    if (params) {
        for (const [k, v] of Object.entries(params)) {
            str = str.replace(new RegExp(`\\{${k}\\}`, 'g'), String(v))
        }
    }
    return str
}
