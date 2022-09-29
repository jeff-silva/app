// import { useState } from '#app';
import { ref } from 'vue';
import axios from 'axios';
// import { useAppStore } from '@/stores/app';


export default function(params={}) {

    let req = ref({
        loading: false,
        user: false,
        access_token: (localStorage.getItem('useApp.access_token') || ''),
        accounts: JSON.parse(localStorage.getItem('useApp.accounts') || '[]'),
        settings: {},
        login: async (credentials={email:'', password:''}) => {
            req.value.accountRemove(credentials.email);
            req.value.loading = true;

            const { data } = await axios.get('https://randomuser.me/api/?results=1');
            const user = {
                id: data.results[0].login.uuid,
                name: `${data.results[0].name.first} ${data.results[0].name.last}`,
                email: data.results[0].email,
                photo: data.results[0].picture.medium,
            };

            req.value.user = user;
            req.value.access_token = user.id;
            req.value.loading = false;
            req.value.accounts.push({
                email: user.email,
                token: user.id,
            });
            
            req.value.storeData();
        },
        logout: () => {
            req.value.accountRemove(req.value.user.email);
            req.value.user = false;
            req.value.storeData();
        },
        accountSwitch: (email) => {
            req.value.accounts.forEach((acc, index) => {
                if (acc.email==email) {
                    req.value.user = {
                        ...req.value.user,
                        id: acc.token,
                        email: acc.email,
                    };
                    req.value.access_token = acc.token;
                }
            });
            req.value.storeData();
        },
        accountRemove(email) {
            req.value.accounts.forEach((acc, index) => {
                if (acc.email==email) {
                    req.value.accounts.splice(index, 1);
                }
                if (req.value.user.email==email) {
                    req.value.user = false;
                    req.value.access_token = '';
                }
            });

            req.value.storeData();
        },
        storeData() {
            localStorage.setItem('useApp.access_token', req.value.access_token);
            localStorage.setItem('useApp.accounts', JSON.stringify(req.value.accounts));
        },
    });

    return req;
};