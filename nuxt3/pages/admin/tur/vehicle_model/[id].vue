<template>
  <nuxt-layout name="admin">
    <app-model-edit model="tur_vehicle_model">
      <template #edit-fields="form">
        <v-row>
          <v-col cols="12">
            <app-model-select
              label="Brand"
              v-model="form.post.brand_id"
              model="tur_vehicle_brand"
            />
          </v-col>
          <v-col cols="12">
            <v-text-field label="Nome" v-model="form.post.name" />
          </v-col>
        </v-row>
      </template>
    </app-model-edit>
  </nuxt-layout>
</template>