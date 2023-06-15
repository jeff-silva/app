<template>
  <nuxt-layout name="admin">
    <template #default>
      <app-model-crud
        v-bind="{
          name: 'app_user_notification',
          searchTableSizes: ['200', '*'],
          searchParams: { with: 'appUser' },
          editParams: { with: 'appUser' },
          canCreate: false,
          canDelete: false,
          canSave: false,
        }"
      >
        <template #search-table-header="bind">
          <th>User</th>
          <th>Message</th>
        </template>

        <template #search-table-loop="bind">
          <td>{{ bind.item.app_user.name }}</td>
          <td>{{ bind.item.name }}</td>
        </template>

        <template #edit-fields="bind">
          <div class="pa-3" v-if="bind.edit.data.app_user">
            Message to <strong>{{ bind.edit.data.app_user.name }}</strong>
          </div>
          <pre
            class="pa-3 border"
            style="overflow:auto;"
            v-html="bind.edit.data.text"
          ></pre>
        </template>
      </app-model-crud>
    </template>
  </nuxt-layout>
</template>