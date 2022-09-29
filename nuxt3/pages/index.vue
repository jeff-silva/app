<template>
  <div>
    <div>Hello World</div>
    <v-divider />
    <v-btn @click="app.login()">Login</v-btn>
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
    };
  },
};
</script>