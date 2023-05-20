<template>
  <form @submit.prevent="app.login.submit()">
    <v-card title="Login">
      <v-divider />

      <v-alert
        type="error"
        rounded="0"
        v-if="app.login.error.message"
      >
        {{ app.login.error.message }}
      </v-alert>

      <v-alert
        type="success"
        rounded="0"
        v-if="app.login.resp.access_token"
      >
        Logged successfully
      </v-alert>

      <v-alert rounded="0" class="mb-2">Welcome {{ app.auth.user.name }}</v-alert>

      <v-card-text>
        <v-text-field label="E-mail" v-model="app.login.params.email" />
        <v-text-field label="Password" type="password" v-model="app.login.params.password" />
      </v-card-text>

      <v-divider />

      <v-card-actions>
        <v-btn type="button" @click="app.logout()" v-if="app.auth.token">Logout</v-btn>
        <v-spacer />
        <v-btn type="submit" :loading="app.login.loading">Login</v-btn>
      </v-card-actions>

      <template v-if="app.account.list.length>0">
        <v-divider />
        <v-list>
          <v-list-item
            v-for="acc in app.account.list"
            :active="app.auth.user && app.auth.user.email==acc.email"
          >
            <div>{{ acc.email }}</div>
            <template #append>
              <v-btn icon="mdi-login" flat size="x-small" @click="app.account.switch(acc.email)"></v-btn>
            </template>
          </v-list-item>
        </v-list>
      </template>
    </v-card>
  </form>
</template>

<script setup>
  import useApp from '@/composables/useApp';
  const app = useApp();

  const credentials = ref({
    email: '',
    password: '',
  });
</script>