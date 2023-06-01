<template>
  <nuxt-layout name="admin">
    <template #default>
      <app-model-crud
        v-bind="{
          name: 'app_place',
          searchTableSizes: ['*'],
          searchParams: {},
        }"
      >
        <template #search-table-header="bind">
          <th>Name</th>
        </template>

        <template #search-table-loop="bind">
          <td>{{ bind.item.name }}</td>
        </template>

        <template #edit-fields="bind">
          <v-row>
            <v-col cols="12">
              <v-text-field
                v-model="bind.edit.data.name"
                label="Subject"
                :readonly="true"
              />
            </v-col>

            <v-col cols="12" md="8">
              <v-text-field
                v-model="bind.edit.data.route"
                label="Route"
              />
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                v-model="bind.edit.data.number"
                label="Number"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="bind.edit.data.zipcode"
                label="Zip code"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="bind.edit.data.reference"
                label="Reference"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="bind.edit.data.district"
                label="District"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="bind.edit.data.city"
                label="City"
              />
            </v-col>
          </v-row>
        </template>
      </app-model-crud>
    </template>
  </nuxt-layout>
</template>