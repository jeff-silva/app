import { defineNuxtPlugin } from '#app';
import axios from 'axios';

import '@mdi/font/css/materialdesignicons.css';

import { createVuetify } from 'vuetify';
import * as vuetifyComponents from 'vuetify/components';
import * as vuetifyDirectives from 'vuetify/directives';

const devMode = process.env.NODE_ENV !== 'production';

export default defineNuxtPlugin((nuxtApp) => {

  // Vuetify
  nuxtApp.vueApp.use(createVuetify({
    components: vuetifyComponents,
    directives: vuetifyDirectives,
    icons: { defaultSet: 'mdi' },
    defaults: {
        VTextField: {
            variant: 'outlined',
        },
    },
    theme: {
        defaultTheme: 'light',
        themes: {
            light: {
                dark: false,
                colors: {},
            },
            dark: {
                dark: true,
                colors: {},
            },
        },
    },
  }));



  // Intercept axios
  axios.interceptors.request.use(config => {
    const conf = useRuntimeConfig();
    console.log(conf.APP_PORT);
    
    // const { hostname } = (new URL(location.href));
    // config.url = config.url.replace('api://', `localhost:${process.env.APP_PORT}/`);
    // config.headers['Content-Type'] = 'multipart/form-data';

    // if (devMode && (config.url||'').startsWith('/api')) {
    //     const access_token = localStorage.getItem('access_token') || false;
    //     if (access_token) {
    //         config.headers = config.headers || {};
    //         config.headers['Authorization'] = `Bearer ${access_token}`;
    //     }
    // }

    console.log('axios.config', config);
    return config;
  });



  // Filters:
  // {{ $filter.method(param) }}
  nuxtApp.vueApp.config.globalProperties.$filter = {
    dateHuman(value, format='DD/MM/YYYY ') {
        const formatted = useDateFormat(value, format).value;
        return formatted.includes('NaN')? '': formatted;
    },
    timeHuman(value, format='HH:mm') {
        const formatted = useDateFormat(value, format).value;
        return formatted.includes('NaN')? '': formatted;
    },
    dateTimeHuman(value, format='DD/MM/YYYY - HH:mm') {
        const formatted = useDateFormat(value, format).value;
        return formatted.includes('NaN')? '': formatted;
    },
    filesizeHuman(value) {
        if (!value || isNaN(value)) return '0kB';
        let i = Math.floor( Math.log(+value) / Math.log(1024) );
        return ( +value / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
    },
    singularPlural(number, singular, plural) {
        return number==1? singular: plural;
    },
  };


  // Provide
  // this.$provider
  nuxtApp.provide('axios', axios);
  nuxtApp.provide('devMode', devMode);
  nuxtApp.provide('log', console.log);
  nuxtApp.provide('key', function(value) {
      return typeof value=='object'? JSON.stringify(value): value;
  });
});