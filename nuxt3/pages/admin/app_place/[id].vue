<template>
  <nuxt-layout name="admin">
    <app-model-edit model="app_place">
      <template #edit-fields="form">
        <v-row>
          <v-col cols="12">
            <v-text-field label="Nome" v-model="form.post.name" />
          </v-col>
          <v-col cols="12">
            <pre>form: {{ form }}</pre>
          </v-col>
        </v-row>
      </template>
    </app-model-edit>
  </nuxt-layout>
</template>