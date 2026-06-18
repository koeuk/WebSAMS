export default defineNuxtConfig({
  compatibilityDate: "2024-11-01",
  devtools: { enabled: true },
  devServer: { port: 3002 },
  app: {
    head: {
      title: "BELTEI - WebSAMS Student",
      link: [
        { rel: "icon", type: "image/png", href: "/logo1.png" },
        {
          rel: "stylesheet",
          href: "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css",
        },
      ],
    },
  },
  modules: ["@nuxtjs/tailwindcss", "shadcn-nuxt"],
  components: {
    dirs: [
      {
        path: "~/components",
        pathPrefix: false,
        extensions: ["vue"],
        ignore: ["**/ui/**"],
      },
    ],
  },
  shadcn: {
    prefix: "",
    componentDir: "./components/ui",
  },
  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || "http://127.0.0.1:8000/api",
    },
  },
});
