<template>
  <div>
    <v-container>
      <app-model-search-table ref="table" :model="model">
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
      <app-model-search-form  ref="form" :model="model">
        <template #form-actions>
          <v-btn :to="`/admin/${model}/new`">Criar</v-btn>
        </template>
      </app-model-search-form>
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
    },
  };
</script>