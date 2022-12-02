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
                    <v-row>
                      <v-col cols="12" md="6">
                        <v-text-field label="Nome" v-model="t.firstName" @input="generate()" />
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-text-field label="Sobrenome" v-model="t.lastName" @input="generate()" />
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-text-field label="Empresa" v-model="t.companyName" @input="generate()" />
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-text-field label="Cargo" v-model="t.jobTitle" @input="generate()" />
                      </v-col>
                      <v-col cols="12">
                        <v-text-field label="Endereço" v-model="t.address" @input="generate()" />
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-text-field label="CEP" v-model="t.zipcode" @input="generate()" />
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-text-field label="Cidade" v-model="t.city" @input="generate()" />
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-text-field label="Estado" v-model="t.state" @input="generate()" />
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-text-field label="País" v-model="t.country" @input="generate()" />
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-text-field label="Telefone" v-model="t.phone" @input="generate()" />
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-text-field label="Celular" v-model="t.mobile" @input="generate()" />
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-text-field label="E-mail" v-model="t.email" @input="generate()" />
                      </v-col>
                      <v-col cols="12" md="6">
                        <v-text-field label="Site" v-model="t.website" @input="generate()" />
                      </v-col>
                    </v-row>
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
        <div style="height:400px; display:flex; align-items:center; justify-content:center;">
          <app-qrcode v-model="qrcodeValue" style="width:100%;" v-if="qrcodeValue" />
          <div v-else>Preencha os dados ao lado<br /> para gerar o QR.</div>
        </div>
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
        qrcodeValue: false,
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
            key: 'john@doe.com',
            city: 'Brasília',
            fullName: 'John Doe',
            value: '100.00',
            message: 'Presentinho pra você :)',
            handle: (self) => {
              return this.pixClass(self);
            },
          },
        ],
        validURL: '00020101021126610014br.gov.bcb.pix0126jeferson.i.silva@gmail.com0209Teste PIX52040000530398654040.105802BR5923JEFERSON I S DA S E SIL6013BELO HORIZONT62070503***6304A8BF',
      };
    },

    methods: {
      generate() {
        this.$debounce('qr', 1000, () => {
          let tab = this.tabs.filter(tab => tab.id==this.tab)[0];
          tab._value = tab.handle(tab);
          this.qrcodeValue = tab._value;
        });
      },

      pixClass(params) {
        return (new (class {
          constructor(params = {}) {
            this.pixKey = params.key;
            this.description = params.message;
            this.merchantName = params.fullName.substring(0, 25).toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            this.merchantCity = params.city.substring(0, 15).toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            this.txid = '***';
            this.amount = parseFloat(params.value).toFixed(2);

            this.ID_PAYLOAD_FORMAT_INDICATOR = "00";
            this.ID_MERCHANT_ACCOUNT_INFORMATION = "26";
            this.ID_MERCHANT_ACCOUNT_INFORMATION_GUI = "00";
            this.ID_MERCHANT_ACCOUNT_INFORMATION_KEY = "01";
            this.ID_MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION = "02";
            this.ID_MERCHANT_CATEGORY_CODE = "52";
            this.ID_TRANSACTION_CURRENCY = "53";
            this.ID_TRANSACTION_AMOUNT = "54";
            this.ID_COUNTRY_CODE = "58";
            this.ID_MERCHANT_NAME = "59";
            this.ID_MERCHANT_CITY = "60";
            this.ID_ADDITIONAL_DATA_FIELD_TEMPLATE = "62";
            this.ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TXID = "05";
            this.ID_CRC16 = "63";
          }

          _getValue(id, value) {
            const size = String(value.length).padStart(2, "0");
            return id + size + value;
          }

          _getMechantAccountInfo() {
            const gui = this._getValue(this.ID_MERCHANT_ACCOUNT_INFORMATION_GUI, "br.gov.bcb.pix");
            const key = this._getValue(this.ID_MERCHANT_ACCOUNT_INFORMATION_KEY, this.pixKey);
            const description = this._getValue(this.ID_MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION, this.description);
            return this._getValue(this.ID_MERCHANT_ACCOUNT_INFORMATION, gui + key + description);
          }

          _getAdditionalDataFieldTemplate() {
            const txid = this._getValue(this.ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TXID, this.txid);
            return this._getValue(this.ID_ADDITIONAL_DATA_FIELD_TEMPLATE, txid);
          }

          getPayload() {
            if (!this.pixKey || !this.merchantName || !this.merchantCity) return '';
            const payload = [
              this._getValue(this.ID_PAYLOAD_FORMAT_INDICATOR, "01"),
              this._getMechantAccountInfo(),
              this._getValue(this.ID_MERCHANT_CATEGORY_CODE, "0000"),
              this._getValue(this.ID_TRANSACTION_CURRENCY, "986"),
              this._getValue(this.ID_TRANSACTION_AMOUNT, this.amount),
              this._getValue(this.ID_COUNTRY_CODE, "BR"),
              this._getValue(this.ID_MERCHANT_NAME, this.merchantName),
              this._getValue(this.ID_MERCHANT_CITY, this.merchantCity),
              this._getAdditionalDataFieldTemplate(),
            ].join('');
            return payload + this._getCRC16(payload);
          }

          _getCRC16(payload) {
            const ord = (str) => { return str.charCodeAt(0); };
            const dechex = (number) => { if (number < 0) { number = 0xffffffff + number + 1; } return parseInt(number, 10).toString(16); };
            payload = payload + this.ID_CRC16 + "04";
            let polinomio = 0x1021;
            let resultado = 0xffff;
            let length;
            if ((length = payload.length) > 0) {
              for (let offset = 0; offset < length; offset++) {
                resultado ^= ord(payload[offset]) << 8;
                for (let bitwise = 0; bitwise < 8; bitwise++) {
                  if ((resultado <<= 1) & 0x10000) resultado ^= polinomio;
                  resultado &= 0xffff;
                }
              }
            }
            return this.ID_CRC16 + "04" + dechex(resultado).toUpperCase();
          }
        })(params)).getPayload();
      },
    },

    mounted() {
      this.generate();
    },
  };
</script>