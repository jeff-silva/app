import { watch } from 'vue';
import { defineStore } from 'pinia';
import axios from 'axios';
import _ from 'lodash';

import { useStorage, useEventBus } from '@vueuse/core';
import useAxios from '@/composables/useAxios';

export default (params={}) => {
  const tokenStorage = useStorage('access_token', '');
  const accountsStorage = useStorage('accounts', {});

  const s = defineStore('app', {
    state: () => ({
      ready: false,
      loading: false,
      access_token: tokenStorage.value,
      user: false,
      settings: {},
      permissions: {},
      login: false,
      accounts: accountsStorage.value,
    }),
    actions: {
      async init(force=false) {
        if (force==false && this.ready) return;
        this.ready = false;
        this.loading = true;
        try {
          const { data } = await axios.post('api://app');
          this.ready = true;
          this.user = data.user;
          this.settings = data.settings;
          this.permissions = data.permissions;
        } catch(err) {}
        this.loading = false;
      },
      async setToken(token) {
        tokenStorage.value = token;
        this.access_token = token;

        this.ready = false;
        this.loading = true;

        setTimeout(async () => {
          await this.init(true);
        }, 1000);
      },
      async logout() {
        await this.accountRemove(this.user.email);
      },
      async accountAdd(email, token) {
        const data = { access_token: token, valid: true };
        accountsStorage.value[email] = data;
        this.accounts[email] = data;
      },
      async accountRemove(email) {
        if (accountsStorage.value[email]) {
          delete accountsStorage.value[email];
        }
        if (this.accounts[email]) {
          delete this.accounts[email];
        }
        if (this.user.email==email) {
          tokenStorage.value = '';
          this.access_token = '';
          this.user = false;
        }
      },
      async accountSwitch(email) {
        // const acc = accountsStorage.value[ email ] || false;
        const acc = this.accounts[ email ] || false;
        if (!acc) return console.error(`no account found for ${email}`);

        const access_token = acc.access_token;
        if (!access_token) return console.error(`no token found`);

        await this.setToken(access_token);
      },
    },
  })();

  s.login = useAxios({
    method: 'post',
    url: 'api://auth/login',
    data: { email: '', password: '' },
    onSuccess: async ({ data }) => {
      await s.accountAdd(s.login.data.email, data.access_token);
      await s.setToken(data.access_token);
      s.login.data = { email: '', password: '' };
    },
  });

  s.access_token = tokenStorage.value;
  s.accounts = accountsStorage.value;
  setTimeout(() => { s.init(); }, Math.round(Math.random()*600));

  watch([ s.access_token ], ([ access_token_new ]) => {
    console.log(access_token_new);
  });

  return s;
};