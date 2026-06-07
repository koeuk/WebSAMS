<template>
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>

<script setup lang="ts">
// Load the university logo and use it as the browser favicon (falls back to /logo1.png)
const { faviconUrl, loadBranding } = useBranding()
onMounted(loadBranding)
useHead({
  link: [{ rel: 'icon', type: 'image/png', href: faviconUrl, key: 'favicon' }],
})
</script>
