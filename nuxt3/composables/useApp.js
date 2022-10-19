// import { useState } from '#app';
// import { ref } from 'vue';
import { defineStore } from 'pinia';
import axios from 'axios';
import useStorage from '@/composables/useStorage';

export default function() {
    return defineStore({
        id: 'app',
        
        state: () => ({
            init: false,
            loading: false,
            user: false,
            localStorage: useStorage('app', {
                access_token: '',
                accounts: [],
            }),
            settings: {},
        }),
    
        actions: {
            async load(params = {}) {
                if (params.forced) {
                    this.init = false;
                }

                if (this.init) return;
                this.init = true;

        
                this.loading = 'load';
                try {
                    const { data: user } = await axios.post('/api/auth/me');
                    this.user = user;
                }
                catch(e) {
                    await this.setAccessToken();
                }
                this.loading = false;
            },
        
            async login(credentials={email:'', password:''}) {
                this.loading = 'login';
                
                try {
                    const { data: loginData } = await axios.post('/api/auth/login', credentials);
                    await this.setAccessToken(loginData.access_token);
                    await this.accountAdd(credentials.email, loginData.access_token);
                }
                catch(err) {
                    console.log(err);
                }

                this.loading = false;
                await this.load({ forced: true });
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

            async setAccessToken(access_token='') {
                this.localStorage.access_token = access_token;
            },
        
            async accountAdd(email, access_token) {
                await this.accountRemove(email);
                this.localStorage.accounts.push({ email, access_token });
            },
        
            async accountSwitch(email) {
                this.localStorage.accounts.forEach(async (acc, index) => {
                    if (acc.email!=email) return;
                    await this.setAccessToken(acc.access_token);
                    await this.load({ forced: true });
                });
            },
        
            async accountRemove(email) {
                this.localStorage.accounts.forEach(async (acc, index) => {
                    if (acc.email!=email) return;
                    this.localStorage.accounts.splice(index, 1);
                    if (this.user.email==email) {
                        this.user = false;
                        await this.setAccessToken();
                    }
                });
            },
        },
    })();
};