// https://v3.nuxtjs.org/api/configuration/nuxt.config
export default defineNuxtConfig({
  target: 'static',
  
  runtimeConfig: {
    public: {
      APP_PORT: process.env.APP_PORT,
    },
  },

  css: [
    'vuetify/lib/styles/main.css',
    '@mdi/font/css/materialdesignicons.css',
  ],

  modules: [

    // https://vueuse.org/guide/
    ['@vueuse/nuxt', {}],
    
    // https://pinia.vuejs.org/
    ['@pinia/nuxt', {}],

  ],

  build: {
    transpile: ['vuetify'],
  },

  vite: {
    define: {
      // 'process.env.DEBUG': false,
      'process.env.DEBUG': false,
    },
    server: {
      // proxy: {
      //   '/api/': { target: `localhost:${process.env.APP_PORT}` },
      //   '/uploads/': { target: env.SERVER_HOST },
      //   '/files/': { target: env.SERVER_HOST },
      // },
      // watch: {
      //   usePolling: true,
      // },
    },
  },
});
