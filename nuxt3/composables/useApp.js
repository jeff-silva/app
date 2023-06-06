import { ref } from 'vue';
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
      },
      async logout() {},
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
        const { access_token } = this.accounts[ email ] || {};
        if (!access_token) return;
        await this.setToken(access_token);
        await this.init(true);
      },
    },
  })();

  s.login = useAxios({
    method: 'post',
    url: 'api://auth/login',
    data: { email: '', password: '' },
    onSuccess: async ({ data }) => {
      await s.setToken(data.access_token);
      await s.accountAdd(s.login.data.email, data.access_token);
      await s.init(true);
      s.login.data = { email: '', password: '' };
    },
  });

  s.access_token = tokenStorage.value;
  s.accounts = accountsStorage.value;
  setTimeout(() => { s.init(); }, Math.round(Math.random()*600));

  return s;
};



// export default (params={}) => {
//   // params = _.merge({
//   //   onReady: () => {},
//   //   onLogin: () => {},
//   //   onLogout: () => {},
//   //   onRegister: () => {},
//   //   login: {
//   //     validation: {},
//   //   },
//   //   register: {
//   //     validation: {},
//   //   },
//   //   password: {
//   //     validation: {},
//   //   },
//   // }, params);

//   const r = ref({
//     ready: false,

//     auth: defineStore('appAuth', {
//       state: () => ({
//         token: '',
//         user: false,
//       }),
//       actions: {
//         setUser(data) {
//           this.user = data;
//         },
//         setToken(access_token) {
//           const state = useStorage('access_token', '');
//           state.value = access_token;
//           this.token = access_token;
//         },
//       },
//     })(),

//     storage: defineStore('appStorage', {
//       state: () => ({
//         settings: {},
//         permissions: {},
//       }),
//       actions: {
//         setData(key, value) {
//           this[key] = value;
//         },
//       },
//     })(),

//     async init(force=false) {
//       if (this.ready && force==false) return;
//       return useAxios({
//         method: 'get',
//         url: 'api://app',
//         autoSubmit: true,
//         onSuccess: ({ data }) => {
//           this.ready = true;
//           this.storage.setData('settings', data.settings);
//           this.storage.setData('permissions', data.permissions);
//         },
//       });

//       // axios.value.submit();
      
//       // this.auth.setToken(useStorage('access_token', '').value);
//       // if (!forced && this.auth.user) return;
//       // try {
//       //   const { data } = await axios.post('api://auth/me');
//       //   this.auth.setUser(data);
//       // } catch(err) {
//       //   this.account.list.forEach((acc, accIndex) => {
//       //     if (this.auth.token!=acc.access_token) return;
//       //     acc.valid = false;
//       //     // this.account.list.splice(accIndex, 1);
//       //   });
//       // }
//     },

//     login: useAxios({
//       method: 'post',
//       url: 'api://auth/login',
//       params: { email: '', password: '' },
//     }),
    
//     logout: useAxios({
//       method: 'post',
//       url: 'api://auth/logout',
//     }),

//     register: useAxios({
//       method: 'post',
//       url: 'api://auth/register',
//       params: { name: '', email: '', password: '', password_confirmation: '' },
//     }),
    
//     password: useAxios({
//       method: 'post',
//       url: 'api://auth/register',
//       params: { code: '', email: '', password: '' },
//     }),

//     // login: {
//     //   loading: false,
//     //   params: { email: '', password: '' },
//     //   resp: {},
//     //   error: {},
//     //   async submit() {
//     //     this.loading = true;
//     //     this.error.clear();
        
//     //     try {
//     //       const { data } = await axios.post('api://auth/login', this.params);
//     //       this.resp = data;
//     //       r.value.auth.setToken(data.access_token);
//     //       await r.value.account.add(this.params.email, data.access_token);
//     //       setTimeout(async () => {
//     //         await r.value.load(true);
//     //         params.onLogin({ data });
//     //       }, 100);
//     //       this.params = {};
//     //     } catch(err) {
//     //       this.error.setMessage(err.response.data.message);
//     //       this.error.setFields(err.response.data.fields);
//     //     }
  
//     //     this.loading = false;
//     //   },
//     // },

//     // async logout() {
//     //   this.account.remove(this.auth.user.email);
//     //   params.onLogout({
//     //     access_token: this.auth.token,
//     //     email: this.auth.user.email,
//     //   });
//     //   r.value.auth.setUser(false);
//     //   r.value.auth.setToken(false);
//     //   try {
//     //     await axios.post('api://auth/logout');
//     //   } catch(err) {}
//     // },

//     // register: {
//     //   loading: false,
//     //   error: {},
//     //   params: { name: '', email: '', password: '', password_confirmation: '' },
//     //   async submit() {
//     //     this.loading = true;
//     //     this.error.clear();
//     //     try {
//     //       const { data } = await axios.post('api://auth/register', this.params);
//     //       this.params = {};
//     //       params.onRegister(data);
//     //     } catch(err) {
//     //       this.error.setMessage(err.response.data.message);
//     //       this.error.setFields(err.response.data.fields);
//     //     }
//     //     this.loading = false;
//     //   },
//     // },

//     // password: {
//     //   loading: false,
//     //   params: {
//     //     email: '',
//     //     code: '',
//     //     password: '',
//     //   },
//     //   resp: false,
//     //   error: {},
//     //   async submit() {
//     //     this.loading = true;
//     //     this.error.clear();
//     //     try {
//     //       const { data } = await axios.post('api://auth/password', this.params);
//     //       this.resp = data;
//     //       if (data.password_changed) {
//     //         this.params = {};
//     //       }
//     //     } catch(err) {
//     //       this.error.setMessage(err.response.data.message);
//     //       this.error.setFields(err.response.data.fields);
//     //     }
//     //     this.loading = false;
//     //   },
//     // },

//     account: {
//       list: useStorage('accounts', []),
//       async add(email, access_token) {
//         const state = useStorage('accounts', []);
//         let acc = this.list.filter(acc => acc.email==email).at(0) || false;

//         if (acc) {
//           acc.valid = true;
//         } else {
//           acc = { email, access_token, valid: true };
//           state.value.push(acc);
//         }

//         this.list.value = state.value;
//       },
//       async remove(email) {
//         const state = useStorage('accounts', []);
//         for(let i in this.list) {
//           if (this.list[i].email != email) continue;
//           this.list.splice(i, 1);
//         }
//       },
//       async switch(email) {
//         this.list.forEach((acc) => {
//           if (acc.email!=email) return;
//           r.value.auth.setToken(acc.access_token);
//           setTimeout(() => { r.value.load(true); }, 100);
//         });
//       },
//     },
//   });

//   // r.value.login.error = useValidate(r.value.login.params, params.login.validation);
//   // r.value.register.error = useValidate(r.value.register.params, params.register.validation);
//   // r.value.password.error = useValidate(r.value.password.params, params.password.validation);

//   r.value.init();
//   return r;
// };











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