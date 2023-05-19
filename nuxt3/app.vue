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
      </v-col>

      <v-col cols="4">
        <form @submit.prevent="app.register(register)">
          <v-card title="Register">
            <v-divider />
            <v-card-text>
              <v-text-field label="Name" v-model="register.name" />
              <v-text-field label="E-mail" v-model="register.email" />
              <v-text-field label="Password" type="password" v-model="register.password" />
              <v-text-field label="Repeat password" type="password" v-model="register.password_confirmation" />
            </v-card-text>
            <v-divider />
            <v-card-actions>
              <v-spacer />
              <v-btn type="submit" :loading="app.loading">Register</v-btn>
            </v-card-actions>
          </v-card>
        </form>
      </v-col>

      <v-col cols="4">
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
      </v-col>
    </v-row>

    <pre>{{ { $pwa, credentials, register, app } }}</pre>
  </v-defaults-provider>
</template>

<script setup>
  import useApp from '@/composables/useApp';
  import { ref } from 'vue';

  const app = useApp({
    onLogin() {
      credentials.value = {};
    },
  });

  const credentials = ref({
    email: 'main@grr.la',
    password: 'main@grr.la',
  });
  
  const register = ref({
    name: '',
    email: '',
    password: '',
  });
</script>