<template>
  <div>
    <form @submit.prevent="test()">
      <v-text-field label="Nome" />
      
      <app-actions>
        <v-btn type="submit">Enviar</v-btn>
      </app-actions>
    </form>
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
    };
  },

  methods: {
    test() {
      alert('aaa');
    },
  },
};
</script>