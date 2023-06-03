<template>
  <nuxt-layout name="admin">
    <template #default>
      <app-model-crud
        v-bind="{
          name: 'app_file',
          searchTableSizes: ['*', '200px'],
          searchParams: {},
        }"
      >
        <template #search-table-header="bind">
          <th>Name</th>
          <th>Type</th>
        </template>

        <template #search-table-loop="bind">
          <td>
            <div><a :href="bind.item.url" target="_blank" style="text-decoration:none!important; color:inherit;">{{ bind.item.name }}</a></div>
            <small class="d-block text-disabled">
              {{ bind.item.mime }} -
              {{ $filter.numberFilesize(bind.item.size) }}
            </small>
          </td>
          <td>{{ [
            (bind.item.is_text ? 'Text' : null),  
            (bind.item.is_image ? 'Image' : null),  
          ].filter(val => val).join(', ') }}</td>
        </template>

        <template #search-fields="bind">
          <div class="bg-grey-lighten-3 pa-2" v-if="bind.search.options.total_size">
            Total size: {{ $filter.numberFilesize(bind.search.options.total_size) }}
          </div>

          <div class="bg-grey-lighten-3 pa-2" v-if="bind.search.options.result_size">
            Search size: {{ $filter.numberFilesize(bind.search.options.result_size) }}
          </div>
        </template>

        <!-- Edit fields -->
        <template #edit-fields="bind">
          <v-row>
            <v-col cols="12" md="3">
              <div class="bg-grey-lighten-4 rounded pa-3">
                <template v-if="(bind.edit.data.mime || '').startsWith('image/')">
                  <img :src="bind.edit.data.url" alt="" style="width:100%;">
                </template>

                <template v-else>
                  <div class="text-center">
                    <v-avatar color="primary" size="100">
                      <div class="text-h5 text-uppercase">
                        {{ bind.edit.data.ext }}
                      </div>
                    </v-avatar>
                  </div>
                </template>
              </div>
            </v-col>

            <v-col cols="12" md="9">
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
                    :error-messages="bind.edit.error.get('name')"
                  />
                </v-col>
    
                <v-col cols="12">
                  <v-textarea
                    v-model="bind.edit.data.description"
                    label="Description"
                    :error-messages="bind.edit.error.get('description')"
                    auto-grow
                  />
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </template>
      </app-model-crud>
    </template>
  </nuxt-layout>
</template>