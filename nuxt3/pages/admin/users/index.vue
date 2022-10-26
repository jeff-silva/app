<template>
  <nuxt-layout name="admin">
    <app-model-search model="users">
      <template #table-header>
        <th>Nome</th>
        <th>Email</th>
      </template>

      <template #table-row="row">
        <td>Nome</td>
        <td>{{ row }}</td>
      </template>
    </app-model-search>
  </nuxt-layout>
</template>