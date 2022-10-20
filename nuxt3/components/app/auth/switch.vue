<template>
  <v-list>
    <v-list-item-subtitle class="pa-3">
      Accounts
    </v-list-item-subtitle>
    <v-list-item v-if="app.localStorage.accounts.length==0">
      Nenhum login
    </v-list-item>
    <v-list-item v-for="acc in app.localStorage.accounts" :key="acc.id" color="success">
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
</template>

<script>
export default {
  data() {
    return {
      app: useApp(),
    };
  },

  mounted() {
    useEventBus('useApp:onSwitch').on((data, err) => {
      this.$emit('success', data);
    });
  },
};
</script>