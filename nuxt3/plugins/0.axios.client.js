import axios from 'axios';

export default defineNuxtPlugin(async (nuxtApp) => {

  axios.interceptors.request.use(config => {
		const conf = useRuntimeConfig();

		if (config.url.startsWith('api://')) {
			let { protocol, hostname } = (new URL(location.href));
			config.baseURL = `${protocol}//${hostname}:${conf.public.APP_PORT}`;
			config.baseUrl = `${protocol}//${hostname}:${conf.public.APP_PORT}`;
			config.url = config.url.replace('api://', 'api/');

			// if (storage.access_token) {
			// 	config.headers['Authorization'] = `Bearer ${storage.access_token}`;
			// }
		}

		return config;
	});

	// nuxtApp.provide('axios', axios);
});