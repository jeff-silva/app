<template>
  <nuxt-layout name="admin">
    <template #sidebar>
      <app-nav variant="plain" :items="[
        {to:'/dev', name:'Home'},
        {to:'/dev/auth', name:'Auth'},
        {to:'/dev/test', name:'Test'},
      ]" />
      <v-divider />
      <app-nav variant="plain" :items="[
        {to:'/', name:'Home'},
        {to:'/admin', name:'Admin'},
      ]" />
    </template>

    <nuxt-page />
  </nuxt-layout>
</template>