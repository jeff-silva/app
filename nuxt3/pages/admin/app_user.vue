<template>
  <nuxt-layout name="admin">
    <template #default>
      <app-model-crud
        v-bind="{
          name: 'app_user',
          searchTableSizes: ['200', '*', '200'],
          searchParams: {
            with: 'appUserGroup',
          },
        }"
      >
        <template #search-table-header="bind">
          <th>Name</th>
          <th>E-mail</th>
          <th>Group</th>
        </template>

        <template #search-table-loop="bind">
          <td>{{ bind.item.name }}</td>
          <td>{{ bind.item.email }}</td>
          <td>{{ bind.item.app_user_group ? bind.item.app_user_group.name : null }}</td>
        </template>
      </app-model-crud>
    </template>
  </nuxt-layout>
</template>