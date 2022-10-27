<template>
  <nuxt-layout name="admin">
    <app-model-search model="files">
      <template #table-header>
        <th>Nome</th>
        <th>Email</th>
      </template>

      <template #table-row="row">
        <td>{{ row.item.name }}</td>
        <td>{{ row.item.email }}</td>
      </template>

      <template #table-actions="row">
        <v-btn icon="mdi-pencil" :to="`/admin/files/${row.item.id}`"></v-btn>
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