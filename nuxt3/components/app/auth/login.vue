<template>
  <form @submit.prevent="app.login(credentials)">
    <v-alert type="error" v-model="app.error" closable>{{ app.error.message }}</v-alert>
    <v-spacer class="my-3" />
    <v-text-field label="E-mail" v-model="credentials.email" />
    <v-text-field label="Senha" v-model="credentials.password" type="password" />

    <div class="d-flex align-center">
      <v-spacer />
      <v-btn class="ms-2" type="submit" :loading="app.loading=='login'">Login</v-btn>
    </div>
  </form>
</template>

<script>
import { useEventBus } from '@vueuse/core';

export default {
  props: {
    redirect: {
      type: [Boolean, String],
      default: false,
    },
  },

  data() {
    return {
      app: useApp(),
      credentials: this.credentialsDefault(),
    };
  },

  methods: {
    credentialsDefault() {
      return {
        email: '',
        password: '',
      };
    },
  },

  mounted() {
    useEventBus('useApp:onLogin').on((data, err) => {
      this.credentials = this.credentialsDefault();
      this.$emit('success', data);

      if (this.redirect) {
        this.$router.push(this.redirect);
      }
    });
  },
};
</script>