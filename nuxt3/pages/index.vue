<template>
  <nuxt-layout name="admin">
    <v-row>
      <v-col cols="12" md="4">
        <app-auth-login />
      </v-col>

      <v-col cols="12" md="4">
        <app-auth-register />
      </v-col>

      <v-col cols="12" md="4">
        <app-auth-password />
      </v-col>
    </v-row>
  </nuxt-layout>
</template>

<script setup>
  import useApp from '@/composables/useApp';
  import { ref } from 'vue';

  const app = useApp();
</script>