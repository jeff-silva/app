<template>
  <div>
    <v-table v-bind="{fixedHeader:true, density:'compact'}">
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
        <tr v-for="(item, index) in items">
          <td width="50px">
            <v-checkbox :hide-details="true" />
          </td>
          <slot name="table-row" v-bind="slotBind(item)">
            <td>&lt;template #table-row="row"&gt;&lt;/template&gt;</td>
          </slot>
          <td width="30px">
            <app-over type="menu" :overBind="{location:'start'}">
              <template #activator="{ props }">
                <v-btn icon="mdi-menu" size="x-small" v-bind="props"></v-btn>
                <!-- <v-btn icon="mdi-menu" size="x-small" @click="$log(props)"></v-btn> -->
              </template>

              Hello
            </app-over>
          </td>
        </tr>
      </tbody>
    </v-table>
    <pre>items: {{ items }}</pre>
    <pre>search: {{ search }}</pre>
  </div>
</template>

<script>
export default {
  props: {
    model: {
      type: String,
      default: 'users',
    },
  },

  methods: {
    slotBind(merge = {}) {
      return { ...merge };
    },

    submit() {
      alert('submit');
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
        params: {},
        autoSubmit: true,
        resp: {
          data: [],
        },
      }),
    };
  },
};
</script>