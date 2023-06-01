<template>
  <div class="d-flex align-center mb-4">
    <v-spacer />
    <v-text-field
      v-model="search.params.q"
      :hide-details="true"
      append-inner-icon="mdi-magnify"
      density="compact"
      placeholder="Search"
      style="max-width:400px;"
      @keyup.enter="search.submit()"
      @click:append-inner="search.submit()"
    />
  </div>

  <v-table class="border" hover density="compact">
    <colgroup>
      <col width="60">
      <template v-for="_width in props.searchTableSizes">
        <col :width="_width">
      </template>
      <col width="60">
    </colgroup>
    <thead>
      <tr class="bg-grey-lighten-4">
        <th><v-checkbox v-bind="{ hideDetails: true }" /></th>
        <slot name="search-table-header" v-bind="slotBind()"></slot>
        <th></th>
      </tr>
    </thead>

    <tbody>
      <!-- Empty -->
      <tr v-if="!search.loading && search.data.length==0">
        <td colspan="100%">
          <slot name="search-table-empty" v-bind="slotBind({ item })">
            <div class="text-center text-disabled py-5">
              No data found
            </div>
          </slot>
        </td>
      </tr>

      <tr v-for="item in search.data">
        <td><v-checkbox v-bind="{ hideDetails: true }" /></td>
        <slot name="search-table-loop" v-bind="slotBind({ item })"></slot>
        <td>
          <v-menu location="start">
            <template #activator="{ props }">
              <v-btn icon="mdi-dots-vertical" size="x-small" flat v-bind="props"></v-btn>
            </template>

            <div class="d-flex me-2" style="gap:10px;">
              <v-btn icon="mdi-pencil" size="x-small" flat :to="`/admin/${props.name}?edit=${item.id}`"></v-btn>
              <v-btn icon="mdi-close" size="x-small" flat color="error"></v-btn>
            </div>
          </v-menu>
        </td>
      </tr>
    </tbody>
  </v-table>

  <v-pagination
    class="mt-4"
    :length="search.pagination.last_page"
  />

  <v-bottom-navigation>
    <v-btn :to="`/admin/${props.name}?edit=new`">
      <v-icon>mdi-plus</v-icon>
      <span>Add</span>
    </v-btn>
  </v-bottom-navigation>
</template>

<script setup>
  import { ref, defineProps, onMounted } from 'vue';
  import axios from 'axios';

  const route = useRoute();

  const props = defineProps({
    name: {
      type: String,
      default: '',
    },
    searchTableSizes: {
      type: Array,
      default: () => ([]),
    },
    searchParams: {
      type: Array,
      default: () => ({}),
    },
  });

  const search = ref({
    loading: false,
    params: props.searchParams,
    data: [],
    pagination: {},
    async submit() {
      if (this.loading) return;
      this.loading = true;
      try {
        const params = this.params;
        const { data } = await axios.get(`api://${props.name}`, { params });
        this.data = data.data;
        this.params = data.params;
        this.pagination = data.pagination;
      } catch(err) {}
      this.loading = false;
    },
  });

  const slotBind = (merge={}) => {
    return {
      ...merge
    };
  };

  onMounted(() => {
    search.value.submit();
  });
</script>