<template>
  <v-container>
    <!-- <v-text-field
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
        ...valid.bind('email')
      }"
    />

    <v-text-field
      v-model="data.age"
      v-bind="{
        label: 'Age',
        ...valid.bind('age')
      }"
    />

    <v-text-field
      v-model="data.phone"
      v-imask="'phone-br'"
      v-bind="{
        label: 'Phone',
        ...valid.bind('phone')
      }"
    />

    <v-text-field
      v-model="data.creditCard"
      v-imask="'0000 0000 0000 0000'"
      v-bind="{
        label: 'Credit card',
        ...valid.bind('creditCard')
      }"
    />

    <v-btn :disabled="valid.invalid()">Send</v-btn> -->

    <pre>{{ socket }}</pre>

    <v-btn @click="socket.trigger('test', 'client-test', { random: Math.round(Math.random()*99999) })">
      Pusher trigger
    </v-btn>
  </v-container>
</template>

<script setup>
  import { ref, onMounted } from 'vue';
  const conf = useRuntimeConfig();

  const data = ref({
    name: '',
    email: '',
    age: '',
    phone: '',
    creditCard: '',
  });

  const valid = useValidate(data, {
    name: ['required'],
    email: ['required', 'email'],
    age: ['required', 'min:18', 'max:30'],
    phone: ['required'],
    creditCard: ['required'],
  });

  import Pusher from 'pusher-js';
  import _ from 'lodash';

  import useWebsocket from '@/composables/useWebsockets';

  const socket = useWebsocket({
    events: [
      ['app_place@created', (data) => {
        // console.log('app_place@created', data);
      }],
      ['app_file@updated', (data) => {
        alert(data.name);
      }],
    ],
  });
</script>