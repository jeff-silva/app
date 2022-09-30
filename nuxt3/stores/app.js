import { defineStore } from 'pinia';
import axios from 'axios';

// import * as nuxt from '#app';

export const useAppStore = defineStore({
    id: 'app',
    
    state: () => ({
      loading: false,
      access_token: (window.localStorage.getItem('useApp.access_token') || ''),
      user: false,
      accounts: JSON.parse(window.localStorage.getItem('useApp.accounts') || '[]'),
      settings: {},
    }),

    actions: {
      async load() {
        if (!(!this.user && this.access_token)) return;
        this.accounts.forEach(acc => {
          if (acc.access_token != this.access_token) return;
          this.user = { id: this.access_token, email: acc.email };
        });
      },

      async login(credentials={email:'', password:''}) {
        this.loading = true;
        const { data } = await axios.get('https://randomuser.me/api/?results=1');
        this.user = {
            id: data.results[0].login.uuid,
            email: data.results[0].email,
        };
        this.access_token = this.user.id;
        this.loading = false;
        await this.accountAdd(this.user.email, this.access_token);
        await this.load();
      },

      async logout() {
        if (!this.user) return;
        this.accountRemove(this.user.email);
        this.user = false;
        this.access_token = '';
      },

      async accountAdd(email, access_token) {
        await this.accountRemove(email);
        this.accounts.push({ email, access_token });
        this.storeData();
      },

      async accountSwitch(email) {
        this.accounts.forEach((acc, index) => {
          if (acc.email!=email) return;
          this.access_token = acc.access_token;
          this.user = { id:acc.access_token, email:acc.email };
        });
        await this.storeData();
        await this.load();
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
        await this.storeData();
      },

      async storeData() {
        window.localStorage.setItem('useApp.access_token', this.access_token);
        window.localStorage.setItem('useApp.accounts', JSON.stringify(this.accounts));
      },
    },
});