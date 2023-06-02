<template>
  <form @submit.prevent="search.submit()">
    <v-table class="border" hover density="compact">
      <!-- <colgroup>
        <col width="60px">
        <template v-for="_width in props.searchTableSizes">
          <col :width="_width">
        </template>
        <col width="60px">
      </colgroup> -->

      <thead>
        <tr class="bg-grey-lighten-4">
          <th class="app-model-crud-search-table-fixed-s">
            <v-checkbox v-bind="{ hideDetails: true }" />
          </th>
          <slot name="search-table-header" v-bind="slotBind()"></slot>
          <th class="app-model-crud-search-table-fixed-e"></th>
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
          <td class="app-model-crud-search-table-fixed-s"><v-checkbox v-bind="{ hideDetails: true }" /></td>
          <slot name="search-table-loop" v-bind="slotBind({ item })"></slot>
          <td class="app-model-crud-search-table-fixed-e">
            <v-menu location="start">
              <template #activator="{ props }">
                <v-btn icon="mdi-dots-vertical" size="x-small" flat v-bind="props"></v-btn>
              </template>
  
              <div class="d-flex me-2" style="gap:10px;">
                <v-btn icon="mdi-close" size="x-small" flat color="error"></v-btn>
                <v-btn icon="mdi-pencil" size="x-small" flat :to="`/admin/${props.name}?edit=${item.id}`"></v-btn>
              </div>
            </v-menu>
          </td>
        </tr>
      </tbody>
    </v-table>
  
    <v-pagination
      class="mt-4"
      :length="search.pagination.last_page"
      v-model.number="search.params.page"
      @update:modelValue="search.submit()"
    />
  
    <v-navigation-drawer v-model="drawer.search" location="end">
      <div class="d-flex flex-column pa-2" style="gap:10px;">
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
  
        <slot name="search-fields" v-bind="slotBind()"></slot>
      </div>
    </v-navigation-drawer>
  
    <v-bottom-navigation>
      <v-btn :to="`/admin/${props.name}?edit=new`">
        <v-icon>mdi-plus</v-icon>
        <span>Add</span>
      </v-btn>

      <v-btn @click="drawer.search=true" class="d-md-none">
        <v-icon>mdi-magnify</v-icon>
        <span>Search</span>
      </v-btn>
    </v-bottom-navigation>
  </form>
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

  const drawer = ref({
    search: true,
  });

  const search = ref({
    loading: false,
    params: props.searchParams,
    data: [],
    pagination: {},
    options: {},
    async submit() {
      if (this.loading) return;
      this.loading = true;
      try {
        const params = this.params;
        const { data } = await axios.get(`api://${props.name}`, { params });
        this.data = data.data;
        this.params = data.params;
        this.pagination = data.pagination;
        this.options = data.options;
      } catch(err) {}
      this.loading = false;
    },
  });

  const slotBind = (merge={}) => {
    return {
      search: search.value,
      ...merge
    };
  };

  onMounted(() => {
    search.value.submit();
  });
</script>

<style>
  .app-model-crud-search-table-fixed-s {
    position: sticky;
    left: 0;
    padding: 0 !important;
    min-width: 25px !important;
  }

  .app-model-crud-search-table-fixed-e {
    position: sticky;
    right: 0;
    padding: 0 !important;
  }
</style>