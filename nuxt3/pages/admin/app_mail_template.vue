<template>
  <nuxt-layout name="admin">
    <template #default>
      <app-model-crud
        v-model="crud"
        v-bind="{
          name: 'app_mail_template',
          searchTableSizes: ['*'],
          canCreate: false,
          canDelete: false,
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
              <app-html
                v-model="bind.edit.data.body"
              />
            </v-col>
            <v-col cols="12">
              <template v-for="val in bind.edit.data.vars">
                <v-btn class="me-2 mb-2" size="small" @click="editAddVar(bind, val)">{{ val }}</v-btn>
              </template>
            </v-col>

            <v-col cols="12">
              <v-btn
                color="primary"
                @click="test.submit()"
                :loading="test.loading"
              >Test</v-btn>

              <v-dialog v-model="test.response">
                <v-card width="600" class="mx-auto" style="max-width:90vh;">
                  <v-card-title>Subject: {{ test.response.subject }}</v-card-title>
                  <v-divider />
                  <v-card-text>
                    <div v-html="test.response.body"></div>
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer />
                    <v-btn @click="test.response = false">Ok</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-col>
          </v-row>
        </template>
      </app-model-crud>
    </template>
  </nuxt-layout>
</template>

<script setup>
  import { ref } from 'vue';
  import axios from 'axios';

  const crud = ref(null);

  const editAddVar = (bind, strvar) => {
    bind.edit.data.body += strvar;
  };

  const test = ref({
    loading: false,
    response: false,
    async submit() {
      this.loading = true;
      try {
        const { data } = await axios.post('api://app_mail_template/test', crud.value.edit.data);
        this.response = data;
      } catch(err) {}
      this.loading = false;
    },
  });
</script>