import { ref } from 'vue';
import axios from 'axios';
import _ from 'lodash';

import useValidate from '@/composables/useValidate';

export default (params={}) => {
  params = _.merge({
    autoSubmit: false,
    onBeforeRequest: () => {},
    onSuccess: () => {},
    onError: () => {},
    url: false,
    method: false,
    params: {},
    data: {},
  }, params);

  const _call = (data) => {
    if (typeof data=='function') return data.call(this);
    return data;
  };

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
    params: _call(axiosParams.params),
    data: _call(axiosParams.data),
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
          axiosParams.params = _call(this.params);
          axiosParams.data = _call(this.data);
          params.onBeforeRequest(axiosParams);
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
