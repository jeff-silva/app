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
        submit: false,
        onResponse: (resp) => resp,
        ...compParams
    };

    let req = ref({
        loading: false,
        status: false,
        params: compParams.params,
        data: compParams.data,
        resp: compParams.resp,
        err: compParams.err,
    });

    const axiosCancelToken = axios.CancelToken;
    let axiosCancelSource;

    let submitTimeout = false;
    req.value.submit = (submitParams={}) => {
        return new Promise((resolve, reject) => {
            submitParams = {
                debounce: 10,
                ...submitParams
            };

            req.value.cancel();
    
            req.value.loading = true;
            req.value.status = false;
            
            axiosCancelSource = axiosCancelToken.source();

            if (submitTimeout) {
                clearTimeout(submitTimeout);
            }

            submitTimeout = setTimeout(() => {
                let data = JSON.parse(JSON.stringify(req.value.data));
                for(let i in data) {
                    if (typeof data[i]=='object') {
                        data[i] = JSON.stringify(data[i]);
                    }
                }

                axios({
                    method: compParams.method,
                    url: compParams.url,
                    params: req.value.params,
                    data,
                    cancelToken: axiosCancelSource.token,
                })
                .then(resp => {
                    req.value.loading = false;
                    req.value.status = resp.status;
                    req.value.resp = compParams.onResponse(resp.data);
                    axiosCancelSource = false;
                    resolve(resp);
                })
                .catch(err => {
                    req.value.loading = false;
                    reject(err);
                });
            }, submitParams.debounce);

        });
    };

    req.value.cancel = (message=null) => {
        if (!axiosCancelSource) return;
        axiosCancelSource.cancel(message);
        req.value.loading = false;
        req.value.status = false;
    };

    const defaultParams = JSON.parse(JSON.stringify(compParams.params));
    const defaultData = JSON.parse(JSON.stringify(compParams.data));
    req.value.reset = () => {
        req.value.params = defaultParams;
        req.value.data = defaultData;
    };

    req.value.clear = () => {
        req.value.params = {};
        req.value.data = {};
    };

    req.value.fillData = (data={}) => {
        req.value.data = data;
    };

    req.value.fillParam = (data={}) => {
        req.value.param = data;
    };

    if (compParams.submit) {
        req.value.submit();
    }

    return req;
}