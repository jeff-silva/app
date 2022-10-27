<template>
  <div>
    <v-container>
      Hello
    </v-container>

    <v-navigation-drawer location="end">
      <div class="d-flex flex-column pa-3" style="gap:10px;">
        <v-btn color="primary">Salvar</v-btn>
        <v-btn color="error">Deletar</v-btn>
        <v-btn :to="`/admin/${model}`">Voltar</v-btn>
      </div>
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