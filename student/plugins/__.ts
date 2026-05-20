export default defineNuxtPlugin((nuxtApp) => {
  const __ = (key: string, params?: Record<string, unknown>) =>
    (nuxtApp.$i18n as any).t(key, params ?? {})

  nuxtApp.vueApp.config.globalProperties.__ = __

  return {
    provide: { __ },
  }
})
