<template>
  <v-list>
    <v-list-item>
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
          <v-btn icon="mdi-login" flat size="x-small" @click="app.accountSwitch(acc.email)" v-if="acc.valid"></v-btn>
          <v-btn icon="mdi-close" flat size="x-small" @click="app.accountRemove(acc.email)"></v-btn>
        </div>
      </template>
    </v-list-item>
  </v-list>
</template>

<script setup>
  import { computed } from 'vue';
  import useApp from '@/composables/useApp';
  const app = useApp();

  const accounts = computed(() => {
    return Object.entries(app.accounts).map(([ email, data ]) => {
      return { email, ...data };
    });
  });
</script>