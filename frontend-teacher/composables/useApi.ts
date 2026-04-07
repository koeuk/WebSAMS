export const useApi = () => {
  const config = useRuntimeConfig()
  const apiBase = config.public.apiBase as string
  const { token, logout } = useAuth()

  const apiFetch = async <T>(url: string, options: any = {}): Promise<T> => {
    try {
      return await $fetch<T>(`${apiBase}${url}`, {
        ...options,
        headers: {
          ...options.headers,
          Authorization: `Bearer ${token.value}`,
        },
      })
    } catch (error: any) {
      if (error?.response?.status === 401) {
        await logout()
        navigateTo('/login')
      }
      throw error
    }
  }

  return { apiFetch }
}
