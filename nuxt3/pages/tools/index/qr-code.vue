<template>
  <div>
    <v-row>
      <v-col cols="12" md="6">
        <v-tabs v-model="tab">
          <v-tab value="text">Texto</v-tab>
          <v-tab value="wifi">Wifi</v-tab>
          <v-tab value="vcard">VCard</v-tab>
          <v-tab value="event">Evento</v-tab>
        </v-tabs>
        <v-card-text>
          <v-window v-model="tab">

            <!-- text -->
            <v-window-item value="text">
              <v-text-field label="Texto" v-model="params.text" @input="generate()" />
            </v-window-item>

            <!-- wifi -->
            <v-window-item value="wifi">
              <v-text-field label="Rede" v-model="params.wifi.ssid" @input="generate()" />
              <v-select v-model="params.wifi.type" :items="['WPA', 'WEP']" @input="generate()" />
              <v-text-field label="Senha" type="password" v-model="params.wifi.pass" @input="generate()" />
            </v-window-item>

            <!-- vcard -->
            <v-window-item value="vcard">
              <v-text-field label="Nome" v-model="params.vcard.firstName" @input="generate()" />
              <v-text-field label="Sobrenome" v-model="params.vcard.lastName" @input="generate()" />
            </v-window-item>

            <!-- event -->
            <v-window-item value="event">
              <v-text-field label="Descrição" v-model="params.event.summary" @input="generate()" />
              <v-text-field label="Início" v-model="params.event.start" @input="generate()" />
              <v-text-field label="Fim" v-model="params.event.final" @input="generate()" />
            </v-window-item>
          </v-window>
        </v-card-text>
      </v-col>
      <v-col cols="12" md="6">
        <v-card>
          <v-card-text style="text-align: center;">
            <img :src="base64Url" alt="" v-if="base64Url">
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        loaded: false,
        base64Url: false,
        tab: 'text',
        params: {
          text: "Hello",
          wifi: {
            ssid: '',
            type: 'WPA',
            pass: '',
          },
          vcard: {
            firstName: '',
          },
          event: {},
        },
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
        let q = new QRCode(document.createElement('div'), this.compParams);
        this.base64Url = q._el.children[0].toDataURL();
      },
    },

    computed: {
      compParams() {
        let text = this.params.text;

        if (this.tab=='wifi') {
          text = `WIFI:S:${this.params.wifi.ssid};T:${this.params.wifi.type};P:${this.params.wifi.pass};;`;
        } else if (this.tab=='vcard') {
          text = [
            `BEGIN:VCARD`,
            `VERSION:3.0`,
            `N:${this.params.wifi.lastName};${this.params.wifi.firstName}`,
            `FN:${this.params.wifi.firstName} ${this.params.wifi.lastName}`,
            `ORG:CompanyName`,
            `TITLE:JobTitle`,
            `ADR:;;123 Sesame St;SomeCity;CA;12345;USA`,
            `TEL;WORK;VOICE:1234567890`,
            `TEL;CELL:Mobile`,
            `TEL;FAX:`,
            `EMAIL;WORK;INTERNET:foo@email.com`,
            `URL:http://website.com`,
            `END:VCARD`,
          ].join("\n");
        } else if (this.tab=='event') {
          text = [
            `BEGIN:VEVENT`,
            `SUMMARY:Independence Day Parades`,
            `DTSTART:20150323T090000`,
            `DTEND:20150323T110000`,
            `END:VEVENT`,
          ].join("\n");
        }

        return {
          type: "text",
          text,
          wifiSSID: "",
          wifiCrypt: "WPA",
          wifiPassword: "",
          width: 402,
          height: 402,
          colorDark : "#000000",
          colorLight : "#ffffff",
        };
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