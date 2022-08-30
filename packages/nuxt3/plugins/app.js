// // require('dotenv').config({ path: '../.env' });

import { defineNuxtPlugin } from '#app';
import axios from 'axios';

import '@mdi/font/css/materialdesignicons.css';

import { createVuetify } from 'vuetify';
import * as vuetifyComponents from 'vuetify/components';
import * as vuetifyDirectives from 'vuetify/directives';

// import 'leaflet/dist/leaflet.css'
// import * as leafletComponents from '@vue-leaflet/vue-leaflet';

// import imask from 'imask';

// const devMode = process.env.NODE_ENV !== 'production';

export default defineNuxtPlugin((nuxtApp) => {
//     for(let i in leafletComponents) {
//         nuxtApp.vueApp.component(i, leafletComponents[i]);
//     }
    
    nuxtApp.vueApp.use(createVuetify({
        components: vuetifyComponents,
        directives: vuetifyDirectives,
        icons: { defaultSet: 'mdi' },
        defaults: {
            VTextField: {
                variant: 'outlined',
            },
        },
        // theme: {
        //     defaultTheme: 'light',
        //     themes: {
        //         light: {
        //             dark: false,
        //             colors: {},
        //         },
        //         dark: {
        //             dark: true,
        //             colors: {},
        //         },
        //     },
        // },
    }));
    
    // Intercept axios
    axios.interceptors.request.use(config => {
        // config.headers['Content-Type'] = 'multipart/form-data';

        // if (devMode && (config.url||'').startsWith('/api')) {
        //     const access_token = localStorage.getItem('access_token') || false;
        //     if (access_token) {
        //         config.headers = config.headers || {};
        //         config.headers['Authorization'] = `Bearer ${access_token}`;
        //     }
        // }

        return config;
    });

    
//     // Vue directives
//     // https://imask.js.org/
//     nuxtApp.vueApp.directive('imask', {
//         mounted(el, bind, node, prevNode) {
//             let input = el.querySelector('input[type=text]');
//             if (! input) return;
//             // presets: phone, cellphone, money, date, time, datetime
//             let options = typeof bind.value=='object'? bind.value: {mask: bind.value};
//             imask(input, options);
//         },
//     });


//     // {{ $filters.filterName(variable) }}
//     nuxtApp.vueApp.config.globalProperties.$filters = {
//         dateHuman(value, format='DD/MM/YYYY ') {
//             const formatted = useDateFormat(value, format).value;
//             return formatted.includes('NaN')? '': formatted;
//         },
//         timeHuman(value, format='HH:mm') {
//             const formatted = useDateFormat(value, format).value;
//             return formatted.includes('NaN')? '': formatted;
//         },
//         dateTimeHuman(value, format='DD/MM/YYYY - HH:mm') {
//             const formatted = useDateFormat(value, format).value;
//             return formatted.includes('NaN')? '': formatted;
//         },
//         filesizeHuman(value) {
//             if (!value || isNaN(value)) return '0kB';
//             let i = Math.floor( Math.log(+value) / Math.log(1024) );
//             return ( +value / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
//         },
//         singularPlural(number, singular, plural) {
//             return number==1? singular: plural;
//         },
//     };

    nuxtApp.provide('axios', axios);
//     nuxtApp.provide('devMode', devMode);
//     nuxtApp.provide('log', console.log);
//     nuxtApp.provide('key', function(value) {
//         return typeof value=='object'? JSON.stringify(value): value;
//     });
    
//     // this.$alert('Confirm action?')
//     nuxtApp.provide('alert', (message) => {
//         return new Promise((resolve, reject) => {
//             let modal = Object.assign(document.createElement('div'), {
//                 innerHTML: `<div class="modal-confirm-no" style="position:absolute; top:0; left:0; width:100%; height:100%; background:#00000022; z-index:9999; display:flex; align-items:center; justify-content:center;">
//                     <div class="v-card v-theme--light v-card--density-default v-card--variant-contained mx-auto" style="min-width:300px; max-width:300px;">
//                         <div class="v-card-text">${message}</div>
//                         <div class="v-card-actions">
//                             <button type="button" class="modal-confirm-ok v-btn v-theme--light bg-primary v-btn--density-default v-btn--size-default v-btn--variant-text">
//                                 Ok
//                             </button>
//                         </div>
//                     </div>
//                 </div>`,
//             });

//             modal.querySelectorAll('.modal-confirm-ok').forEach(elem => {
//                 elem.addEventListener('click', ev => {
//                     modal.remove();
//                 });
//             });

//             document.body.appendChild(modal);
//         });
//     });

//     // this.$confirm('Confirm action?').then(...)
//     nuxtApp.provide('confirm', (message) => {
//         return new Promise((resolve, reject) => {
//             let modal = Object.assign(document.createElement('div'), {
//                 innerHTML: `<div class="modal-confirm-no" style="position:absolute; top:0; left:0; width:100%; height:100%; background:#00000022; z-index:9999; display:flex; align-items:center; justify-content:center;">
//                     <div class="v-card v-theme--light v-card--density-default v-card--variant-contained mx-auto" style="min-width:300px; max-width:300px;">
//                         <div class="v-card-text">${message}</div>
//                         <div class="v-card-actions">
//                             <button type="button" class="modal-confirm-yes v-btn v-theme--light bg-primary v-btn--density-default v-btn--size-default v-btn--variant-text">
//                                 Sim
//                             </button>
//                             <button type="button" class="modal-confirm-no v-btn v-theme--light v-btn--density-default v-btn--size-default v-btn--variant-text">
//                                 Não
//                             </button>
//                         </div>
//                     </div>
//                 </div>`,
//             });

//             modal.querySelectorAll('.modal-confirm-no').forEach(elem => {
//                 elem.addEventListener('click', ev => {
//                     modal.remove();
//                 });
//             });
            
//             modal.querySelectorAll('.modal-confirm-yes').forEach(elem => {
//                 elem.addEventListener('click', ev => {
//                     modal.remove();
//                     setTimeout(() => resolve(), 500);
//                 });
//             });

//             document.body.appendChild(modal);
//         });
//     });
});