<template>
  <div>
    <v-table v-bind="{fixedHeader:true, density:'compact'}">
      <colgroup>
        <col width="50px">
        <col v-for="s in tableSizes" :width="s">
        <col width="50px">
      </colgroup>
      <thead>
        <tr>
          <th>
            <v-checkbox :hide-details="true" />
          </th>
          <slot name="table-header">
            <th>&lt;template #table-header&gt;&lt;/template&gt;</th>
          </slot>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="search.resp.data.length==0 && !search.loading">
          <td colspan="100%" class="text-center py-5">
            <slot name="table-empty">
              Nenhum item encontrado
            </slot>
          </td>
        </tr>
        <tr v-for="(item, index) in items">
          <td width="50px">
            <v-checkbox :hide-details="true" />
          </td>
          <slot name="table-row" v-bind="slotBind(item)">
            <td>&lt;template #table-row="row"&gt;&lt;/template&gt;</td>
          </slot>
          <td width="30px">
            <app-over type="side" :overBind="{location:'start'}">
              <template #activator="{ props }">
                <v-btn icon="mdi-menu" flat v-bind="props"></v-btn>
              </template>
              
              <v-btn icon="mdi-pencil" color="primary" :to="`/admin/${model}/${item.item.id}`"></v-btn>
              <v-btn icon="mdi-delete" color="error"></v-btn>
              <slot name="table-actions" v-bind="slotBind(item)"></slot>
            </app-over>
          </td>
        </tr>
      </tbody>
    </v-table>
    <v-divider class="mb-4" />
    <v-pagination
      v-model="search.params.page"
      density="comfortable"
      :length="search.resp.last_page"
      :total-visible="10"
      @update:modelValue="$debounce('app-model-search-table-page', 1000, search.submit)"
    ></v-pagination>
    <!-- <pre>items: {{ items }}</pre> -->
    <!-- <pre>search: {{ search }}</pre> -->
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
    params: {
      type: Object,
      default: () => ({}),
    },
  },

  methods: {
    slotBind(merge = {}) {
      return { ...merge };
    },

    submit() {
      alert('submit');
    },

    paramsMerge(merge={}) {
      const defs = {
        q: '',
        page: 1,
        per_page: 10,
        order: 'id:desc',
      };
      return { ...defs, ...this.params, ...merge };
    },
  },

  computed: {
    items() {
      return this.search.resp.data.map((item, index) => {
        const selected = false;
        return { index, selected, item };
      });
    },
  },

  data() {
    return {
      search: useAxios({
        method: 'get',
        url: `/api/${this.model}/search`,
        params: this.paramsMerge(),
        autoSubmit: true,
        resp: {
          last_page: 1,
          data: [],
        },
      }),
    };
  },
};
</script>