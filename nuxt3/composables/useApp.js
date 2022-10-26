// import { useState } from '#app';
// import { ref } from 'vue';
import { defineStore } from 'pinia';
import axios from 'axios';
import useStorage from '@/composables/useStorage';
import { useEventBus } from '@vueuse/core';

export default function() {
    const onInit = useEventBus('useApp:onInit');
    const onLoad = useEventBus('useApp:onLoad');
    const onLogin = useEventBus('useApp:onLogin');
    const onLogout = useEventBus('useApp:onLogout');
    const onSwitch = useEventBus('useApp:onSwitch');

    const app =  defineStore({
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
            async load(loadParams = {}) {
                if (loadParams.forced) {
                    this.init = false;
                }

                if (this.init) {
                    onInit.emit(true, false);
                    return;
                }

                this.init = true;

                if (this.localStorage.access_token) {
                    this.loading = 'load';
                    try {
                        const { data } = await axios.post('/api/auth/me');
                        this.user = data.user;
                        this.settings = data.settings;
                    }
                    catch(e) {
                        await this.setAccessToken();
                    }
                    this.loading = false;
                }

                onInit.emit(true, false);
                onLoad.emit(true, false);
            },
        
            async login(credentials={email:'', password:''}) {
                this.loading = 'login';
                
                try {
                    const { data: loginData } = await axios.post('/api/auth/login', credentials);
                    await this.setAccessToken(loginData.access_token);
                    await this.accountAdd(credentials.email, loginData.access_token);
                    onLogin.emit(loginData, false);
                }
                catch(err) {
                    onLogin.emit(false, err);
                }

                this.loading = false;
                await this.load({ forced: true });
            },
        
            async logout() {
                try {
                    this.loading = 'logout';
                    const { data: logoutData } = await axios.post('/api/auth/logout');
                    await this.accountRemove(this.user.email);
                    await this.setAccessToken();
                    this.user = false;
                    this.loading = false;
                    onLogout.emit(logoutData, false);
                }
                catch(err) {
                    onLogout.emit(false, err);
                }
            },

            async setAccessToken(access_token=false) {
                this.localStorage.access_token = access_token || '';
            },
            
            getAccessToken() {
                return this.localStorage.access_token || '';
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
                    onSwitch.emit(acc, false);
                });
            },
        
            async accountRemove(email) {
                this.localStorage.accounts.forEach(async (acc, index) => {
                    if (email != acc.email) return;
                    this.localStorage.accounts.splice(index, 1);
                    if (acc.access_token==this.getAccessToken()) {
                        await this.setAccessToken(false);
                    }
                });
            },
        },
    })();

    app.load();

    return app;
};