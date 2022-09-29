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
        url: 'api://uniqid',
        submit: true,
      }),
    };
  },
};
</script>