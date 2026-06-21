export default defineNuxtRouteMiddleware(async (to) => {
  const { isAuthenticated, user, init, fetchUser } = useAuth()

  init()

  if (!isAuthenticated.value && to.path !== '/auth/login') {
    return navigateTo('/auth/login')
  }

  if (isAuthenticated.value && to.path === '/auth/login') {
    return navigateTo('/')
  }

  // Restore the user after a page refresh — init() only loads the token,
  // so without this the nav avatar/account menu render blank.
  if (isAuthenticated.value && !user.value) {
    await fetchUser()
  }
})
