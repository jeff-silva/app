// import { useState } from '#app';
import { ref } from 'vue';
// import axios from 'axios';
import { defineStore } from 'pinia';
import * as nuxt from '#app';
import { useStorage } from '@vueuse/core';

export default function() {
  const appStore = defineStore({
    id: 'app',

    state: () => ({
      loaded: false,
      user: {},
      access_token: (localStorage.getItem('access_token') || false),
      auths: useStorage('app-auths', []),
    }),

    actions: {
      async load(forced=false) {
        try {
            if (this.loaded && !forced) return;
            const { $axios } = nuxt.useNuxtApp();
            const { data } = await $axios({method:"get", url:"/api/app/load"});
            for(let i in data) {
                this[i] = data[i];
            }
        }
        catch(err) {}
        this.loaded = true;
      },

      async login(params={}) {
          try {
            const { $axios } = nuxt.useNuxtApp();
            const data = JSON.parse(JSON.stringify(params));
            const resp = await $axios({method:"post", url:"/api/auth/login", data});
            this.authAdd(params.email, resp.data.access_token);
            this.setAccessToken(resp.data.access_token);
            this.load();
          }
          catch(err) {
            console.log(err);
          }
      },

      async logout() {
        const { $axios } = nuxt.useNuxtApp();
        const resp = await $axios({method:"post", url:"/api/auth/logout"});
        this.setAccessToken(false);
        this.user = false;
        this.authRemove(this.user.email);
      },

      async authAdd(email, token) {
        for(let i in this.auths) {
          const auth = this.auths[i];
          if (auth.email==email) {
            auth.token = token;
            return false;
          }
        }
        this.auths.push({ email, token});
      },

      async authRemove(email) {
        for(let i in this.auths) {
          const auth = this.auths[i];
          if (auth.email==email) {
            this.auths.splice(i, 1);
            break;
          }
        }
      },

      async authSwitch(email, redirect=false) {
        for(let i in this.auths) {
          const auth = this.auths[i];
          if (auth.email==email) {
            await this.setAccessToken(auth.token);
            if (!this.user) this.authRemove(auth.email);
            !redirect? location.reload(): (location.href=redirect);
            break;
          }
        }
      },

      async me() {
        if (!this.access_token || this.user) return;
        try {
          const { $axios } = nuxt.useNuxtApp();
          const resp = await $axios({method:"post", url:"/api/auth/me"});
          this.user = resp.data;
        }
        catch(err) {}
      },

      setAccessToken(access_token) {
        if (access_token) {
          this.access_token = access_token;
          localStorage.setItem('access_token', access_token);
          return;
        }

        this.access_token = false;
        localStorage.removeItem('access_token');
      },

      setTitle(title) {
        this.title = title;
      },
    },
  });

  return appStore();
};