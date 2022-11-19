<template>
  <div>
    <v-row>
      <v-col cols="12" md="7">
        <div class="d-flex" style="gap: 15px;">
          <div style="min-width: 200px; max-width: 200px;">
            <v-tabs v-model="tab" direction="vertical">
              <template v-for="t in tabs">
                <v-tab :value="t.id">{{ t.name }}</v-tab>
              </template>
            </v-tabs>
          </div>
          <div class="flex-grow-1" style="overflow:auto;">
            <v-window v-model="tab">
              <template v-for="t in tabs">
                <v-window-item :value="t.id">

                  <template v-if="t.id=='text'">
                    <v-text-field label="Texto" v-model="t.text" @input="generate()" />
                  </template>
                  
                  <template v-if="t.id=='url'">
                    <v-text-field label="Texto" v-model="t.url" @input="generate()" />
                  </template>
                  
                  <template v-if="t.id=='wifi'">
                    <v-text-field label="Rede" v-model="t.ssid" @input="generate()" />
                    <v-select v-model="t.type" :items="['WPA', 'WEP']" @input="generate()" />
                    <v-text-field label="Senha" type="password" v-model="t.pass" @input="generate()" />
                  </template>
                  
                  <template v-if="t.id=='vcard'">
                    <v-text-field label="Nome" v-model="t.firstName" @input="generate()" />
                    <v-text-field label="Sobrenome" v-model="t.lastName" @input="generate()" />
                    <v-text-field label="Empresa" v-model="t.companyName" @input="generate()" />
                    <v-text-field label="Cargo" v-model="t.jobTitle" @input="generate()" />
                    <v-text-field label="Endereço" v-model="t.address" @input="generate()" />
                    <v-text-field label="CEP" v-model="t.zipcode" @input="generate()" />
                    <v-text-field label="Cidade" v-model="t.city" @input="generate()" />
                    <v-text-field label="Estado" v-model="t.state" @input="generate()" />
                    <v-text-field label="País" v-model="t.country" @input="generate()" />
                    <v-text-field label="Telefone" v-model="t.phone" @input="generate()" />
                    <v-text-field label="Celular" v-model="t.mobile" @input="generate()" />
                    <v-text-field label="E-mail" v-model="t.email" @input="generate()" />
                    <v-text-field label="Site" v-model="t.website" @input="generate()" />
                  </template>
                  
                  <template v-if="t.id=='event'">
                    <v-text-field label="Texto" v-model="t.summary" @input="generate()" />
                  </template>

                  <template v-if="t.id=='sms'">
                    <v-text-field label="Telefone" v-model="t.mobile" @input="generate()" />
                    <v-textarea label="Mensagem" v-model="t.message" @input="generate()" />
                  </template>

                  <template v-if="t.id=='phone'">
                    <v-text-field label="Telefone" v-model="t.phone" @input="generate()" />
                  </template>
                  
                  <template v-if="t.id=='geo'">
                    <l-map :zoom="2" :center="[t.lat, t.lng]" style="height:400px;">
                      <l-tile-layer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png" layer-type="base" name="OpenStreetMap" :max-zoom="10" />
                      <!-- <l-tile-layer url="https://s3.amazonaws.com/te512.safecast.org/{z}/{x}/{y}.png" attribution="attribution" :min-zoom="5" :max-zoom="7" /> -->
                      <l-marker :lat-lng="[t.lat, t.lng]" draggable @move="t.onMapMove($event, t)"> </l-marker>
                    </l-map>
                    <v-btn @click="t.getGeolocation(t)" block color="primary" rounded="0">Meu posicionamento atual</v-btn>
                  </template>
                </v-window-item>
              </template>
            </v-window>
          </div>
        </div>
      </v-col>
      <v-col cols="12" md="5">
        <img :src="base64Url" alt="" v-if="base64Url" style="width: 100%;">
      </v-col>
    </v-row>
  </div>
</template>

<script>
  import "leaflet/dist/leaflet.css";
  import { LMap, LTileLayer, LMarker } from "@vue-leaflet/vue-leaflet";

  export default {
    components: { LMap, LTileLayer, LMarker },

    data() {
      return {
        loaded: false,
        base64Url: false,
        tab: 'text',
        tabs: [
          {
            id: 'text',
            name: 'Texto',
            text: 'Hello World :)',
            handle: (self) => {
              return self.text;
            },
          },
          {
            id: 'url',
            name: 'URL',
            url: '',
            handle: (self) => {
              return self.text;
            },
          },
          {
            id: 'wifi',
            name: 'WIFI',
            ssid: '',
            type: 'WPA',
            pass: '',
            handle: (self) => {
              return `WIFI:S:${self.ssid};T:${self.type};P:${self.pass};;`;
            },
          },
          {
            id: 'vcard',
            name: 'Cartão de contato',
            firstName: '',
            lastName: '',
            companyName: '',
            jobTitle: '',
            address: '',
            city: '',
            state: '',
            zipcode: '',
            country: '',
            phone: '',
            mobile: '',
            email: '',
            website: '',
            handle: (self) => {
              return [
                `BEGIN:VCARD`,
                `VERSION:3.0`,
                `N:${self.lastName};${self.firstName}`,
                `FN:${self.firstName} ${self.lastName}`,
                `ORG:${self.companyName}`,
                `TITLE:${self.jobTitle}`,
                `ADR:;;${self.address};${self.city};${self.state};${self.zipcode};${self.country}`,
                `TEL;WORK;VOICE:${self.phone}`,
                `TEL;CELL:${self.mobile}`,
                `TEL;FAX:`,
                `EMAIL;WORK;INTERNET:${self.email}`,
                `URL:${self.website}`,
                `END:VCARD`,
              ].join("\n");
            },
          },
          {
            id: 'event',
            name: 'Evento',
            summary: '',
            dateStart: '',
            dateFinal: '',
            handle: (self) => {
              return [
                `BEGIN:VEVENT`,
                `SUMMARY:Independence Day Parades`,
                `DTSTART:20150323T090000`,
                `DTEND:20150323T110000`,
                `END:VEVENT`,
              ].join("\n");
            },
          },
          {
            id: 'sms',
            name: 'SMS',
            mobile: '',
            message: '',
            handle: (self) => {
              return `SMSTO:+${self.mobile.replace(/[^0-9]/g, '')}:${self.message}`;
            },
          },
          {
            id: 'phone',
            name: 'Ligação',
            phone: '',
            handle: (self) => {
              return `tel:${self.phone.replace(/[^0-9]/g, '')}`;
            },
          },
          {
            id: 'geo',
            name: 'Geo posicionamento',
            lat: 0,
            lng: 0,
            handle: (self) => {
              return `geo:${self.lat},${self.lng}`;
            },
            getGeolocation: async (self) => {
              navigator.geolocation.getCurrentPosition(
                (pos) => {
                  self.lat = pos.coords.latitude;
                  self.lng = pos.coords.longitude;
                  this.generate();
                },
                () => {},
                {
                  enableHighAccuracy: true,
                  timeout: 5000,
                  maximumAge: 0
                }
              );
            },
            onMapMove: (ev, self) => {
              self.lat = ev.latlng.lat;
              self.lng = ev.latlng.lng;
              this.generate();
            },
          },
        ],
      };
    },

    methods: {
      scriptLoaded() {
        setTimeout(() => {
          this.loaded = true;
          this.generate();
        }, 200);
      },

      generate() {
        this.$debounce('qr', 1000, () => {
          let tab = this.tabs.filter(tab => tab.id==this.tab)[0];
          tab._value = tab.handle(tab);
          try {
            let q = new QRCode(document.createElement('div'), {
              type: "text",
              text: tab._value,
              wifiSSID: "",
              wifiCrypt: "WPA",
              wifiPassword: "",
              width: 402,
              height: 402,
              colorDark : "#000000",
              colorLight : "#ffffff",
            });
            this.base64Url = q._el.children[0].toDataURL();
          }
          catch(e) {}
        });
      },
    },

    mounted() {
      useHead({
        script: [
          {src: 'https://cdn.jsdelivr.net/npm/davidshimjs-qrcodejs/qrcode.min.js', load: this.scriptLoaded},
        ],
      });
    },
  };
</script>