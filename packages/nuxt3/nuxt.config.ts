import { defineNuxtConfig } from 'nuxt';

// https://v3.nuxtjs.org/api/configuration/nuxt.config
export default defineNuxtConfig({
  ssr: false,

  css: ['vuetify/lib/styles/main.css'],

  modules: [
    // https://pinia.vuejs.org/
    ['@pinia/nuxt', {}],
  ],

  buildModules: [
    // https://vuetifyjs.com/en/getting-started/installation/
    // ['@nuxtjs/vuetify', {}],

    // https://vueuse.org/
    ['@vueuse/nuxt', {}],
  ],

  vite: {
    define: {
        'process.env.DEBUG': false,
    },
    server: {
      proxy: {
        '/api/': { target: `http://localhost:${process.env.SERVER_PORT}` },
        '/uploads/': { target: `http://localhost:${process.env.SERVER_PORT}` },
        '/files/': { target: `http://localhost:${process.env.SERVER_PORT}` },
      },
    },
  },
})
