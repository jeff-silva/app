<template>
  <div class="app-model-search">
    <v-container>
      <app-model-search-table
        ref="table"
        :model="model"
        v-bind="{ tableSizes }"
        @on-response="tableOnResponse($event)"
      >
        <template #table-header>
          <slot name="table-header"></slot>
        </template>

        <template #table-row="row">
          <slot name="table-row" v-bind="row"></slot>
        </template>

        <template #table-actions="row">
          <slot name="table-actions" v-bind="row"></slot>
        </template>
      </app-model-search-table>
    </v-container>

    <v-navigation-drawer location="end">
      <form @submit.prevent="submit()">
        <div class="pa-3">
          <v-text-field label="Busca" />
          <v-select label="Ordem" :items="[
            {value:'id:asc', title:'Primeiro &raquo; Último'},
            {value:'id:desc', title:'Último &raquo; Primeiro'},
            {value:'name:asc', title:'Nome crescente'},
            {value:'name:desc', title:'Nome decrescente'},
          ]" />
        </div>
        <v-divider />
        <div class="app-model-search-form-actions d-flex flex-column pa-3">
          <v-btn type="submit" color="primary" block>
            <v-icon icon="mdi-magnify" /> Buscar
          </v-btn>
          <slot name="form-actions" />
        </div>
      </form>
    </v-navigation-drawer>
  </div>
</template>

<script>
  export default {
    props: {
      model: {
        type: String,
        default: 'users',
      },
      tableSizes: {
        type: Array,
        default: () => ([]),
      },
      changeUrl: {
        type: Boolean,
        default: true,
      },
    },

    methods: {
      submit() {
        this.$refs.table.submit();
      },

      tableOnResponse(resp) {
        // this.$router.push({
        //   query: resp.data.params
        // });
      },

      tableSetParams() {
        this.$refs.table.search.params = this.$refs.table.paramsMerge(this.$route.query);
        console.log(this.$refs.table.search.params);
      },
    },

    watch: {
      $route(to, from) {
        this.tableSetParams();
      },
    },

    mounted() {
      this.tableSetParams();
    },
  };
</script>

<style>
  .app-model-search-form-actions {
    gap: 10px;
  }
  .app-model-search-form-actions .v-btn .v-icon {
    margin: 0 8px;
  }
</style>