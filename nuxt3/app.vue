<template>
  <v-defaults-provider
    :defaults="{
      VTextField: { variant: 'outlined' },
    }"
  >
  <!-- outlined, plain, underlined, filled, solo, solo-inverted, solo-filled -->
    <!-- <NuxtWelcome /> -->
    <v-btn color="primary" icon="mdi-abacus"></v-btn>

    <v-row>
      <v-col cols="4">
        <form @submit.prevent="app.login(credentials)">
          <v-card title="Login">
            <v-divider />
            <v-card-text>
              <v-text-field label="E-mail" v-model="credentials.email" />
              <v-text-field label="Password" type="password" v-model="credentials.password" />
            </v-card-text>
            <v-divider />
            <v-card-actions>
              <v-spacer />
              <v-btn type="submit" :loading="app.loading">Login</v-btn>
            </v-card-actions>
          </v-card>
        </form>
      </v-col>

      <v-col cols="4">
        <form @submit.prevent="app.login(credentials)">
          <v-card title="Register">
            <v-divider />
            <v-card-text>
              <v-text-field label="Name" />
              <v-text-field label="E-mail" />
              <v-text-field label="Password" type="password" />
              <v-text-field label="Repeat password" type="password" />
            </v-card-text>
            <v-divider />
            <v-card-actions>
              <v-spacer />
              <v-btn type="submit" :loading="app.loading">Login</v-btn>
            </v-card-actions>
          </v-card>
        </form>
      </v-col>

      <v-col cols="4">
        <form @submit.prevent="app.login(credentials)">
          <v-card title="E-mail recover">
            <v-divider />
            <v-card-text>
              <v-text-field label="E-mail" />
            </v-card-text>
            <v-divider />
            <v-card-actions>
              <v-spacer />
              <v-btn type="submit" :loading="app.loading">Login</v-btn>
            </v-card-actions>
          </v-card>
        </form>
      </v-col>
    </v-row>

    <pre>{{ { $pwa, app, credentials } }}</pre>
  </v-defaults-provider>
</template>

<script setup>
  import useApp from '@/composables/useApp';
  import { ref } from 'vue';

  const app = useApp();
  const credentials = ref({ email: 'main@grr.la', password: 'main@grr.la' });
</script>