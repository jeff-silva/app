// https://v3.nuxtjs.org/api/configuration/nuxt.config
export default defineNuxtConfig({
  css: ['vuetify/lib/styles/main.css'],
  build: {
    transpile: ['vuetify'],
  },
  vite: {
    define: {
      'process.env.DEBUG': false,
    },
    server: {
      proxy: {
        '/api/': { target: `localhost:${process.env.APP_PORT}` },
        // '/uploads/': { target: env.SERVER_HOST },
        // '/files/': { target: env.SERVER_HOST },
      },
      // watch: {
      //   usePolling: true,
      // },
    },
  },
});
