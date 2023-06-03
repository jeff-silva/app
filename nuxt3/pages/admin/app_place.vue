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
        @ready="map.init()"
        @switch="map.init()"
        @edit:loaded="crudEditLoadedHandler($event)"
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
            :zoom="0"
            :center="[ 0, 0 ]"
            :use-global-leaflet="false"
            style="height:200px"
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

          <div style="position:relative; height:400px; z-index:1;">
            <l-map
              ref="mapRef"
              :zoom="map.zoom"
              :center="map.center"
              :use-global-leaflet="false"
              style="height:100%;"
              @ready="map.mapReadyHandler($event)"
            >
              <l-tile-layer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png" layer-type="base" name="OpenStreetMap" />
  
              <l-marker
                :lat-lng="map.center"
                :draggable="true"
                @update:latLng="map.markerUpdateLatLngHandler($event)"
              />
            </l-map>
            <div class="pa-2" style="position:absolute; bottom:0; right:0; z-index:9999;">
              <v-btn
                v-if="map.changed"
                color="primary"
                :loading="map.setAddressLoading"
                @click="map.setAddress()"
              >
                Set address
              </v-btn>
            </div>
          </div>
        </template>
      </app-model-crud>
    </template>
  </nuxt-layout>
</template>

<script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import 'leaflet/dist/leaflet.css';
  import { LMap, LTileLayer, LMarker } from '@vue-leaflet/vue-leaflet';

  const crud = ref({});
  const mapRef = ref(null);

  const crudEditLoadedHandler = ($event) => {
    map.value.init();
    if (!$event.edit.data.lat) return;
    if (!$event.edit.data.lng) return;
    map.value.center = [
      $event.edit.data.lat,
      $event.edit.data.lng,
    ];
  };

  const map = ref({
    changed: false,
    zoom: 14,
    center: [ 0, 0 ],
    setAddressLoading: false,
    init() {
      this.changed = false;
      this.zoom = 14;
      this.center = [ 0, 0 ];
      this.setAddressLoading = false;
    },
    async setAddress() {
      this.setAddressLoading = true;
      try {
        const { data } = await axios.get(`https://nominatim.openstreetmap.org/reverse?format=json&addressdetails=1&extratags=1&namedetails=1&limit=10&lat=${this.center[0]}&lon=${this.center[1]}`);
        const [ country_short, state_short ] = (data.address['ISO3166-2-lvl4'] || '-').toLowerCase().split('-');
        crud.value.edit.data.name = '';
        crud.value.edit.data.number = '';
        crud.value.edit.data.reference = '';
        crud.value.edit.data.route = data.address.road || null;
        crud.value.edit.data.zipcode = data.address.postcode || null;
        crud.value.edit.data.district = data.address.neighbourhood || data.address.suburb || null;
        crud.value.edit.data.city = data.address.city || data.address.town || data.address.city_district || null;
        crud.value.edit.data.state = data.address.state || null;
        crud.value.edit.data.state_short = state_short || null
        crud.value.edit.data.country = data.address.country || null;
        crud.value.edit.data.country_short = data.address.country_code || country_short || null;
        crud.value.edit.data.lat = data.lat || null;
        crud.value.edit.data.lng = data.lon || null;
        this.changed = false;
      } catch(err) {}
      this.setAddressLoading = true;
    },
    setCenter(coords) {
      this.changed = true;
      this.setAddressLoading = false;
      this.center = coords;
    },
    mapReadyHandler($ev) {
      $ev.on('click', (e) => {
        this.setCenter([ e.latlng.lat, e.latlng.lng ]);
      });
    },
    markerUpdateLatLngHandler($ev) {
      this.setCenter([ $ev.lat, $ev.lng ]);
    },
  });
</script>