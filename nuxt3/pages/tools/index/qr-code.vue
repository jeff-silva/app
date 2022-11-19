<template>
  <div>
    <v-row>
      <v-col cols="12" md="6">
        <v-tabs v-model="tab">
          <v-tab value="text">Texto</v-tab>
          <v-tab value="wifi">Wifi</v-tab>
        </v-tabs>
        <v-card-text>
          <v-window v-model="tab">
            <v-window-item value="text">
              <v-text-field label="Texto" v-model="params.text" @input="generate()" />
            </v-window-item>

            <v-window-item value="wifi">
              <v-text-field label="Rede" v-model="params.wifi.ssid" @input="generate()" />
              <v-select v-model="params.wifi.type" :items="['WPA', 'WEP']" @input="generate()" />
              <v-text-field label="Senha" type="password" v-model="params.wifi.pass" @input="generate()" />
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