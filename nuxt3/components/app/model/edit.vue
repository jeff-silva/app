<template>
  <app-form method="post" :action="`/api/${model}/save`" v-model="post">
    <slot name="edit-fields" v-bind="slotBind({ post })"></slot>
    
    <v-navigation-drawer location="end">
      <div class="d-flex flex-column pa-3" style="gap:10px;">
        <v-btn color="primary" type="submit">Salvar</v-btn>
        <v-btn color="error">Deletar</v-btn>
        <v-btn :to="`/admin/${model}`">Voltar</v-btn>
      </div>
    </v-navigation-drawer>
  
    <pre>post: {{ post }}</pre>
  </app-form>
</template>

<script>
  export default {
    props: {
      model: {
        type: String,
        default: 'users',
      },
    },

    data() {
      return {
        post: {},
      };
    },

    methods: {
      slotBind(merge = {}) {
        return { ...merge };
      },

      async load() {
        if (! +this.$route.params.id) return;
        const { data } = await this.$axios.get(`/api/${this.model}/find/${this.$route.params.id}`);
        this.post = data;
      },
    },

    async mounted() {
      await this.load();
    },
  };
</script>