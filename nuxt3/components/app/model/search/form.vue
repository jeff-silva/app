<template>
  <form @submit.prevent>
    <div class="pa-3">
      <v-text-field label="Busca" />
      <v-select label="Ordem" :items="[
        {value:'id:asc', title:'Primeiro &raquo; Último'},
        {value:'id:desc', title:'Último &raquo; Primeiro'},
        {value:'name:asc', title:'Nome crescente'},
        {value:'name:desc', title:'Nome decrescente'},
      ]" />
    </div>
    <v-divider />
    <div class="d-flex flex-column pa-3" style="gap:10px;">
      <v-btn type="submit" color="primary" block>Buscar</v-btn>
      <slot name="form-actions" />
    </div>
  </form>
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