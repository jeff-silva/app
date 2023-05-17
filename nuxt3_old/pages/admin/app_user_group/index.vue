<template>
  <nuxt-layout name="admin">
    <app-model-search model="app_user_group">
      <template #table-header>
        <th>Nome</th>
        <th>Email</th>
      </template>

      <template #table-row="row">
        <td>{{ row.item.name }}</td>
        <td>{{ row.item.email }}</td>
      </template>

      <template #table-actions="row">
        <!-- <v-btn icon="mdi-alpha-a-circle" @click="click()"></v-btn> -->
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