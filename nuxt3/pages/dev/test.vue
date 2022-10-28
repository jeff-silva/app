<template>
  <div>
    <!-- <app-form v-model="post" method="post" action="/api/files/save" @success="onSuccess($event)">
      <v-text-field label="Nome" v-model="post.name" />
      <v-file-input label="Arquivo" v-bind="{showSize:true, dirty:true}" @update:modelValue="post.file=$event[0]||false" />
      <v-text-field label="Pasta" v-model="post.folder" />
      
      <app-actions>
        <v-btn type="submit">Enviar</v-btn>
      </app-actions>
    </app-form> -->

    <!-- <app-model-search model="files">
      <template #table-header>
        <th>Nome</th>
        <th>URL</th>
      </template>

      <template #table-row="row">
        <td>{{ row.item.name }}</td>
        <td>{{ row.item.url }}</td>
      </template>
    </app-model-search> -->

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

    <v-row>
      <v-col cols="4">
        <v-text-field
          label="page"
          v-model.number="graphql.data.page"
          type="number"
          @input="graphql.submit()"
        />
      </v-col>
      <v-col cols="4">
        <v-text-field
          label="first"
          v-model.number="graphql.data.first"
          type="number"
          @input="graphql.submit()"
        />
      </v-col>
      <v-col cols="4">
        <v-text-field
          label="userId"
          v-model.number="graphql.data.userId"
          type="number"
          @input="graphql.submit()"
        />
      </v-col>
    </v-row>

    <app-dd>$data: {{ $data }}</app-dd>
  </div>
</template>

<script>
import useGraphql from '@/composables/useGraphql';

export default {
  data() {
    return {
      post: {
        name: 'Descrição do arquivo',
      },
      graphql: useGraphql({
        autoSubmit: true,
        data: {
          userId: 2,
          page: 1,
          first: 5,
        },
        query: (data) => `{
          usersFind(id: ${data.userId}) { id name email }
          usersSearch(page:${data.page}, first:${data.first}) {
            paginatorInfo { currentPage total perPage lastPage hasMorePages }
            data { id name email }
          }
        }`,
      }),
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

    // async graphql(query) {
    //   const { data } = await this.$axios({
    //     url: '/graphql',
    //     method: 'post',
    //     data: { query },
    //   });
      
    //   console.log(query, data.data);
    // },
  },

  mounted() {
    // this.graphql(`{
    //   users(page:1, first:3) {
    //     paginatorInfo {
    //       currentPage
    //       total
    //       perPage
    //       lastPage
    //       hasMorePages
    //     }
    //     data {
    //       id
    //       name
    //       email
    //     }
    //   }
    // }`);
  },
};
</script>