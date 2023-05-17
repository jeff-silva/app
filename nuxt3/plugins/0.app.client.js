import axios from 'axios';

export default defineNuxtPlugin(async (nuxtApp) => {

  axios.interceptors.request.use(config => {
		// const conf = useRuntimeConfig();

		// // Set access token
		// let storage = useStorage('app', {
		// 	access_token: '',
		// 	accounts: [],
		// });

		// if (storage.access_token && config.url.startsWith('/api')) {
		// 	config.headers = config.headers || {};
		// 	config.headers['Authorization'] = `Bearer ${storage.access_token}`;
		// }
		
		// // Set base url api
		// if (config.url[0] == '/') {
		// 	let { protocol, hostname } = (new URL(location.href));
		// 	config.url = `${protocol}//${hostname}:${conf.APP_PORT}${config.url}`;
		// }

		return config;
	});

	nuxtApp.provide('axios', axios);
});