// import { useState } from '#app';
import { ref } from 'vue';
import axios from 'axios';
// import { useAppStore } from '@/stores/app';


export default function(params={}) {

    let req = ref({
        loading: false,
        user: false,
        access_token: '',
        accounts: [],
        settings: {},
    });

    req.value.login = async () => {
        req.value.userLogging = true;

        const { data } = await axios.get('https://randomuser.me/api/?results=1');
        console.log('login', data);
    };

    req.value.logout = () => {
        console.log('logout');
    };

    req.value.switchTo = (email) => {
        console.log('switchTo', email);
    };

    return req;
};