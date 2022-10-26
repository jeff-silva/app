<template>
  <div>
    <app-form v-model="post" method="post" action="/api/files/save">
      <v-text-field label="Nome" v-model="post.name" />
      <v-file-input label="Arquivo" v-bind="{showSize:true, dirty:true}" @update:modelValue="post.file=$event[0]||false" />
      
      <app-actions>
        <v-btn type="submit">Enviar</v-btn>
      </app-actions>
    </app-form>
    <pre>post: {{ post }}</pre>
  </div>
</template>

<script>
export default {
  data() {
    return {
      // axios: useAxios({
      //   method: 'get',
      //   url: '/api/app/test',
      //   submit: true,
      // }),
      post: {
        name: 'John',
      },
    };
  },

  methods: {
    test() {
      alert('aaa');
    },
  },
};
</script>