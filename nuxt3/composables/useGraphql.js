// import { useState } from '#app';
import { watch, reactive } from 'vue';
import axios from 'axios';


export default function(params = {}) {

    params = {
        autoSubmit: false,
        data: {},
        query: (query) => '',
        onSuccess: (resp) => {},
        onError: (resp) => {},
        ...params
    };

    let data = reactive({
        loading: false,
        data: params.data,
        query: '',
        resp: {},
        errors: {},
        async submit() {
            this.loading = true;
            try {
                const resp = await axios.post('/graphql', {
                    query: this.query,
                });
                this.resp = resp.data.data;
                this.errors = resp.data.errors || [];
                this.errors.length?
                    params.onError(resp):
                    params.onSuccess(resp);
            }
            catch(err) {
                this.error = err.response.data;
                params.onError(err.response);
            }
            this.loading = false;
        },
    });

    data.query = params.query(data.data);

    watch(data.data, (value, preval) => {
        data.query = params.query(value);
    });

    if (params.autoSubmit) {
        data.submit();
    }

    return data;
};