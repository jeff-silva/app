<template>
  <nuxt-layout name="admin">
    <template #default>
      <app-model-crud
        v-bind="{
          name: 'app_mail_template',
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
                v-model="bind.edit.data.subject"
                label="Subject"
              />
            </v-col>
            <v-col cols="12">
              <v-textarea
                v-model="bind.edit.data.content"
                label="Body"
                :auto-grow="true"
              />
            </v-col>
            <v-col cols="12">
              <template v-for="val in bind.edit.data.vars">
                <v-btn class="me-2 mb-2" size="small" @click="editAddVar(bind, val)">{{ val }}</v-btn>
              </template>
            </v-col>
          </v-row>
        </template>
      </app-model-crud>
    </template>
  </nuxt-layout>
</template>

<script setup>
  const editAddVar = (bind, strvar) => {
    bind.edit.data.content += strvar;
  };
</script>