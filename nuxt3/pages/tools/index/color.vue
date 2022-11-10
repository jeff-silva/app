<template>
  <v-row>
    <v-col cols="12" md="6">
      <div style="display:flex; gap:15px; padding:10px; overflow:auto;">
        <v-card min-width="250px">
          <v-card-title>hex</v-card-title>
          <v-card-text>
            <v-color-picker v-model="params.hex" elevation="0" />
          </v-card-text>
        </v-card>
        <v-card min-width="250px">
          <v-card-title>hexa</v-card-title>
          <v-card-text>
            <v-color-picker v-model="params.hexa" elevation="0" />
          </v-card-text>
        </v-card>
        <v-card min-width="250px">
          <v-card-title>rgba</v-card-title>
          <v-card-text>
            <v-color-picker v-model="params.rgba" elevation="0" />
          </v-card-text>
        </v-card>
        <v-card min-width="250px">
          <v-card-title>hsla</v-card-title>
          <v-card-text>
            <v-color-picker v-model="params.hsla" elevation="0" />
          </v-card-text>
        </v-card>
        <v-card min-width="250px">
          <v-card-title>hsva</v-card-title>
          <v-card-text>
            <v-color-picker v-model="params.hsva" elevation="0" />
          </v-card-text>
        </v-card>
      </div>
    </v-col>
  </v-row>
</template>

<script>
  export default {
    data: () => ({
      params: {
        hex: '#000000',
        hexa: '#000000FF',
        rgba: {r:0, g:0, b:0, a:1},
        hsla: {h: 300, s: 0, l: 0, a: 1},
        hsva: {h: 300, s: 0, v: 0, a: 1},
        mode: 'rgba',
      },
    }),
  };
</script>