export default defineNuxtConfig({
  compatibilityDate: '2024-11-01',
  devtools: { enabled: true },
  devServer: { port: 3002 },
  app: {
    head: {
      title: 'BELTEI - WebSAMS Student',
      link: [{ rel: 'icon', type: 'image/png', href: '/logo.png' }],
    },
  },
  modules: [
    '@nuxtjs/tailwindcss',
    'shadcn-nuxt',
  ],
  shadcn: {
    prefix: '',
    componentDir: './components/ui',
  },
  runtimeConfig: {
    public: {
      apiBase: 'http://localhost:8000/api',
    },
  },
})
