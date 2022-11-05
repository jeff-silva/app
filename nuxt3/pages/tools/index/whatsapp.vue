<template>
  <div>
    <v-text-field label="Número" v-model="number" v-bind="{hideDetails:true}">
      <template #append>
        <a :href="numberLink" target="_blank" v-if="numberLink">
          {{ numberLink }}
        </a>
      </template>
    </v-text-field>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        number: '',
      };
    },
    computed: {
      numberLink() {
        const number = this.number.replace(/[^0-9]/g, '');
        return number? `https://wa.me/${number}`: false;
      },
    },
  };
</script>