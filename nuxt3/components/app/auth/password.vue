<template>
  <form @submit.prevent="app.login(credentials)">
    <v-text-field label="Informe seu e-mail" v-model="credentials.email" />

    <div class="d-flex align-center">
      <v-spacer />
      <v-btn class="ms-2" @click="app.login(credentials)" :loading="app.loading=='login'">Prosseguir</v-btn>
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