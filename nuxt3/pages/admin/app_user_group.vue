<template>
  <nuxt-layout name="admin">
    <template #default>
      <app-model-crud name="app_user_group">
        <template #search-table-header="bind">
          <th>Name</th>
        </template>

        <template #search-table-loop="bind">
          <td>{{ bind.item.name }}</td>
        </template>
      </app-model-crud>
    </template>
  </nuxt-layout>
</template>