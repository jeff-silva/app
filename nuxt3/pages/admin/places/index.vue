<template>
  <nuxt-layout name="admin">
    <app-model-search model="places">
      <template #table-header>
        <th>Nome</th>
      </template>

      <template #table-row="row">
        <td>{{ row.item.name }}</td>
      </template>

      <template #table-actions="row">
        <v-btn icon="mdi-pencil" :to="`/admin/places/${row.item.id}`"></v-btn>
      </template>
    </app-model-search>
  </nuxt-layout>
</template>

<script>
  export default {
    methods: {
      click() {
        alert('Aaa');
      },
    },
  };
</script>