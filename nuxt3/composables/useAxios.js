// import { useState } from '#app';
import { ref } from 'vue';
import axios from 'axios';


export default function(compParams={}) {

    compParams = {
        method: 'get',
        url: '/api',
        params: {},
        data: {},
        resp: {},
        err: {message:false, fields:{}},
        submit: () => {},
        onSubmit: (resp) => {},
        onSuccess: (resp) => {},
        onError: (resp) => {},
        onResponse: (resp) => {},
        ...compParams
    };

    let req = ref({
        loading: false,
        status: false,
        params: JSON.parse(JSON.stringify(compParams.params)),
        data: JSON.parse(JSON.stringify(compParams.data)),
        resp: compParams.resp,
        err: compParams.err,
    });

    const axiosCancelToken = axios.CancelToken;
    let axiosCancelSource;

    req.value.submit = () => {
        return new Promise((resolve, reject) => {
            req.value.cancel();
    
            req.value.loading = true;
            req.value.status = false;
            compParams.onSubmit(req.value);
    
            axiosCancelSource = axiosCancelToken.source();

            let formData = new FormData();
            for(let i in compParams.data) {
                formData.append(i, compParams.data[i]);
            }
    
            axios({
                method: compParams.method,
                url: compParams.url,
                params: compParams.params,
                data: formData,
                headers: {'Content-Type': 'multipart/form-data'},
                cancelToken: axiosCancelSource.token,
            })
            .then(resp => {
                req.value.loading = false;
                req.value.status = resp.status;
                req.value.resp = resp.data;
                compParams.onSuccess(resp);
                compParams.onResponse(resp);
                axiosCancelSource = false;
                resolve(resp);
            })
            .catch(err => {
                req.value.loading = false;
                compParams.onError(err);
                compParams.onResponse(err.response);
                reject(err);
            });
        });
    };

    req.value.cancel = (message=null) => {
        if (!axiosCancelSource) return;
        axiosCancelSource.cancel(message);
        req.value.loading = false;
        req.value.status = false;
    };

    req.value.reset = () => {
        console.log(compParams);
        req.value.params = JSON.parse(JSON.stringify(compParams.params));
        req.value.data = JSON.parse(JSON.stringify(compParams.data));
    };

    req.value.clear = () => {
        req.value.params = {};
        req.value.data = {};
    };

    // if (compParams.submit) {
    //     req.value.submit();
    // }

    return req;
};