<template>
  <nuxt-layout name="admin">
    <template #default>
      <app-model-crud
        v-model="crud"
        v-bind="{
          name: 'app_user_group',
          searchTableSizes: ['*'],
        }"
      >
        <template #search-table-header="bind">
          <th>Name</th>
        </template>

        <template #search-table-loop="bind">
          <td>{{ bind.item.name }}</td>
        </template>

        <template #edit-fields="bind">
          <v-defaults-provider
            :defaults="{
              VCheckbox: { disabled: crud && crud.edit.data.id==1 },
            }"
          >
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="bind.edit.data.name"
                  label="Subject"
                />
              </v-col>

              <v-col cols="12">
                <v-row>
                  <v-col cols="12">
                    <v-checkbox
                      label="Select all"
                      :hide-details="true"
                      @click="selectAll($event.target.checked)"
                      v-if="crud && crud.edit.data.id!=1"
                    />
                  </v-col>
                  <template v-for="p in permission.data">
                    <v-col cols="12" md="3">
                      <v-checkbox
                        v-model="bind.edit.data.permissions"
                        :value="p.id"
                        :label="p.name"
                        :hide-details="true"
                      />
                    </v-col>
                  </template>
                </v-row>
              </v-col>
            </v-row>
          </v-defaults-provider>
        </template>
      </app-model-crud>
    </template>
  </nuxt-layout>
</template>

<script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';

  const crud = ref(null);

  const permission = ref({
    loading: false,
    data: [],
    async load() {
      if (this.loading) {
        clearTimeout(this.loading);
        this.loading = false;
      }

      this.loading = setTimeout(async () => {
        try {
          const { data } = await axios.get('api://app_user_group/permissions');
          this.data = data;
        } catch(err) {}
      }, 1000);
    },
  });

  const selectAll = (checked) => {
    if (checked) {
      return crud.value.edit.data.permissions = permission.value.data.map(item => item.id);
    }
    crud.value.edit.data.permissions = [];
  };

  onMounted(() => {
    permission.value.load();
  });
</script>