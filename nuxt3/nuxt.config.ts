// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({

  css: [
    '@mdi/font/css/materialdesignicons.css',
    // 'leaflet/dist/leaflet.css',
  ],

  modules: [

    // https://nuxt.com/modules/vite-pwa-nuxt
    ['@vite-pwa/nuxt', {}],

    // https://vueuse.org/guide/
    ['@vueuse/nuxt', {}],
  ],
})
