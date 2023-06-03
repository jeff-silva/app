<template>
  <nuxt-layout name="admin">
    <template #default>
      <app-model-crud
        v-model="crud"
        v-bind="{
          name: 'app_place',
          searchTableSizes: ['*'],
          searchParams: {},
        }"
        @search:loaded="searchMapReadyHandler($event)"
      >
        <template #search-table-header="bind">
          <th>Name</th>
        </template>

        <template #search-table-loop="bind">
          <td>{{ bind.item.name }}</td>
        </template>

        <!-- Search fields -->
        <template #search-fields="bind">
          <l-map
            ref="searchFieldsMapRef"
            :zoom="0"
            :center="[ 0, 0 ]"
            :use-global-leaflet="false"
            style="height:400px"
            @ready="searchMapReadyHandler($event)"
          >
            <l-tile-layer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png" layer-type="base" name="OpenStreetMap" />

            <template v-for="p in bind.search.data">
              <l-marker :lat-lng="[ p.lat, p.lng ]" />
            </template>
          </l-map>
        </template>

        <template #edit-fields="bind">
          <v-row>
            <v-col cols="12">
              <v-text-field
                v-model="bind.edit.data.name"
                label="Name"
                :readonly="true"
                :error-messages="bind.edit.error.get('name')"
              />
            </v-col>

            <v-col cols="12">
              <app-place
                :zoom="16"
                :center="[ bind.edit.data.lat, bind.edit.data.lng ]"
                @change="appPlaceChangeHandler($event)"
              />
            </v-col>

            <v-col cols="12" md="8">
              <v-text-field
                v-model="bind.edit.data.route"
                label="Route"
                :error-messages="bind.edit.error.get('route')"
              />
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                v-model="bind.edit.data.number"
                label="Number"
                :error-messages="bind.edit.error.get('number')"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="bind.edit.data.zipcode"
                label="Zip code"
                :error-messages="bind.edit.error.get('zipcode')"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="bind.edit.data.reference"
                label="Reference"
                :error-messages="bind.edit.error.get('reference')"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="bind.edit.data.district"
                label="District"
                :error-messages="bind.edit.error.get('district')"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="bind.edit.data.city"
                label="City"
                :error-messages="bind.edit.error.get('city')"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="bind.edit.data.state"
                label="State"
                :error-messages="bind.edit.error.get('state')"
              />
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                v-model="bind.edit.data.country"
                label="Country"
                :error-messages="bind.edit.error.get('country')"
              />
            </v-col>
          </v-row>
        </template>
      </app-model-crud>
    </template>
  </nuxt-layout>
</template>

<script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  
  import 'leaflet/dist/leaflet.css';
  import { LMap, LTileLayer, LMarker } from '@vue-leaflet/vue-leaflet';
  // import * as L from "leaflet/dist/leaflet-src.esm.js";
  // import 'leaflet/dist/leaflet';
  // import 'leaflet';

  const crud = ref({});
  const searchFieldsMapRef = ref(null);
  
  const appPlaceChangeHandler = (addr) => {
    crud.value.edit.data = {
      ...crud.value.edit.data,
      ...addr
    };
  };

  const searchMapReadyHandler = () => {
    setTimeout(() => {
      const map = searchFieldsMapRef.value.leafletObject;
      const markers = crud.value.search.data.map((addr) => ([ addr.lat, addr.lng ]));
      if (markers.length>0) map.fitBounds(markers);
    }, 1000);
  };
</script>