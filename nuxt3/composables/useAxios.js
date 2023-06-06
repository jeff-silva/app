import { ref } from 'vue';
import axios from 'axios';
import _ from 'lodash';

import useValidate from '@/composables/useValidate';

export default (params={}) => {
  params = _.merge({
    autoSubmit: false,
    onSuccess: () => {},
    onError: () => {},
    url: false,
    method: false,
    params: {},
    data: {},
  }, params);

  let axiosParams = {
    url: false,
    method: false,
    params: {},
    data: {},
  };

  for(let attr in axiosParams) {
    axiosParams[ attr ] = params[ attr ] || false;
  }

  const r = ref({
    loading: false,
    params: axiosParams.params,
    data: axiosParams.data,
    error: useValidate(),
    success: false,
    async submit() {

      if (this.loading) {
        clearTimeout(this.loading);
        this.loading = false;
      }

      this.success = false;
      this.error.clear();
      this.loading = setTimeout(async () => {
        try {
          axiosParams.params = this.params;
          axiosParams.data = this.data;
          console.log(axiosParams.data);
          const { data } = await axios(axiosParams);
          params.onSuccess({ data });
          this.success = data;
        } catch(err) {
          this.error.setData(err.response ? err.response.data : { message: err.message });
        }
        this.loading = false;
      }, 1000);
    },
  });

  if (params.autoSubmit){
    r.value.submit();
  }

  return r;
};
