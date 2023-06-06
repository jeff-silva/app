<template>
  <form @submit.prevent="app.login.submit()">
    <v-text-field label="E-mail" v-model="app.login.data.email" />
    <v-text-field label="Password" type="password" v-model="app.login.data.password" />
    <v-btn block color="primary" type="submit" :loading="app.login.loading">Login</v-btn>
  </form>
</template>

<script setup>
  import useApp from '@/composables/useApp';
  const app = useApp();
</script>

<!-- <template>
  <form @submit.prevent="app.login.submit()">
    <v-fade-transition>
      <v-alert
        type="error"
        rounded="0"
        v-if="app.login.error.message"
        @click="app.login.error.clear()"
      >
        {{ app.login.error.message }}
      </v-alert>
    </v-fade-transition>

    <v-alert
      type="success"
      rounded="0"
      v-if="app.login.resp.access_token"
    >
      Logged successfully
    </v-alert>

    <v-alert rounded="0" class="mb-2" v-if="app.auth.user">Welcome {{ app.auth.user.name }}</v-alert>

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
          <div :class="{ 'text-disabled': !acc.valid }">{{ acc.email }}</div>
          <template #append>
            <div class="d-flex" style="gap:5px;">
              <v-btn icon="mdi-close" flat size="x-small" @click="app.account.remove(acc.email)" v-if="!acc.valid"></v-btn>
              <v-btn icon="mdi-login" flat size="x-small" @click="app.account.switch(acc.email)" v-if="acc.valid"></v-btn>
            </div>
          </template>
        </v-list-item>
      </v-list>
    </template>
  </form>
</template>

<script setup>
  import useApp from '@/composables/useApp';
  const app = useApp();
</script> -->