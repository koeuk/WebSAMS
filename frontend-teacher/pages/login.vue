<script setup lang="ts">
definePageMeta({ layout: false, middleware: 'auth' })

const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

const { login } = useAuth()

const submit = async () => {
  error.value = ''
  loading.value = true
  try {
    await login(email.value, password.value)
    navigateTo('/')
  } catch (e: any) {
    error.value = e?.data?.message || 'Invalid credentials.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md">
      <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-8">
        <div class="text-center mb-8">
          <img src="/logo.png" alt="BELTEI International University" class="h-20 mx-auto mb-3" />
          <h1 class="text-2xl font-bold text-gray-900">WebSAMS</h1>
          <p class="text-sm text-gray-600 mt-1">Teacher Portal</p>
        </div>

        <div v-if="error" class="mb-4 px-4 py-3 rounded-md bg-red-50 border border-red-200 text-sm text-red-800">
          {{ error }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input v-model="email" type="email" required autofocus class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" placeholder="teacher1@websams.com" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input v-model="password" type="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" />
          </div>
          <button type="submit" :disabled="loading" class="w-full bg-beltei text-white py-2 px-4 rounded-md hover:bg-beltei-dark disabled:opacity-50 text-sm font-medium">
            {{ loading ? 'Signing in...' : 'Sign In' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>
