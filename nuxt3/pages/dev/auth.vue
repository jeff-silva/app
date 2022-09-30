<template>
  <div>
    <v-card v-if="app.user">
      Bem vindo {{ app.user.email }}
    </v-card>
    <v-text-field label="E-mail" v-model="credentials.email" />
    <v-text-field label="Senha" v-model="credentials.password" type="password" />
    <v-btn @click="app.login(credentials)">Login</v-btn>
    <v-divider />

    <v-btn @click="app.logout()">Logout</v-btn>
    <v-divider />

    <v-card v-if="app.user">
      Bem vindo {{ app.user.email }}
    </v-card>
    <v-divider />

    <v-card>
      <v-list>
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
        email: '',
        password: '',
      },
    };
  },
};
</script>