<template>
  <div style="width:100%; height:400px; position:relative; z-index:1;">
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
    
    <div class="pa-3" style="position:absolute; bottom:0; left:0; width:100%; z-index:9999; gap:10px;">
      <v-menu
        v-bind="{
          closeOnContentClick: false,
          maxWidth: 400,
        }"
      >
        <template #activator="{ props }">
          <div v-bind="props">
            <v-text-field
              v-model="search.params.amenity"
              v-bind="{
                variant: 'solo',
                label: 'Search address',
                hideDetails: true,
                loading: search.loading,
                density: 'compact',
                appendInnerIcon: (search.loading ? 'mdi-dots-horizontal' : 'mdi-magnify'),
              }"
              @input="search.submit()"
            />
          </div>
        </template>

        <v-list class="my-3" style="max-height:300px; overflow:auto;" :loading="true">
          <v-list-subheader>{{ search.data.length }} results for <q>{{ search.params.street }}</q></v-list-subheader>
          <template v-for="a in search.data">
            <v-list-item @click="map.setFromSearchResult(a)">
              <v-list-item-title v-text="a.display_name"></v-list-item-title>
            </v-list-item>
          </template>
        </v-list>
      </v-menu>
    </div>
  </div>
</template>

<script setup>
  import 'leaflet/dist/leaflet.css';
  import { LMap, LTileLayer, LMarker } from '@vue-leaflet/vue-leaflet';

  import { ref, watch, defineEmits, defineProps } from 'vue';
  import axios from 'axios';

  const emit = defineEmits(['change']);
  const props = defineProps({
    zoom: {
      type: Number,
      default: 16,
    },
    center: {
      type: Array,
      default: () => ([ 0, 0 ]),
    },
  });

  const map = ref({
    zoom: props.zoom,
    center: [
      (props.center[0] || 0),
      (props.center[1] || 0),
    ],
    loading: false,
    async emitAddressFromCoords(coords) {
      if (this.loading) {
        clearTimeout(this.loading);
        this.loading = false;
      }

      this.loading = setTimeout(async () => {
        try {
          const { data } = await axios.get(`https://nominatim.openstreetmap.org/reverse?format=json&addressdetails=1&extratags=1&namedetails=1&limit=10&lat=${coords[0]||0}&lon=${coords[1]||0}`);
          const [ country_short, state_short ] = (data.address['ISO3166-2-lvl4'] || '-').toUpperCase().split('-');
          emit('change', {
            name: '',
            route: (data.address.road || null),
            number: '',
            complement: '',
            zipcode: (data.address.postcode || null),
            reference: '',
            district: (data.address.neighbourhood || data.address.suburb || null),
            city: (data.address.city || data.address.town || data.address.city_district || null),
            state: (data.address.state || null),
            state_short: (state_short || null),
            country: (data.address.country || null),
            country_short: (data.address.country_code || country_short || null),
            lat: parseFloat(data.lat || null),
            lng: parseFloat(data.lon || null),
          });
          this.loading = false;
        } catch(err) {}
      }, 1000);
    },
    async mapReadyHandler($ev) {
      $ev.on('click', (e) => {
        this.center = [ e.latlng.lat, e.latlng.lng ];
        this.emitAddressFromCoords([ e.latlng.lat, e.latlng.lng ]);
      });
    },
    async setFromSearchResult(addr) {
      this.center = [ parseFloat(addr.lat), parseFloat(addr.lon) ];
      this.emitAddressFromCoords(this.center);
    },
    async markerUpdateLatLngHandler($ev) {
      this.emitAddressFromCoords([ $ev.lat, $ev.lng ]);
    },
  });

  const search = ref({
    loading: false,
    params: {
      format: 'json',
      amenity: null,
      street: null,
      city: null,
      county: null,
      state: null,
      country: null,
      postalcode: null,
    },
    data: [],
    async submit() {
      if (this.loading) {
        clearTimeout(this.loading);
        this.loading = false;
      }
      this.loading = setTimeout(async () => {
        try {
          const params = this.params;
          const { data } = await axios.get('https://nominatim.openstreetmap.org/search.php', { params });
          this.data = data;
        } catch(err) {}
        this.loading = false;
      }, 1000);
    },
  });

  watch([ props ], ([ propsNew ]) => {
    map.value.center = [
      (propsNew.center[0] || 0),
      (propsNew.center[1] || 0),
    ];
  });
</script>