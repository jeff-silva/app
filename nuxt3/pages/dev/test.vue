<template>
  <div>
    <v-btn @click="axios.submit()">submit()</v-btn>
    <!-- <pre>axios: {{ axios }}</pre> -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      axios: useAxios({
        method: 'get',
        url: '/api/app/test',
        submit: true,
      }),
    };
  },
};
</script>