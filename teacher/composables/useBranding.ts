// Fetches the university logo from the public /branding API and exposes a
// ready-to-use URL, falling back to the bundled /logo1.png when unavailable.
export const useBranding = () => {
  const config = useRuntimeConfig()
  const apiBase = (config.public.apiBase as string) || ''
  const origin = apiBase.replace(/\/api\/?$/, '')

  const logoUrl = useState<string>('branding_logo', () => '/logo1.png')
  const loaded = useState<boolean>('branding_loaded', () => false)

  const loadBranding = async () => {
    if (loaded.value) return
    loaded.value = true
    try {
      const data = await $fetch<{ logo: string | null }>(`${apiBase}/branding`)
      if (data?.logo) {
        logoUrl.value = data.logo.startsWith('http') ? data.logo : origin + data.logo
      }
    } catch {
      // keep the default logo
    }
  }

  // Fall back to the default logo if the resolved image fails to load
  const onLogoError = () => {
    logoUrl.value = '/logo1.png'
  }

  return { logoUrl, loadBranding, onLogoError }
}
