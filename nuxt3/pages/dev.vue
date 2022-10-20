<template>
  <nuxt-layout name="admin">
    <template #sidebar>
      <v-list variant="elevated">
        <v-list-item to="/dev">
          Home
        </v-list-item>
        <v-list-item to="/dev/auth">
          Auth
        </v-list-item>
        <v-list-item to="/dev/test">
          Test
        </v-list-item>
      </v-list>
    </template>

    <nuxt-page />
  </nuxt-layout>
</template>