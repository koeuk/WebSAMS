<script setup lang="ts">
const { user, logout } = useAuth()

const navigation = [
  { name: 'Dashboard', href: '/' },
  { name: 'My Attendance', href: '/attendance' },
  { name: 'Notifications', href: '/notifications' },
  { name: 'Profile', href: '/profile' },
]

const route = useRoute()

const handleLogout = async () => {
  await logout()
  navigateTo('/login')
}
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <aside class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200">
      <div class="flex items-center gap-3 h-16 px-6 border-b-2 border-beltei-gold bg-beltei text-white">
        <img src="/logo.png" alt="BELTEI" class="h-10" />
        <h1 class="text-xl font-bold">WebSAMS</h1>
      </div>
      <nav class="p-4 space-y-1">
        <NuxtLink
          v-for="item in navigation"
          :key="item.name"
          :to="item.href"
          class="flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors"
          :class="route.path === item.href
            ? 'bg-beltei text-white'
            : 'text-gray-600 hover:bg-gray-100'"
        >
          {{ item.name }}
        </NuxtLink>
      </nav>
    </aside>

    <div class="pl-64">
      <header class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-200">
        <div></div>
        <div class="flex items-center gap-4">
          <span class="text-sm text-gray-600">{{ user?.name }}</span>
          <button @click="handleLogout" class="text-sm text-red-600 hover:text-red-800">Logout</button>
        </div>
      </header>
      <main class="p-6">
        <slot />
      </main>
    </div>
  </div>
</template>
