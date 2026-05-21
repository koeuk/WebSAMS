import { ref } from 'vue'
import en from '@/locale/en.json'
import km from '@/locale/km.json'
import zh from '@/locale/zh.json'

const messages = { en, km, zh }

const LOCALES = [
    { code: 'en', name: 'English' },
    { code: 'km', name: 'ខ្មែរ' },
    { code: 'zh', name: '中文' },
]

const stored = typeof window !== 'undefined' ? window.localStorage.getItem('locale') : null
const locale = ref(stored && messages[stored] ? stored : 'en')

const setLocale = (code) => {
    if (!messages[code]) return
    locale.value = code
    if (typeof window !== 'undefined') window.localStorage.setItem('locale', code)
}

export const useI18n = () => ({
    locale,
    locales: ref(LOCALES),
    setLocale,
})

export const useLocale = () => locale

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
