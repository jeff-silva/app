import { defineStore } from 'pinia';
import axios from 'axios';

// import * as nuxt from '#app';

export const useAppStore = defineStore({
    id: 'app',
    
    state: () => ({
      init: false,
      loading: false,
      access_token: (window.localStorage.getItem('access_token') || ''),
      user: false,
      accounts: JSON.parse(window.localStorage.getItem('useApp.accounts') || '[]'),
      settings: {},
    }),

    actions: {
      async load() {
        if (this.init) return;
        this.init = true;

        this.loading = 'load';
        try {
          const user = await axios.post('/api/auth/me');
          this.user = user.data;
        }
        catch(e) {
          await this.setAccessToken();
        }
        this.loading = false;
      },

      async setAccessToken(token='') {
        window.localStorage.setItem('access_token', token);
        return this.access_token = token;
      },

      async login(credentials={email:'', password:''}) {
        this.loading = 'login';
        const login = await axios.post('/api/auth/login', credentials);
        await this.setAccessToken(login.data.access_token);
        this.loading = false;

        await this.accountAdd(credentials.email, this.access_token);

        this.init = false;
        await this.load();
      },

      async logout() {
        this.loading = 'logout';
        try {
          await axios.post('/api/auth/logout');
        }
        catch(e) {}
        this.accountRemove(this.user.email);
        await this.setAccessToken();
        this.user = false;
        this.loading = false;
      },

      async accountAdd(email, access_token) {
        await this.accountRemove(email);
        this.accounts.push({ email, access_token });
        this.accountsStore();
      },

      async accountSwitch(email) {
        this.accounts.forEach(async (acc, index) => {
          if (acc.email!=email) return;
          await this.setAccessToken(acc.access_token);
          this.init = false;
          await this.load();
        });
      },

      async accountRemove(email) {
        this.accounts.forEach((acc, index) => {
          if (acc.email!=email) return;
          this.accounts.splice(index, 1);
          if (this.user.email==email) {
            this.user = false;
            this.access_token = '';
          }
        });
        await this.accountsStore();
      },

      async accountsStore() {
        window.localStorage.setItem('useApp.accounts', JSON.stringify(this.accounts));
      },
    },
});