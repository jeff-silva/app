<template>
  <div>
    <pre>axios: {{ axios }}</pre>
  </div>
</template>

<script>
export default {
  data() {
    return {
      axios: useAxios({
        method: 'get',
        url: 'http://localhost:443/api/test',
        submit: true,
      }),
    };
  },
};
</script>