<template>
  <div>
    <v-row>
      <v-col cols="12">
        <v-text-field label="App name" v-model="app.settings['app.name']" />
      </v-col>
      <v-col cols="12">
        <v-text-field label="Tempo de autenticação" v-model="app.settings['jwt.ttl']">
          <template #append-inner>Minutos</template>  
        </v-text-field>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  data() {
    return {
      app: useApp(),
    };
  },
};
</script>