export const __ = (key: string, params?: Record<string, unknown>) => {
  const { $i18n } = useNuxtApp()
  return ($i18n as any).t(key, params ?? {})
}
