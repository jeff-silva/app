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

        <template #edit-fields="bind">
          <v-row>
            <v-col cols="12">
              <v-text-field
                v-model="bind.edit.data.name"
                label="Subject"
              />
            </v-col>
            <v-col cols="12">
              <v-text-field
                v-model="bind.edit.data.email"
                label="E-mail"
              />
            </v-col>
            <v-col cols="12">
              <app-model-select
                name="app_user_group"
                v-model="bind.edit.data.group_id"
                label="User Group"
              />
            </v-col>
          </v-row>
        </template>
      </app-model-crud>
    </template>
  </nuxt-layout>
</template>