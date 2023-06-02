<template>
  <nuxt-layout name="admin">
    <template #default>
      <app-model-crud
        v-bind="{
          name: 'app_file',
          searchTableSizes: ['*', '100', '100'],
          searchParams: {},
        }"
      >
        <template #search-table-header="bind">
          <th>Name</th>
          <th>Mime</th>
          <th>Type</th>
        </template>

        <template #search-table-loop="bind">
          <td>{{ bind.item.name }}</td>
          <td>{{ bind.item.mime }}</td>
          <td>{{ [
            (bind.item.is_text ? 'Text' : null),  
            (bind.item.is_image ? 'Image' : null),  
          ].filter(val => val).join(', ') }}</td>
        </template>

        <template #edit-fields="bind">
          <v-row>
            <v-col cols="12">
              <v-file-input
                label="File"
                @update:modelValue="bind.edit.data.content=$event[0]||false"
              />
            </v-col>

            <v-col cols="12">
              <v-text-field
                v-model="bind.edit.data.name"
                label="Name"
              />
            </v-col>
          </v-row>
          <pre>{{ bind }}</pre>
        </template>
      </app-model-crud>
    </template>
  </nuxt-layout>
</template>