<template>
  <div v-if="app.user">
    <slot />
  </div>
  <div v-else>
    <v-text-field label="E-mail" v-model="credentials.email" />
    <v-text-field label="Senha" v-model="credentials.password" type="password" />
    <v-btn @click="app.login(credentials)">Login</v-btn>
    <pre>$data: {{ $data }}</pre>
  </div>
</template>

<script>
export default {
  data() {
    return {
      app: useApp(),
      credentials: {
        email: '',
        password: '',
      },
    };
  },
};
</script>