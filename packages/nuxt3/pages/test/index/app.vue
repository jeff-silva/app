<template>
  <div>
    <v-row>
      <v-col cols="6">
        <v-text-field label="User name" v-model="app.user.name" />
      </v-col>
      <v-col cols="6">
        <v-text-field label="Field name" v-model="field" />
        <v-text-field label="Field value" v-model="app[ field ]" />
      </v-col>
    </v-row>
    <pre>app: {{ app }}</pre>
  </div>
</template>

<script>
export default {
  data() {
    return {
      app: useApp(),
      field: 'test',
    };
  },
};
</script>