<template>
  <form @submit.prevent="app.login(credentials)">
    <v-text-field label="E-mail" v-model="credentials.email" />
    <v-text-field label="Senha" v-model="credentials.password" type="password" />

    <div class="d-flex align-center">
      <v-spacer />
      <v-btn class="ms-2" @click="app.login(credentials)" :loading="app.loading=='login'">Login</v-btn>
    </div>
  </form>
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