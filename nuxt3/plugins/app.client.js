import { defineNuxtPlugin } from '#app';
import axios from 'axios';

// import '@mdi/font/css/materialdesignicons.css';

import { createVuetify } from 'vuetify';
import * as vuetifyComponents from 'vuetify/components';
import * as vuetifyDirectives from 'vuetify/directives';

import useStorage from '@/composables/useStorage';
import useApp from '@/composables/useApp';

const devMode = process.env.NODE_ENV !== 'production';

export default defineNuxtPlugin(async (nuxtApp) => {

	// Vuetify
	nuxtApp.vueApp.use(createVuetify({
		components: vuetifyComponents,
		directives: vuetifyDirectives,
		icons: { defaultSet: 'mdi' },
		defaults: {
			VTextField: {
				// variant: 'outlined',
				// hideDetails: true,
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

		// Set access token
		let storage = useStorage('app', {
			access_token: '',
			accounts: [],
		});

		if (storage.access_token && config.url.startsWith('/api')) {
			config.headers = config.headers || {};
			config.headers['Authorization'] = `Bearer ${storage.access_token}`;
		}
		
		// Set base url api
		if (config.url[0] == '/') {
			let { protocol, hostname } = (new URL(location.href));
			config.url = `${protocol}//${hostname}:${conf.APP_PORT}${config.url}`;
		}

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
	
	// this.$events.on('any-name', (message) => console.log(message));
	// this.$events.run('any-name', 'Hello World');
	nuxtApp.provide('events', {
		list: [],
		on: function(name, call) {
			this.list.push({ name, call });
		},
		run: function(name, param=null) {
			this.list.filter(item => item.name==name).forEach(item => {
				item.call.apply(null, [ param ]);
			});
		},
	});

	useApp().load();
});