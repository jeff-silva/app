<template>
  <v-app>
    <div>Hello World</div>
    <v-btn>Login</v-btn>
    <pre>$data: {{ $data }}</pre>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      app: useApp(),
    };
  },
};
</script>