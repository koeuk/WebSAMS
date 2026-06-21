export default defineNuxtRouteMiddleware(async (to) => {
  // The token lives in localStorage, which is unavailable during SSR.
  // Letting the server run these checks makes it wrongly redirect to
  // /auth/login, and the client then bounces /auth/login → / — so a
  // direct load/refresh of any protected page (e.g. /profile) never lands.
  // Auth is enforced on the client only.
  if (import.meta.server) return

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
