<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="3">
        <app-auth-login />
      </v-col>
      <v-col cols="12" md="3">
        <app-auth-switch />
      </v-col>
      <v-col cols="12" md="3">
        <app-auth-register />
      </v-col>
      <v-col cols="12" md="3">
        <app-auth-password />
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
  import useApp from '@/composables/useApp';
  const app = useApp();
</script>