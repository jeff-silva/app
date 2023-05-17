// import { useState } from '#app';
import { ref, watch, reactive } from 'vue';


export default function(name, def={}) {
    let data;

    try {
        data = window.localStorage.getItem(name) || def;
        data = JSON.parse(data);
    }
    catch(err) {
        data = def;
    }

    let refData = reactive(data);

    watch(refData, (value, preval) => {
        window.localStorage.setItem(name, JSON.stringify(value));
    });

    return refData;
};