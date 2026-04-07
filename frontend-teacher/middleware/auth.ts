export default defineNuxtRouteMiddleware((to) => {
  const { isAuthenticated, init } = useAuth()

  init()

  if (!isAuthenticated.value && to.path !== '/login') {
    return navigateTo('/login')
  }

  if (isAuthenticated.value && to.path === '/login') {
    return navigateTo('/')
  }
})
