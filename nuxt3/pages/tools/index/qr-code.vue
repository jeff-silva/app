<template>
  <div>
    <v-row>
      <v-col cols="12" md="7">
        <div class="d-flex" style="gap: 15px;">
          <div style="min-width: 200px; max-width: 200px;">
            <v-tabs v-model="tab" direction="vertical" @update:modelValue="generate()">
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
                    <l-map :zoom="2" :center="[0, 0]" style="height:400px;">
                      <l-tile-layer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png" layer-type="base" name="OpenStreetMap" :max-zoom="10" />
                      <!-- <l-tile-layer url="https://s3.amazonaws.com/te512.safecast.org/{z}/{x}/{y}.png" attribution="attribution" :min-zoom="5" :max-zoom="7" /> -->
                      <l-marker :lat-lng="[t.lat, t.lng]" draggable @move="t.onMapMove($event, t)"> </l-marker>
                    </l-map>
                    <v-btn @click="t.getGeolocation(t)" block color="primary" rounded="0">Meu posicionamento atual</v-btn>
                  </template>

                  <template v-if="t.id=='pix'">
                    <v-text-field label="Chave pix (Única, e-mail, CPF, telefone, CNPJ)" v-model="t.key" @input="generate()" />
                    <v-text-field label="Cidade" v-model="t.city" @input="generate()" />
                    <v-text-field label="Nome do beneficiado" v-model="t.fullName" @input="generate()" />
                    <v-text-field label="Valor" v-model="t.value" @input="generate()" />
                    <v-text-field label="Mensagem" v-model="t.message" @input="generate()" />
                  </template>
                </v-window-item>
              </template>
            </v-window>
          </div>
        </div>
      </v-col>
      <v-col cols="12" md="5" style="text-align: center;">
        <img :src="base64Url" alt="" v-if="base64Url" style="width:100%; max-width:450px;">
      </v-col>
    </v-row>
    <app-dd>{{ [ (validURL==tabs[8]._value), validURL, tabs[8]._value ] }}</app-dd>
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
        tab: 'pix',
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
              return self.url;
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
          {
            id: 'pix',
            name: 'PIX',
            version: '01',
            key: 'jeferson.i.silva@gmail.com',
            city: 'Belo Horizont',
            fullName: 'JEFERSON I S DA S E SIL',
            value: '0.10',
            message: 'Teste PIX',
            transactionId: '',
            currency: '986',
            countryCode: 'BR',
            handle: (self) => {
              // https://github.com/bacen/pix-api
              // https://github.com/NascentSecureTech/pix-qrcode-utils
              const genEMV = (id, parameter) => {
                const len = parameter.length.toString().padStart(2, '0');
                console.log([id, len, parameter]);
                return [id, len, parameter].join('');
                // return `${id}${len}${parameter}`;
              };

              const generateKey = (key, message) => {
                const payload = [genEMV('00', 'br.gov.bcb.pix'), genEMV('01', key)];
                if (message) payload.push(genEMV('02', message));
                return payload.join('');
              };

              const numToHex = (n, digits) => {
                const hex = n.toString(16).toUpperCase();
                if ( digits ) { return ("0".repeat( digits ) + hex).slice( -digits ); }
                return ( hex.length % 2 == 0) ? hex : "0" + hex;
              };

              const crc16 = (str, invert=false) => {
                return 'A8BF';
                const bytes = new TextEncoder().encode(str);
                const crcTable = [0x0000, 0x1021, 0x2042, 0x3063, 0x4084, 0x50a5, 0x60c6, 0x70e7, 0x8108, 0x9129, 0xa14a, 0xb16b, 0xc18c, 0xd1ad, 0xe1ce, 0xf1ef, 0x1231, 0x0210, 0x3273, 0x2252, 0x52b5, 0x4294, 0x72f7, 0x62d6, 0x9339, 0x8318, 0xb37b, 0xa35a, 0xd3bd, 0xc39c, 0xf3ff, 0xe3de, 0x2462, 0x3443, 0x0420, 0x1401, 0x64e6, 0x74c7, 0x44a4, 0x5485, 0xa56a, 0xb54b, 0x8528, 0x9509, 0xe5ee, 0xf5cf, 0xc5ac, 0xd58d, 0x3653, 0x2672, 0x1611, 0x0630, 0x76d7, 0x66f6, 0x5695, 0x46b4, 0xb75b, 0xa77a, 0x9719, 0x8738, 0xf7df, 0xe7fe, 0xd79d, 0xc7bc, 0x48c4, 0x58e5, 0x6886, 0x78a7, 0x0840, 0x1861, 0x2802, 0x3823, 0xc9cc, 0xd9ed, 0xe98e, 0xf9af, 0x8948, 0x9969, 0xa90a, 0xb92b, 0x5af5, 0x4ad4, 0x7ab7, 0x6a96, 0x1a71, 0x0a50, 0x3a33, 0x2a12, 0xdbfd, 0xcbdc, 0xfbbf, 0xeb9e, 0x9b79, 0x8b58, 0xbb3b, 0xab1a, 0x6ca6, 0x7c87, 0x4ce4, 0x5cc5, 0x2c22, 0x3c03, 0x0c60, 0x1c41, 0xedae, 0xfd8f, 0xcdec, 0xddcd, 0xad2a, 0xbd0b, 0x8d68, 0x9d49, 0x7e97, 0x6eb6, 0x5ed5, 0x4ef4, 0x3e13, 0x2e32, 0x1e51, 0x0e70, 0xff9f, 0xefbe, 0xdfdd, 0xcffc, 0xbf1b, 0xaf3a, 0x9f59, 0x8f78, 0x9188, 0x81a9, 0xb1ca, 0xa1eb, 0xd10c, 0xc12d, 0xf14e, 0xe16f, 0x1080, 0x00a1, 0x30c2, 0x20e3, 0x5004, 0x4025, 0x7046, 0x6067, 0x83b9, 0x9398, 0xa3fb, 0xb3da, 0xc33d, 0xd31c, 0xe37f, 0xf35e, 0x02b1, 0x1290, 0x22f3, 0x32d2, 0x4235, 0x5214, 0x6277, 0x7256, 0xb5ea, 0xa5cb, 0x95a8, 0x8589, 0xf56e, 0xe54f, 0xd52c, 0xc50d, 0x34e2, 0x24c3, 0x14a0, 0x0481, 0x7466, 0x6447, 0x5424, 0x4405, 0xa7db, 0xb7fa, 0x8799, 0x97b8, 0xe75f, 0xf77e, 0xc71d, 0xd73c, 0x26d3, 0x36f2, 0x0691, 0x16b0, 0x6657, 0x7676, 0x4615, 0x5634, 0xd94c, 0xc96d, 0xf90e, 0xe92f, 0x99c8, 0x89e9, 0xb98a, 0xa9ab, 0x5844, 0x4865, 0x7806, 0x6827, 0x18c0, 0x08e1, 0x3882, 0x28a3, 0xcb7d, 0xdb5c, 0xeb3f, 0xfb1e, 0x8bf9, 0x9bd8, 0xabbb, 0xbb9a, 0x4a75, 0x5a54, 0x6a37, 0x7a16, 0x0af1, 0x1ad0, 0x2ab3, 0x3a92, 0xfd2e, 0xed0f, 0xdd6c, 0xcd4d, 0xbdaa, 0xad8b, 0x9de8, 0x8dc9, 0x7c26, 0x6c07, 0x5c64, 0x4c45, 0x3ca2, 0x2c83, 0x1ce0, 0x0cc1, 0xef1f, 0xff3e, 0xcf5d, 0xdf7c, 0xaf9b, 0xbfba, 0x8fd9, 0x9ff8, 0x6e17, 0x7e36, 0x4e55, 0x5e74, 0x2e93, 0x3eb2, 0x0ed1, 0x1ef0];
                let crc = 0xFFFF;

                for ( let i = 0; i < bytes.length; i++ ) {
                  const c = bytes[ i ];
                  const j = (c ^ (crc >> 8)) & 0xFF;
                  crc = crcTable[ j ] ^ (crc << 8);
                }

                let answer = ((crc ^ 0) & 0xFFFF);
                let hex = numToHex( answer, 4 );
                if (invert) return hex.slice(2) + hex.slice(0, 2);
                return hex;
              };

              const payload = [];

              payload.push(genEMV('00', self.version));
              
              // payload.push('______');
              payload.push('010211');

              payload.push(genEMV('26', generateKey(self.key, self.message)));
              payload.push(genEMV('52', '0000'));
              payload.push(genEMV('53', self.currency));

              if (self.value) {
                payload.push(genEMV('54', self.value));
              }

              payload.push(genEMV('58', self.countryCode.toUpperCase()));
              payload.push(genEMV('59', self.fullName.substring(0, 25).toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')));
              payload.push(genEMV('60', self.city.substring(0, 15).toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')));

              if (self.cep) payload.push(genEMV('61', self.cep));
              payload.push(genEMV('62', genEMV('05', self.transactionId)));

              payload.push('***');
              payload.push('6304');

              const stringPayload = payload.join('');
              const crcResult = crc16(stringPayload).toString(16).toUpperCase().padStart(4, '0');
              return `${stringPayload}${crcResult}`;
            },
          },
        ],
        validURL: '00020101021126610014br.gov.bcb.pix0126jeferson.i.silva@gmail.com0209Teste PIX52040000530398654040.105802BR5923JEFERSON I S DA S E SIL6013BELO HORIZONT62070503***6304A8BF',
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