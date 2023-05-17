<template>
  <nuxt-layout name="admin">
    <app-model-search model="tur_vehicle_model">
      <template #table-header>
        <th>Nome</th>
      </template>

      <template #table-row="row">
        <td>{{ row.item.name }}</td>
      </template>

      <template #table-actions="row">
        <!--  -->
      </template>
    </app-model-search>
  </nuxt-layout>
</template>

<script>
  export default {
    methods: {
      // 
    },
  };
</script>