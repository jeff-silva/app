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
        VCheckbox: { disabled: edit.loading },
        VCombobox: { disabled: edit.loading },
      }"
    >
      <v-snackbar
        v-model="edit.error.message"
        v-if="edit.error.message"
        color="error"
        :close-delay="99000"
        location="top"
      >
        {{ edit.error.message }}
      </v-snackbar>
      <slot name="edit-fields" v-bind="slotBind()"></slot>
    </v-defaults-provider>
  </form>

  <!-- Search -->
  <template v-if="!route.query.edit">
    <v-snackbar
      v-model="search.error.message"
      v-if="search.error.message"
      color="error"
      :close-delay="99000"
      location="top"
    >
      {{ search.error.message }}
    </v-snackbar>

    
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
              <v-defaults-provider :defaults="searchActions">
                <v-menu location="start">
                  <template #activator="{ props }">
                    <v-btn icon="mdi-dots-vertical" v-bind="props"></v-btn>
                  </template>
      
                  <div class="d-flex me-2" style="gap:8px;">
                    <v-defaults-provider :defaults="searchActions">
                      <v-btn
                        icon="mdi-close"
                        color="error"
                        v-if="props.canDelete"
                        @click="dialog.delete=true"
                      />
                      <v-btn
                        icon="mdi-pencil"
                        :to="`/admin/${props.name}?edit=${item.id}`"
                        @click="edit.data=item"
                      />
                    </v-defaults-provider>
                  </div>
                </v-menu>
              </v-defaults-provider>
            </td>
          </tr>
        </tbody>
      </v-table>

      <br>
    
      <v-navigation-drawer
        v-model="dialog.search"
        location="end"
        style="max-width:calc(100vw - 60px);"
        width="400"
      >
        <div class="d-flex flex-column pa-3" style="gap:15px;">
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

          <v-divider class="mx-n3 my-3" />

          <v-select
            label="Per page"
            v-model="search.params.per_page"
            :items="[ 5, 10, 25, 50, 100 ]"
            :hide-details="true"
            density="compact"
            @update:modelValue="search.submit()"
          />

          <v-pagination
            size="small"
            :length="search.pagination.last_page"
            v-model.number="search.params.page"
            @update:modelValue="search.submit()"
          />

          <div class="text-center">
            {{ search.pagination.total }} registers
          </div>
        </div>
      </v-navigation-drawer>
    </form>
  </template>


  <!-- Dialogs -->
  <!-- Dialog delete -->
  <v-dialog v-model="dialog.delete">
    <v-card class="mx-auto" style="width:300px; max-width:90vw;">
      <v-card-text>
        Are you sure you want to delete this item?
      </v-card-text>
      <v-divider />
      <v-card-actions>
        <v-spacer />
        <v-btn color="red">Delete</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  
  <!-- Actions -->
  <v-bottom-navigation>
    <!-- Edit -->
    <template v-if="route.query.edit">
      <v-btn
        v-if="props.canDelete"
        class="text-error"
        @click="dialog.delete=true"
      >
        <v-icon>mdi-delete</v-icon>
        <span>Delete</span>
      </v-btn>

      <v-btn
        :to="`/admin/${props.name}`"
      >
        <v-icon>mdi-arrow-left</v-icon>
        <span>Cancel</span>
      </v-btn>
  
      <v-btn
        :loading="edit.saving"
        @click="edit.save()"
        class="text-primary"
        v-if="props.canSave"
      >
        <v-icon>mdi-content-save-outline</v-icon>
        <span>Save</span>
      </v-btn>
    </template>

    <!-- Search -->
    <template v-if="!route.query.edit">
      <v-btn
        :to="`/admin/${props.name}?edit=new`"
        v-if="props.canCreate"
      >
        <v-icon>mdi-plus</v-icon>
        <span>Create</span>
      </v-btn>

      <v-btn @click="dialog.search=true" class="d-lg-none">
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
    canSave: {
      type: Boolean,
      default: true,
    },
    canCreate: {
      type: Boolean,
      default: true,
    },
    canDelete: {
      type: Boolean,
      default: true,
    },
  });

  const searchActions = {
    VBtn: {
      size: 'x-small',
      flat: true,
    },
  };

  const search = ref({
    loading: false,
    params: props.searchParams,
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
          const params = { ...props.editParams, ...this.params };
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
          const post = { ...this.data };
          for(let attr in post) {
            if (Array.isArray(post[attr]) && post[attr].length==0) {
              post[attr] = '[]';
            }
          }
          const { data } = await axios.post(`api://${props.name}/`, post);
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

  const dialog = ref({
    search: breakpoints.md,
    delete: false,
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
    padding: 0 0 0 0 !important;
    min-width: 25px !important;
  }

  .app-model-crud-search-table-fixed-e {
    position: sticky;
    right: 0;
    padding: 0 5px 0 0 !important;
  }
</style>