<template>
  <div>
    <app-form v-model="post" method="post" action="/api/files/save" @success="onSuccess($event)">
      <v-text-field label="Nome" v-model="post.name" />
      <v-file-input label="Arquivo" v-bind="{showSize:true, dirty:true}" @update:modelValue="post.file=$event[0]||false" />
      <v-text-field label="Pasta" v-model="post.folder" />
      
      <app-actions>
        <v-btn type="submit">Enviar</v-btn>
      </app-actions>
    </app-form>

    <app-model-search model="files">
      <template #table-header>
        <th>Nome</th>
        <th>URL</th>
      </template>

      <template #table-row="row">
        <td>{{ row.item.name }}</td>
        <td>{{ row.item.url }}</td>
      </template>
    </app-model-search>
    <!-- <v-row>
      <v-col cols="6">
        <pre style="overflow:auto;">post: {{ post }}</pre>
      </v-col>
      <v-col cols="6">
        <v-btn :loading="files.loading" @click="files.submit()">Buscar novamente</v-btn>
        <v-table>
          <thead>
            <tr>
              <th>Nome</th>
              <th>URL</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="f in files.resp.data" :key="f.id">
              <td>{{ f.name }}</td>
              <td>{{ f.url }}</td>
            </tr>
          </tbody>
        </v-table>
      </v-col>
    </v-row> -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      post: {
        name: 'Descrição do arquivo',
      },
      // files: useAxios({
      //   method: 'get',
      //   url: '/api/files/search',
      //   params: {
      //     order: 'id:desc',
      //   },
      //   autoSubmit: true,
      // }),
    };
  },

  methods: {
    onSuccess(ev) {
      console.log(ev);
      // this.files.submit();
    },
  },
};
</script>