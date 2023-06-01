<template>
  <nuxt-layout name="admin">
    <template #default>
      <app-model-crud name="app_mail_template">
        <template #search-table-header="bind">
          <th>Name</th>
          <th>Pre</th>
        </template>

        <template #search-table-loop="bind">
          <td>{{ bind.item.name }}</td>
          <td><pre>{{ bind }}</pre></td>
        </template>
      </app-model-crud>
    </template>
  </nuxt-layout>
</template>