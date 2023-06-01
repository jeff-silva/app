<template>
  <nuxt-layout name="admin">
    <template #default>
      <app-model-crud
        v-bind="{
          name: 'app_user',
          searchTableSizes: ['200', '*'],
        }"
      >
        <template #search-table-header="bind">
          <th>Name</th>
          <th>E-mail</th>
        </template>

        <template #search-table-loop="bind">
          <td>{{ bind.item.name }}</td>
          <td>{{ bind.item.email }}</td>
        </template>
      </app-model-crud>
    </template>
  </nuxt-layout>
</template>