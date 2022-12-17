<template>
  <nuxt-layout name="admin">
    <app-model-edit model="tur_vehicle">
      <template #edit-fields="form">
        <v-row>
          <v-col cols="12">
            <v-text-field label="Nome" v-model="form.post.name" />
          </v-col>
          <v-col cols="12">
            <app-model-select
              label="Type"
              model="tur_vehicle_type"
              v-model="form.post.type_id"
            />
          </v-col>
          <v-col cols="12">
            <app-model-select
              label="Brand"
              model="tur_vehicle_brand"
              v-model="form.post.brand_id"
            />
          </v-col>
          <v-col cols="12">
            <app-model-select
              label="Model"
              model="tur_vehicle_model"
              v-model="form.post.model_id"
            />
          </v-col>
        </v-row>
      </template>
    </app-model-edit>
  </nuxt-layout>
</template>