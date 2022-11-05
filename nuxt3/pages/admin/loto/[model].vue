<template>
  <nuxt-layout name="admin">
    <app-model-search
      :model="$route.params.model"
      v-bind="{
        tableSizes: ['50px', '*'],
      }"
    >
      <template #table-header>
        <th>Sorteio</th>
        <th>Números</th>
      </template>

      <template #table-row="row">
        <td>{{ row.item.contest }}</td>
        <td><pre>{{ row.item.numbers }}</pre></td>
      </template>

      <template #form-actions>
        <v-btn color="success" @click="lotoImport.submit()" :loading="lotoImport.loading" block>
          <v-icon icon="mdi-download" /> Importar
        </v-btn>
      </template>
    </app-model-search>
  </nuxt-layout>
</template>

<script>
  export default {
    data() {
      return {
        lotoImport: useAxios({
          method: "post",
          url: `/api/${this.$route.params.model}/import`,
        }),
      };
    },
  };
</script>