<template>
  <v-container>
    <v-text-field
      v-model="data.name"
      v-bind="{
        label: 'Name',
        ...valid.bind('name')
      }"
    />

    <v-text-field
      v-model="data.email"
      v-bind="{
        label: 'Email',
        ...valid.bind('email', { presence: { allowEmpty: false }, email: true })
      }"
    />

    <v-text-field
      v-model="data.age"
      v-bind="{
        label: 'Age',
        ...valid.bind('age', ['required', 'min:18'])
      }"
    />

    <v-btn :active="valid.invalid()">Send</v-btn>

    <pre>valid: {{ valid.valid() }}</pre>
    <pre>data: {{ data }}</pre>
    <pre>valid: {{ valid }}</pre>
  </v-container>
</template>

<script setup>
  import { ref } from 'vue';

  const data = ref({
    name: '',
    email: '',
    age: '',
  });

  const valid = useValidate(data, {
    name: {
      presence: {
        allowEmpty: false,
      },
    },
  });
</script>