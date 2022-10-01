<template>
  <div>
    <v-card v-if="app.user">
      Bem vindo {{ app.user.email }}
    </v-card>
    <v-text-field label="E-mail" v-model="credentials.email" />
    <v-text-field label="Senha" v-model="credentials.password" type="password" />

    <div class="d-flex">
      <v-spacer />
      <v-btn class="ms-2" @click="app.login(credentials)" :loading="app.loading=='login'">Login</v-btn>
      <v-btn class="ms-2" @click="app.logout()" :loading="app.loading=='logout'">Logout</v-btn>
    </div>

    <v-card v-if="app.user">
      Bem vindo {{ app.user.email }}
    </v-card>

    <v-card>
      <v-list>
        <v-list-item-subtitle>
          Aaa
        </v-list-item-subtitle>
        <v-list-item v-if="app.accounts.length==0">
          Nenhum login
        </v-list-item>
        <v-list-item v-for="acc in app.accounts" :key="acc.id" color="success">
          <div>
            {{ acc.email }}
          </div>

          <template v-slot:append>
            <v-btn
              :disabled="acc.email==(app.user? app.user.email: '')"
              @click="app.accountSwitch(acc.email)"
              size="small"
              class="ms-2"
              icon="mdi-account-switch"
              :loading="app.loading=='load'"
            />
            <v-btn
              @click="app.accountRemove(acc.email)"
              size="small"
              class="ms-2"
              icon="mdi-logout"
            />
          </template>
        </v-list-item>
      </v-list>
    </v-card>

    <pre>$data: {{ $data }}</pre>
  </div>
</template>

<script>
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