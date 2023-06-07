<template>
  <v-list>
    <v-list-item v-if="accounts.length==0">
      <div class="text-disabled text-center">
        No users logged
      </div>
    </v-list-item>
    <v-list-item
      v-for="acc in accounts"
      :active="app.user && app.user.email==acc.email"
    >
      <div :class="{ 'text-disabled': !acc.valid }">{{ acc.email }}</div>
      <template #append>
        <div class="d-flex" style="gap:5px;">
          <v-btn icon="mdi-login" flat size="x-small" @click="accountSwitch(acc.email)" v-if="acc.valid"></v-btn>
          <v-btn icon="mdi-close" flat size="x-small" @click="accountRemove(acc.email)"></v-btn>
        </div>
      </template>
    </v-list-item>
  </v-list>
</template>

<script setup>
  import { computed, defineEmits } from 'vue';
  const emit = defineEmits(['switch']);

  import useApp from '@/composables/useApp';
  const app = useApp();

  const accountSwitch = (email) => {
    app.accountSwitch(email);
    emit('switch', { email });
  };

  const accountRemove = (email) => {
    app.accountRemove(email);
    emit('remove', { email });
  };

  const accounts = computed(() => {
    return Object.entries(app.accounts).map(([ email, data ]) => {
      return { email, ...data };
    });
  });
</script>