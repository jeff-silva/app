<template>
  <v-row>
    <v-col cols="6">
      <v-textarea label="Texto" v-model="params.text" auto-grow />
    </v-col>
    <v-col cols="6">
      <v-select label="Converter para" v-model="params.type" :items="items" />
      <template v-for="i in items">
        <template v-if="i.value==params.type">
          <v-card>
            <v-card-title>Resultado:</v-card-title>
            <v-card-text>
              {{ i.call() || 'Nenhum texto' }}
            </v-card-text>
          </v-card>
        </template>
      </template>
    </v-col>
  </v-row>
</template>

<script>
  export default {
    data() {
      return {
        params: {
          text: '',
          type: 'lowercase',
        },
        items: [
          {
            value: 'lowercase',
            title: 'Minúsculo',
            call: () => {
              return this.params.text.toLowerCase();
            },
          },
          {
            value: 'uppercase',
            title: 'Maiúsculo',
            call: () => {
              return this.params.text.toUpperCase();
            },
          },
          {
            value: 'capitalized',
            title: 'Capitalizado',
            call: () => {
              return this.params.text.replace(/(^\w{1})|(\s+\w{1})/g, letter => letter.toUpperCase());
            },
          },
        ],
      };
    },
  };
</script>