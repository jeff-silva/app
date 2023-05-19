import axios from 'axios';
import { useStorage } from '@vueuse/core';

export default defineNuxtPlugin(async (nuxtApp) => {

  axios.interceptors.request.use(config => {
		const conf = useRuntimeConfig();

		if (config.url.startsWith('api://')) {
			let { protocol, hostname } = (new URL(location.href));
			config.baseURL = `${protocol}//${hostname}:${conf.public.APP_PORT}`;
			config.baseUrl = `${protocol}//${hostname}:${conf.public.APP_PORT}`;
			config.url = config.url.replace('api://', 'api/');

			const state = useStorage('access_token', '');

			if (state.value) {
				config.headers['Authorization'] = `Bearer ${state.value}`;
			}
		}

		return config;
	});

	// nuxtApp.provide('axios', axios);
});