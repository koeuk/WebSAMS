// Fetches the university logo + favicon from the public /branding API and
// exposes ready-to-use URLs, falling back to the bundled /logo1.png.
export const useBranding = () => {
  const config = useRuntimeConfig()
  const apiBase = (config.public.apiBase as string) || ''
  const origin = apiBase.replace(/\/api\/?$/, '')
  const resolve = (p: string) => (p.startsWith('http') ? p : origin + p)

  const logoUrl = useState<string>('branding_logo', () => '/logo1.png')
  const faviconUrl = useState<string>('branding_favicon', () => '/logo1.png')
  const loaded = useState<boolean>('branding_loaded', () => false)

  const loadBranding = async () => {
    if (loaded.value) return
    loaded.value = true
    try {
      const data = await $fetch<{ logo: string | null; favicon: string | null }>(`${apiBase}/branding`)
      if (data?.logo) logoUrl.value = resolve(data.logo)
      if (data?.favicon) faviconUrl.value = resolve(data.favicon)
    } catch {
      // keep the defaults
    }
  }

  // Fall back to the default logo if the resolved image fails to load
  const onLogoError = () => {
    logoUrl.value = '/logo1.png'
  }

  return { logoUrl, faviconUrl, loadBranding, onLogoError }
}
