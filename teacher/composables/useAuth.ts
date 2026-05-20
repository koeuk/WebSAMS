interface User {
  id: number
  name: string
  email: string
  role: string
}

const user = ref<User | null>(null)
const token = ref<string | null>(null)

export const useAuth = () => {
  const config = useRuntimeConfig()
  const apiBase = config.public.apiBase as string

  const isAuthenticated = computed(() => !!token.value)

  const init = () => {
    if (import.meta.client) {
      token.value = localStorage.getItem('token')
    }
  }

  const login = async (email: string, password: string) => {
    const data = await $fetch<{ token: string; user: User }>(`${apiBase}/login`, {
      method: 'POST',
      body: { email, password },
    })
    token.value = data.token
    user.value = data.user
    if (import.meta.client) {
      localStorage.setItem('token', data.token)
    }
  }

  const fetchUser = async () => {
    if (!token.value) return
    try {
      user.value = await $fetch<User>(`${apiBase}/user`, {
        headers: { Authorization: `Bearer ${token.value}` },
      })
    } catch {
      logout()
    }
  }

  const logout = async () => {
    if (token.value) {
      try {
        await $fetch(`${apiBase}/logout`, {
          method: 'POST',
          headers: { Authorization: `Bearer ${token.value}` },
        })
      } catch {}
    }
    token.value = null
    user.value = null
    if (import.meta.client) {
      localStorage.removeItem('token')
    }
  }

  return { user, token, isAuthenticated, init, login, fetchUser, logout }
}
