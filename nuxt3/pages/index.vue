<template>
  <div>
    <div>Hello World</div>
    <v-btn @click="app.login()">Login</v-btn>
    <v-btn @click="app.logout()">Logout</v-btn>
    <v-list>
      <v-list-item v-for="acc in app.accounts" :key="acc.id">
        {{ acc.email }}

        <template v-slot:append>
          <v-btn @click="app.accountSwitch(acc.email)" size="small">switch</v-btn>
          <v-btn @click="app.accountRemove(acc.email)" size="small">remove</v-btn>
        </template>
      </v-list-item>
    </v-list>
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