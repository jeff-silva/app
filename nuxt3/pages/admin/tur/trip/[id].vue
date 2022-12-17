<template>
  <nuxt-layout name="admin">
    <app-model-edit model="tur_trip">
      <template #edit-fields="form">
        <v-row>
          <v-col cols="12">
            <v-text-field label="Nome" v-model="form.post.name" />
          </v-col>
          <v-col cols="12">
            <app-model-select
              label="Vehicle"
              model="tur_vehicle"
              v-model="form.post.vehicle_id"
            />
          </v-col>
          <v-col cols="12">
            <app-model-select
              label="Driver"
              model="tur_driver"
              v-model="form.post.driver_id"
            />
          </v-col>
        </v-row>
      </template>
    </app-model-edit>
  </nuxt-layout>
</template>