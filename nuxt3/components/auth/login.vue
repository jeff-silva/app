<template>
  <form @submit.prevent="app.login(credentials)">
    <v-card title="Login">
      <v-divider />
      <v-card-text>
        <v-text-field label="E-mail" v-model="credentials.email" />
        <v-text-field label="Password" type="password" v-model="credentials.password" />
      </v-card-text>

      <template v-if="app.account.list.length>0">
        <v-divider />
        <v-list>
          <v-list-item
            v-for="acc in app.account.list"
          >
            <div>{{ acc.email }}</div>
            <template #append>
              <v-btn v-if="app.user && app.user.email!=acc.email" icon="mdi-login" flat size="x-small" @click="app.account.switch(acc.email)"></v-btn>
              <v-btn v-if="app.user && app.user.email==acc.email" icon="mdi-logout" flat size="x-small" @click="app.account.remove(acc.email)"></v-btn>
            </template>
          </v-list-item>
        </v-list>
      </template>

      <v-divider />
      <v-card-actions>
        <v-btn type="submit" @click="app.logout()" v-if="app.access_token">Logout</v-btn>
        <v-spacer />
        <v-btn type="submit" :loading="app.loading">Login</v-btn>
      </v-card-actions>
    </v-card>
  </form>
</template>

<script setup>
  import useApp from '@/composables/useApp';
  const app = useApp();

  const credentials = ref({
    email: 'main@grr.la',
    password: 'main@grr.la',
  });
</script>