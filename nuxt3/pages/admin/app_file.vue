<template>
  <nuxt-layout name="admin">
    <template #default>
      <app-model-crud
        v-bind="{
          name: 'app_file',
          searchTableSizes: ['*', '100', '100', '100'],
          searchParams: {},
        }"
      >
        <template #search-table-header="bind">
          <th>Name</th>
          <th>Size</th>
          <th>Mime</th>
          <th>Type</th>
        </template>

        <template #search-table-loop="bind">
          <td><a :href="bind.item.url" target="_blank">{{ bind.item.name }}</a></td>
          <td>{{ bind.item.size }}b</td>
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

            <!-- <v-col cols="12" v-if="bind.edit.data.is_text">
              <v-textarea
                v-model="bind.edit.data.content"
                label="Content"
              />
            </v-col> -->
          </v-row>
          <pre>{{ bind }}</pre>
        </template>
      </app-model-crud>
    </template>
  </nuxt-layout>
</template>