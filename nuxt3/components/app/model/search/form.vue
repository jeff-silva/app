<template>
  <form @submit.prevent>
    <v-text-field label="Busca" />
    <v-select label="Ordem" :items="[
      {value:'id:asc', title:'Primeiro &raquo; Último'},
      {value:'id:desc', title:'Último &raquo; Primeiro'},
      {value:'name:asc', title:'Nome crescente'},
      {value:'name:desc', title:'Nome decrescente'},
    ]" />
    <v-btn type="submit" block>Buscar</v-btn>
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