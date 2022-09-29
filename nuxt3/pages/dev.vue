<template>
  <v-row>
    <v-col cols="3">
      <v-list>
        <v-list-item to="/dev">
          Home
        </v-list-item>
        <v-list-item to="/dev/auth">
          Auth
        </v-list-item>
      </v-list>
    </v-col>
    <v-col cols="9">
      <nuxt-child />
    </v-col>
  </v-row>
</template>