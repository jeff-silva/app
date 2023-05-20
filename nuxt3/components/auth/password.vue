<template>
  <form @submit.prevent="app.password.submit()">
    <v-card title="E-mail recover">
      <v-divider />
      <v-card-text>
        <v-text-field label="E-mail" />
      </v-card-text>
      <v-divider />
      <v-card-actions>
        <v-spacer />
        <v-btn type="submit" :loading="app.loading">Send</v-btn>
      </v-card-actions>
    </v-card>
  </form>
</template>

<script setup>
  import useApp from '@/composables/useApp';
  const app = useApp();
</script>