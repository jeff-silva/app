<template>
  <nuxt-layout name="auth">
    <app-auth-login @success="redirect($event)" />
    <v-spacer class="my-2" />
    
    <app-auth-switch
      @success="redirect($event)"
      v-if="app.localStorage.accounts.length"
    />
  </nuxt-layout>
</template>

<script>
import { useEventBus } from '@vueuse/core';

export default {
  data() {
    return {
      app: useApp(),
    };
  },
  methods: {
    redirect(loginData) {
      let redirectUrl = this.$route.query.redirect || '/admin';

      if (redirectUrl=='back') {
        return this.$router.go(-1);
      }

      this.$router.push(redirectUrl);
    },
  },
};
</script>