import path from 'path';
import fs from 'fs-extra';

// https://v3.nuxtjs.org/api/configuration/nuxt.config
export default defineNuxtConfig({
  // target: 'static',
  // ssr: false,
  // inlineSSRStyles: false,
  
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
  },

  hooks: {
    async close(nuxt) {
      try {
        const dirNuxtBuild = path.join(__dirname, '.output', 'public');
        const dirDest = path.join(__dirname, '.laravel-public');

        const filesIgnore = ['.htaccess', 'favicon.ico', 'index.php', 'robots.txt'];
        (await fs.promises.readdir(dirDest))
          .filter(file => !filesIgnore.includes(file))
          .forEach(async (file) => {
            const fileDir = path.join('.laravel-public', file);
            await fs.promises.rm(fileDir, { recursive: true, force: true });
          });
        
        await fs.copy(dirNuxtBuild, dirDest, { overwrite: true });
        await fs.promises.rename(
          path.join(__dirname, '.laravel-public', 'index.html'),
          path.join(__dirname, '.laravel-public', 'app.html')
        );

        console.log([dirNuxtBuild, 'moved to', dirDest].join(' '));
      } catch (err) {}
    },
  },
});
