<template>
  <nuxt-layout name="admin">
    <v-navigation-drawer>
      <app-nav
        :items="[
          {to:'/admin/settings', name:'Geral'},
          {to:'/admin/settings/email', name:'E-mail'},
        ]"
      />
    </v-navigation-drawer>
    
    <nuxt-page />

    <app-actions>
      <v-btn>Salvar</v-btn>
    </app-actions>
  </nuxt-layout>
</template>

<script>
export default {
  data() {
    return {
      // app: useApp(),
    };
  },
};
</script>