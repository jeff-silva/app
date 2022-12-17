<template>
  <nuxt-layout name="admin">
    <app-model-edit model="tur_vehicle">
      <template #edit-fields="form">
        <v-row>
          <v-col cols="12">
            <v-text-field label="Nome" v-model="form.post.name" />
          </v-col>
        </v-row>
      </template>
    </app-model-edit>
  </nuxt-layout>
</template>