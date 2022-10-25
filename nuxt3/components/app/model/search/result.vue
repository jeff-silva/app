<template>
  <div>
    <v-table v-bind="{fixedHeader:true, density:'compact'}">
      <thead>
        <tr>
          <th>
            <v-checkbox :hide-details="true" />
          </th>
          <slot name="table-header"></slot>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in items">
          <td width="50px">
            <v-checkbox :hide-details="true" />
          </td>
          <slot name="table-row" v-bind="slotBind({ selected: itemSelected(item), index, item })"></slot>
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
  </div>
</template>

<script>
export default {
  methods: {
    slotBind(merge = {}) {
      return {
        ...merge
      };
    },

    itemSelected(item) {
      return false;
    },
  },

  computed: {
    items() {
      let items = [];
      for(let i=0; i<=10; i++) {
        items.push({ id: i, name: `user ${i}` });
      }
      return items;
    },
  },
};
</script>