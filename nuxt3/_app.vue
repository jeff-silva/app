<template>
  <v-defaults-provider
    :defaults="{
      VTextField: { variant: 'outlined' },
    }"
  >
    <!-- outlined, plain, underlined, filled, solo, solo-inverted, solo-filled -->
    <!-- <NuxtWelcome /> -->
    <!-- <v-btn color="primary" icon="mdi-abacus"></v-btn> -->

    <v-container>
      <v-row>
        <v-col cols="12" md="4">
          <auth-login />
        </v-col>
  
        <v-col cols="12" md="4">
          <auth-register />
        </v-col>
  
        <v-col cols="12" md="4">
          <auth-password />
        </v-col>
      </v-row>
    </v-container>

    <pre>{{ app }}</pre>
  </v-defaults-provider>
</template>

<script setup>
  import useApp from '@/composables/useApp';
  import { ref } from 'vue';

  const app = useApp();
</script>