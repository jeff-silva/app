<template>
  <v-row>
    <v-col cols="12" md="3">
      <div class="d-md-none">
        <v-btn type="button" block>Buscar</v-btn>
      </div>
      <app-model-search-form  ref="form" class="d-none d-md-block" :model="model">
        <!-- <slot></slot> -->
      </app-model-search-form>
    </v-col>
    <v-col cols="12" md="9">
      <app-model-search-table ref="table" :model="model">
        <template #table-header>
          <slot name="table-header"></slot>
        </template>

        <template #table-row="row">
          <slot name="table-row" v-bind="row"></slot>
        </template>
      </app-model-search-table>
    </v-col>
  </v-row>
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