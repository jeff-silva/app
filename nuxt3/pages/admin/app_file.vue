<template>
  <nuxt-layout name="admin">
    <template #default>
      <app-model-crud
        v-bind="{
          name: 'app_file',
          searchTableSizes: ['*', '100px', '100px', '100px'],
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
          <td><a :href="bind.item.url" target="_blank" style="text-decoration:none!important; color:inherit;">{{ bind.item.name }}</a></td>
          <td>{{ $filter.numberFilesize(bind.item.size) }}</td>
          <td>{{ bind.item.mime }}</td>
          <td>{{ [
            (bind.item.is_text ? 'Text' : null),  
            (bind.item.is_image ? 'Image' : null),  
          ].filter(val => val).join(', ') }}</td>
        </template>

        <template #search-fields="bind">
          <div class="bg-grey-lighten-3 pa-2">
            Total size: {{ bind.search.options.total_size }}
          </div>

          <div class="bg-grey-lighten-3 pa-2">
            Search size: {{ bind.search.options.result_size }}
          </div>
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