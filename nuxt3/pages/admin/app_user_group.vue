<template>
  <nuxt-layout name="admin">
    <template #default>
      <app-model-crud
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
          <v-row>
            <v-col cols="12">
              <v-text-field
                v-model="bind.edit.data.name"
                label="Subject"
              />
            </v-col>

            <v-col cols="12">
              <v-select
                v-model="bind.edit.data.permissions"
                label="Permissions"
                :items="permission.data"
                item-value="id"
                item-title="name"
                :multiple="true"
                :disabled="bind.edit.data.id && bind.edit.data.id==1"
              />
            </v-col>
          </v-row>
        </template>
      </app-model-crud>
    </template>
  </nuxt-layout>
</template>

<script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';

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

  onMounted(() => {
    permission.value.load();
  });
</script>