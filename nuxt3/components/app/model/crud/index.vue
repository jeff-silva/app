<template>
  <template v-if="route.query.edit">
    <app-model-crud-edit v-bind="props">
      <template #edit-fields="bind">
        <slot name="edit-fields" v-bind="bind"></slot>
      </template>
    </app-model-crud-edit>
  </template>

  <template v-if="!route.query.edit">
    <app-model-crud-search v-bind="props">
      <template #search-table-header="bind">
        <slot name="search-table-header" v-bind="bind"></slot>
      </template>
      <template #search-table-loop="bind">
        <slot name="search-table-loop" v-bind="bind"></slot>
      </template>
    </app-model-crud-search>
  </template>
  
  <!-- <pre>{{ { props, route } }}</pre> -->
</template>

<script setup>
  import { defineProps } from 'vue';

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
      type: Object,
      default: () => ({}),
    },
    editParams: {
      type: Object,
      default: () => ({}),
    },
  });
</script>