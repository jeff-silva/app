import { ref } from 'vue';
import axios from 'axios';
import { useStorage, useEventBus } from '@vueuse/core';

export default (params={}) => {

  params = {
    onLogin: () => {},
    onLogout: () => {},
    onRegister: () => {},
    ...params
  };

  const r = ref({
    init: false,
    loading: false,
    access_token: useStorage('access_token', ''),
    user: false,
    async load() {
      try {
        const { data } = await axios.post('api://auth/me');
        this.user = data;
      } catch(err) {}
    },
    async login(credentials) {
      this.loading = true;
      
      try {
        const { data } = await axios.post('api://auth/login', credentials);
        const state = useStorage('access_token', '');
        state.value = data.access_token;
        await this.load();
        await this.account.add(credentials.email, data.access_token);
        params.onLogin({ data });
      } catch(err) {}

      this.loading = false;
    },
    async logout() {
      const state = useStorage('access_token', '');
      state.value = '';
      this.account.remove(this.user.email);
      this.user = false;
      params.onLogout({
        access_token: this.access_token,
        email: this.user.email,
      });
      try {
        await axios.post('api://auth/logout');
      } catch(err) {}
    },
    async register(data={}) {
      try {
        await axios.post('api://auth/register', data);
        params.onRegister(data);
      } catch(err) {}
    },
    async password() {},
    account: {
      list: useStorage('accounts', []),
      async add(email, access_token) {
        if (this.list.filter(acc => acc.email==email).at(0) || false) return;
        const state = useStorage('accounts', []);
        state.value.push({ email, access_token });
        this.list.value = state.value;
      },
      async remove(email) {
        const state = useStorage('accounts', []);
        for(let i in this.list) {
          if (this.list[i].email != email) continue;
          this.list.splice(i, 1);
        }
      },
      async switch(email) {
        console.log(`switch to ${email}`);
      },
    },
  });

  r.value.load();
  return r;
};

// // import { useState } from '#app';
// // import { ref } from 'vue';
// import { defineStore } from 'pinia';
// import axios from 'axios';
// import useStorage from '@/composables/useStorage';
// import { useEventBus } from '@vueuse/core';

// export default function() {
//     const onInit = useEventBus('useApp:onInit');
//     const onLoad = useEventBus('useApp:onLoad');
//     const onLogin = useEventBus('useApp:onLogin');
//     const onLogout = useEventBus('useApp:onLogout');
//     const onSwitch = useEventBus('useApp:onSwitch');

//     const app =  defineStore({
//         id: 'app',
        
//         state: () => ({
//             init: false,
//             loading: false,
//             user: false,
//             error: false,
//             localStorage: useStorage('app', {
//                 access_token: '',
//                 accounts: [],
//             }),
//             settings: {},
//         }),
    
//         actions: {
//             async load(loadParams = {}) {
//                 if (loadParams.forced) {
//                     this.init = false;
//                 }

//                 if (this.init) {
//                     onInit.emit(true, false);
//                     return;
//                 }

//                 this.init = true;

//                 if (this.localStorage.access_token) {
//                     this.loading = 'load';
//                     try {
//                         const { data } = await axios.post('/api/auth/me');
//                         this.user = data.user;
//                         this.settings = data.settings;
//                     }
//                     catch(e) {
//                         await this.setAccessToken();
//                     }
//                     this.loading = false;
//                 }

//                 onInit.emit(true, false);
//                 onLoad.emit(true, false);
//             },
        
//             async login(credentials={email:'', password:''}) {
//                 this.loading = 'login';
//                 this.error = false;
                
//                 try {
//                     const { data: loginData } = await axios.post('/api/auth/login', credentials);
//                     await this.setAccessToken(loginData.access_token);
//                     await this.accountAdd(credentials.email, loginData.access_token);
//                     onLogin.emit(loginData, false);
//                 }
//                 catch(err) {
//                     onLogin.emit(false, err);
//                     this.error = err.response.data;
//                 }

//                 this.loading = false;
//                 await this.load({ forced: true });
//             },
        
//             async logout() {
//                 try {
//                     this.loading = 'logout';
//                     const { data: logoutData } = await axios.post('/api/auth/logout');
//                     await this.accountRemove(this.user.email);
//                     await this.setAccessToken();
//                     this.user = false;
//                     this.loading = false;
//                     onLogout.emit(logoutData, false);
//                 }
//                 catch(err) {
//                     onLogout.emit(false, err);
//                 }
//             },

//             async setAccessToken(access_token=false) {
//                 this.localStorage.access_token = access_token || '';
//             },
            
//             getAccessToken() {
//                 return this.localStorage.access_token || '';
//             },
        
//             async accountAdd(email, access_token) {
//                 await this.accountRemove(email);
//                 this.localStorage.accounts.push({ email, access_token });
//             },
        
//             async accountSwitch(email) {
//                 this.localStorage.accounts.forEach(async (acc, index) => {
//                     if (acc.email!=email) return;
//                     await this.setAccessToken(acc.access_token);
//                     await this.load({ forced: true });
//                     onSwitch.emit(acc, false);
//                 });
//             },
        
//             async accountRemove(email) {
//                 this.localStorage.accounts.forEach(async (acc, index) => {
//                     if (email != acc.email) return;
//                     this.localStorage.accounts.splice(index, 1);
//                     if (acc.access_token==this.getAccessToken()) {
//                         await this.setAccessToken(false);
//                     }
//                 });
//             },
//         },
//     })();

//     app.load();
//     return app;
// };