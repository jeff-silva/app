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

          <l-map
            ref="mapRef"
            :zoom="map.zoom"
            :center="map.initialCenter(bind)"
            :use-global-leaflet="false"
            style="height:400px;"
          >
            <l-tile-layer
              url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
              layer-type="base"
              name="OpenStreetMap"
            ></l-tile-layer>
            <l-marker
              :lat-lng="map.center"
              :draggable="true"
              @update:latLng="map.markerUpdateCoords($event, bind)"
            />
          </l-map>

          <pre>{{ map }}</pre>
          <pre>{{ bind }}</pre>

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

  const map = ref({
    zoom: 14,
    center: [ -19.932686818611995, -43.95715713500977 ],
    initialCenter(bind) {
      const center = [
        (bind.edit.data.lat || 0),
        (bind.edit.data.lng || 0),
      ];

      this.center = center;
      return center;
    },
    markerUpdateCoords(ev, bind) {
      this.center = [ ev.lat, ev.lng ];
      bind.edit.data.lat = ev.lat;
      bind.edit.data.lng = ev.lng;
      this.osmCoords(this.center, bind);
    },
    osmCoordsLoading: false,
    async osmCoords(coords=[], bind) {
      try {
        if (this.osmCoordsLoading) {
          clearTimeout(this.osmCoordsLoading);
          this.osmCoordsLoading = false;
        }
        this.osmCoordsLoading = setTimeout(async () => {
          const { data } = await axios.get(`https://nominatim.openstreetmap.org/reverse?format=json&addressdetails=1&extratags=1&namedetails=1&limit=10&lat=${coords[0]||null}&lon=${coords[1]||null}`);
          bind.edit.data.name = '';
          bind.edit.data.number = '';
          bind.edit.data.reference = '';
          bind.edit.data.route = data.address.road || null;
          bind.edit.data.zipcode = data.address.postcode || null;
          bind.edit.data.district = data.address.neighbourhood || data.address.suburb || null;
          bind.edit.data.city = data.address.city || null;
          bind.edit.data.state = data.address.state || null;
          bind.edit.data.state_short = (data.address['ISO3166-2-lvl4'] ? data.address['ISO3166-2-lvl4'].split('-').at(1) : null) || null;
          bind.edit.data.country = data.address.country || null;
          bind.edit.data.country_short = data.address.country_code || null;
          // bind.edit.data.lat = data.lat || null;
          // bind.edit.data.lng = data.lon || null;
          console.log(data);
        }, 1000);
      } catch(err) {}
    },
  });
</script>