// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  ssr: false,

  runtimeConfig: {
    public: {
      APP_PORT: process.env.APP_PORT,
    },
  },

  css: [
    '@mdi/font/css/materialdesignicons.css',
    // 'leaflet/dist/leaflet.css',
  ],

  modules: [

    // https://vueuse.org/guide/
    ['@vueuse/nuxt', {}],

    // https://pinia.vuejs.org/
    // https://github.com/elk-zone/elk/blob/main/config/pwa.ts
    ['@pinia/nuxt', {}],

    // https://nuxt.com/modules/vite-pwa-nuxt
    ['@vite-pwa/nuxt', {
      mode: 'development',
      disable: false,
      // scope: '/',
      // srcDir: './service-worker',
      // filename: 'sw.ts',
      strategies: 'injectManifest',
      injectRegister: false,
      includeManifestIcons: false,
      manifest: false,
      injectManifest: {
        globPatterns: ['**/*.{js,json,css,html,txt,svg,png,ico,webp,woff,woff2,ttf,eot,otf,wasm}'],
        globIgnores: ['emojis/**', 'shiki/**', 'manifest**.webmanifest'],
      },
      devOptions: {
        enabled: true,
        type: 'module',
      },
    }],

    ['nuxt-monaco-editor', {}],
  ],

  // build: {
  //   plugins: [
  //     new MonacoWebpackPlugin({
  //       languages: ['javascript', 'html', 'css', 'php'],
  //     }),
  //   ],
  // },
});
