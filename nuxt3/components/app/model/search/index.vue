<template>
  <v-row>
    <v-col cols="12" md="3">
      <div class="d-md-none">
        <v-btn type="button" block>Buscar</v-btn>
      </div>
      <app-model-search-form class="d-none d-md-block">
        <!-- <slot></slot> -->
      </app-model-search-form>
    </v-col>
    <v-col cols="12" md="9">
      <app-model-search-result>
        <template #table-header>
          <slot name="table-header"></slot>
        </template>

        <template #table-row="row">
          <slot name="table-row" v-bind="row"></slot>
        </template>
      </app-model-search-result>
    </v-col>
  </v-row>
</template>