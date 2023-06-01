<template>
  <nuxt-layout name="admin">
    <template #default>
      <app-model-crud
        v-bind="{
          name: 'app_mail_template',
          searchTableSizes: ['*'],
        }"
      >
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