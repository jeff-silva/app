<template>
  <v-layout>
    <v-navigation-drawer width="250" permanent>
      <v-list>
        <v-list-subheader>Menu</v-list-subheader>
        <v-list-item to="/test">Index</v-list-item>
        <v-list-item to="/test/api">API</v-list-item>
        <v-list-item to="/test/app">App</v-list-item>
        <v-list-item to="/">Sair</v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-main>
      <div class="pa-3">
        <nuxt-page />
      </div>
    </v-main>
  </v-layout>
</template>