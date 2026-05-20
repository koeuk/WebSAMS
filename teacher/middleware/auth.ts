export default defineNuxtRouteMiddleware((to) => {
  const { isAuthenticated, init } = useAuth()

  init()

  if (!isAuthenticated.value && to.path !== '/auth/login') {
    return navigateTo('/auth/login')
  }

  if (isAuthenticated.value && to.path === '/auth/login') {
    return navigateTo('/')
  }
})
