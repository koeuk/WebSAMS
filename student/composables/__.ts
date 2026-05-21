import en from '~/locale/en.json'
import km from '~/locale/km.json'
import zh from '~/locale/zh.json'

const messages: Record<string, Record<string, string>> = { en, km, zh }

const ALL_LOCALES = [
  { code: 'en', name: 'English' },
  { code: 'km', name: 'ខ្មែរ' },
  { code: 'zh', name: '中文' },
]

const fetchLocaleSettings = async (
  enabled: Ref<string[]>,
  defaultLang: Ref<string>,
) => {
  try {
    const config = useRuntimeConfig()
    const apiBase = (config.public as any).apiBase as string
    const res = await $fetch<{ enabled_languages: string[]; default_language?: string }>(`${apiBase}/student/locales`)

    // English is always available as the fallback locale, even if not explicitly listed.
    if (Array.isArray(res?.enabled_languages) && res.enabled_languages.length) {
      const list = res.enabled_languages.filter((c) => messages[c])
      enabled.value = list.includes('en') ? list : ['en', ...list]
    }
    if (res?.default_language && messages[res.default_language]) {
      defaultLang.value = res.default_language
    }
  } catch {
    // keep defaults if fetch fails
  }
}

export const useI18n = () => {
  const defaultLang = useState<string>('defaultLanguage', () => 'en')
  const locale = useCookie<string>('locale', { default: () => defaultLang.value })
  const enabled = useState<string[]>('enabledLanguages', () => ['en', 'km', 'zh'])
  // Fetch every time on the client until we get a successful result.
  // (Don't gate via useState — that's preserved from SSR and would skip the client fetch.)
  if (import.meta.client) {
    const fetchedClient = useState<boolean>('enabledLanguagesFetchedClient', () => false)
    if (!fetchedClient.value) {
      fetchedClient.value = true
      fetchLocaleSettings(enabled, defaultLang)
    }
  }
  const locales = computed(() => ALL_LOCALES.filter((l) => enabled.value.includes(l.code)))
  watch(enabled, (list) => {
    if (!list.includes(locale.value)) locale.value = list.includes(defaultLang.value) ? defaultLang.value : (list[0] ?? 'en')
  })
  return {
    locale,
    locales,
    setLocale: (code: string) => {
      if (enabled.value.includes(code)) locale.value = code
    },
  }
}

export const useLocale = () => useI18n().locale

export const __ = (key: string, params?: Record<string, string | number>): string => {
  const { locale } = useI18n()
  const dict = messages[locale.value] ?? messages.en
  let str = dict[key] ?? key
  if (params) {
    for (const [k, v] of Object.entries(params)) {
      str = str.replace(new RegExp(`\\{${k}\\}`, 'g'), String(v))
    }
  }
  return str
}
