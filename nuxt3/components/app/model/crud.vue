<template>

  <!-- Edit -->
  <form
    v-if="route.query.edit"
    @submit.prevent="edit.save()"
  >
    <v-defaults-provider
      :defaults="{
        VTextField: { disabled: edit.loading },
        VTextarea: { disabled: edit.loading },
        VFileInput: { disabled: edit.loading },
        VSelect: { disabled: edit.loading },
      }"
    >
      <slot name="edit-fields" v-bind="slotBind()"></slot>
    </v-defaults-provider>
  </form>

  <!-- Search -->
  <template v-if="!route.query.edit">
    <div style="height:4px;">
      <v-progress-linear indeterminate v-if="search.loading" />
    </div>

    <form @submit.prevent="search.submit()">
      <v-table class="border" hover density="compact">
        <colgroup>
          <col width="0">
          <template v-for="_width in props.searchTableSizes">
            <col :width="_width">
          </template>
          <col width="0">
        </colgroup>
  
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

          <!-- Loading -->
          <tr v-if="search.loading && search.data.length==0">
            <td colspan="100%">
              <slot name="search-table-loading" v-bind="slotBind()">
                <div class="d-flex align-center justify-center text-disabled" style="height:100px;">
                  <v-progress-circular indeterminate size="20" />
                  <span class="ms-2">Loading</span>
                </div>
              </slot>
            </td>
          </tr>

          <!-- Empty -->
          <tr v-if="!search.loading && search.data.length==0">
            <td colspan="100%">
              <slot name="search-table-empty" v-bind="slotBind()">
                <div class="d-flex align-center justify-center text-disabled" style="height:100px;">
                  <v-icon>mdi-alert-circle-outline</v-icon>
                  <span class="ms-2">No data found</span>
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
                  <v-btn icon="mdi-pencil" size="x-small" flat :to="`/admin/${props.name}?edit=${item.id}`" @click="edit.data=item"></v-btn>
                </div>
              </v-menu>
            </td>
          </tr>
        </tbody>
      </v-table>

      <br>

      <v-row class="align-center">
        <v-col cols="12" sm="4" class="text-center text-sm-left">
          {{ search.pagination.total }} registers
        </v-col>
        <v-col cols="12" sm="4">
          <v-pagination
            size="small"
            :length="search.pagination.last_page"
            v-model.number="search.params.page"
            @update:modelValue="search.submit()"
          />
        </v-col>
        <v-col cols="12" sm="4">
          <v-select
            label="Per page"
            v-model="search.params.per_page"
            :items="[ 5, 10, 25, 50, 100 ]"
            :hide-details="true"
            density="compact"
            @update:modelValue="search.submit()"
          />
        </v-col>
      </v-row>
    
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
    </form>
  </template>

  
  <!-- Actions -->
  <v-bottom-navigation>
    <!-- Edit -->
    <template v-if="route.query.edit">
      <v-btn :to="`/admin/${props.name}`">
        <v-icon>mdi-close</v-icon>
        <span>Cancel</span>
      </v-btn>
  
      <v-btn :loading="edit.saving" @click="edit.save()">
        <v-icon>mdi-content-save-outline</v-icon>
        <span>Save</span>
      </v-btn>
    </template>

    <!-- Search -->
    <template v-if="!route.query.edit">
      <v-btn :to="`/admin/${props.name}?edit=new`">
        <v-icon>mdi-plus</v-icon>
        <span>Add</span>
      </v-btn>

      <v-btn @click="drawer.search=true" class="d-lg-none">
        <v-icon>mdi-magnify</v-icon>
        <span>Search</span>
      </v-btn>
    </template>
  </v-bottom-navigation>
</template>

<script setup>
  import { watch, onMounted, defineProps, defineEmits } from 'vue';
  import axios from 'axios';

  import { breakpointsVuetify, useBreakpoints } from '@vueuse/core';
  const breakpoints = useBreakpoints(breakpointsVuetify);

  import useValidate from '@/composables/useValidate';

  const route = useRoute();
  const router = useRouter();

  const emit = defineEmits([
    'update:modelValue',
    'ready',
    'switch',
    'search:loaded',
    'edit:loaded',
    'edit:saved',
  ]);

  const props = defineProps({
    modelValue: {
      type: [ Boolean, Number, String, Array, Object ],
      default: false,
    },
    name: {
      type: String,
      default: '',
    },
    searchTableSizes: {
      type: Array,
      default: () => ([]),
    },
    searchParams: {
      type: Object,
      default: () => ({}),
    },
    editParams: {
      type: Object,
      default: () => ({}),
    },
  });

  const search = ref({
    loading: false,
    params: {},
    data: [],
    pagination: {},
    options: {},
    error: useValidate(),
    async submit() {
      this.error.clear();
      if (this.loading) {
        clearTimeout(this.loading);
        this.loading = false;
      }

      this.loading = setTimeout(async () => {
        try {
          const params = this.params;
          const { data } = await axios.get(`api://${props.name}`, { params });
          this.data = data.data;
          this.params = data.params;
          this.options = data.options;
          this.pagination = data.pagination;
          emit('search:loaded', slotBind());
        } catch(err) {
          if (!err.response) return;
          this.error.setData(err.response.data);
        }

        this.loading = false;
      }, 1000);
    },
  });

  const edit = ref({
    loading: false,
    saving: false,
    params: {},
    data: {},
    options: {},
    error: useValidate(),
    async load() {
      this.error.clear();
      this.data = {};

      const id = parseInt(route.query.edit || null) || null;
      if (!id) return;
      this.data = {};

      if (this.loading) {
        clearTimeout(this.loading);
        this.loading = false;
      }

      this.loading = setTimeout(async () => {
        try {
          const params = this.params;
          const { data } = await axios.get(`api://${props.name}/${id}`, { params });
          this.data = data.data;
          this.options = data.options;
          emit('edit:loaded', slotBind());
        } catch(err) {
          if (!err.response) return;
          this.error.setData(err.response.data);
        }
        this.loading = false;
      }, 1000);
    },
    async save() {
      this.error.clear();
      if (this.saving) {
        clearTimeout(this.saving);
        this.saving = false;
      }

      this.saving = setTimeout(async () => {
        try {
          const { data } = await axios.post(`api://${props.name}/`, this.data);
          // this.data = data.data;
          // this.options = data.options;
          search.value.submit();
          emit('edit:saved', slotBind());
          router.push(`/admin/${props.name}`);
        } catch(err) {
          if (!err.response) return;
          this.error.setData(err.response.data);
        }
        this.saving = false;
      }, 1000);
    },
  });

  const drawer = ref({
    search: breakpoints.md,
  });

  const slotBind = (merge={}) => {
    return {
      ...merge,
      search: search.value,
      edit: edit.value,
    };
  };

  watch([ route ], ([ routeNew ]) => {
    if (routeNew.query.edit) edit.value.load();
    emit('switch', slotBind({ edit: routeNew.query.edit || false }));
  });

  onMounted(() => {
    search.value.submit();
    edit.value.load();
    emit('update:modelValue', slotBind());
    emit('ready', slotBind());
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