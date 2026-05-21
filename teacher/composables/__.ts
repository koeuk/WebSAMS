import en from '~/locale/en.json'
import km from '~/locale/km.json'
import zh from '~/locale/zh.json'

const messages: Record<string, any> = { en, km, zh }

const LOCALES = [
  { code: 'en', name: 'English' },
  { code: 'km', name: 'ខ្មែរ' },
  { code: 'zh', name: '中文' },
]

const get = (obj: any, path: string): unknown =>
  path.split('.').reduce<any>((o, k) => (o == null ? o : o[k]), obj)

export const useI18n = () => {
  const locale = useCookie<string>('locale', { default: () => 'en' })
  return {
    locale,
    locales: ref(LOCALES),
    setLocale: (code: string) => { locale.value = code },
  }
}

export const useLocale = () => useI18n().locale

export const __ = (key: string, params?: Record<string, string | number>): string => {
  const { locale } = useI18n()
  const dict = messages[locale.value] ?? messages.en
  const value = get(dict, key) ?? get(messages.en, key)
  let str = typeof value === 'string' ? value : key
  if (params) {
    for (const [k, v] of Object.entries(params)) {
      str = str.replace(new RegExp(`\\{${k}\\}`, 'g'), String(v))
    }
  }
  return str
}
