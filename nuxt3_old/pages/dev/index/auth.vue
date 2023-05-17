<template>
  <div>
    <v-card>
      <template v-if="app.user">
        <v-card-text>
          <div class="d-flex align-center">
            <div class="flex-grow-1">
              Bem vindo(a) {{ app.user.email }}
            </div>
            <v-btn size="small" flat icon="mdi-logout" @click="app.logout()"></v-btn>
          </div>
        </v-card-text>
        <v-divider />
      </template>

      <template v-if="app.localStorage.accounts.length">
        <v-card-title>Switch account</v-card-title>
        <v-card-text>
          <app-auth-switch />
        </v-card-text>
        <v-divider />
      </template>
      
      <v-card-title>Login</v-card-title>
      <v-card-text>
        <app-auth-login />
      </v-card-text>
      <v-divider />

      <v-card-title>Cadastro</v-card-title>
      <v-card-text>
        <app-auth-register />
      </v-card-text>
      <v-divider />

      <v-card-title>Esqueci minha senha</v-card-title>
      <v-card-text>
        <app-auth-password />
      </v-card-text>
      <v-divider />
    </v-card>

    <a href="">refresh</a>
    <app-dd v-model="app" />
  </div>
</template>

<script>
import useApp from '@/composables/useApp';

export default {
  data() {
    return {
      app: useApp(),
      credentials: {
        email: 'root@grr.la',
        password: '321321',
      },
    };
  },
};
</script>