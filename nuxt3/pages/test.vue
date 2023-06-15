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

  const useWebsocket = (params={}) => {
    params = _.merge({
      events: {},
    }, params);

    const pusherSettings = {
      cluster: conf.public.PUSHER_APP_CLUSTER,
      wsHost: conf.public.PUSHER_HOST,
      wsPort: conf.public.PUSHER_PORT,
      wssPort: conf.public.PUSHER_PORT,
      encrypted: conf.public.PUSHER_SCHEME=='https',
      forceTLS: conf.public.PUSHER_SCHEME=='https',
      disableStats: true,
      enabledTransports: ['ws', 'wss'],
      scheme: conf.public.PUSHER_SCHEME === 'https' ? 'wss' : 'ws',
    };

    console.clear();
    console.log(JSON.stringify(conf.public, null, 2));
    console.log(JSON.stringify(pusherSettings, null, 2));

    const pusher = new Pusher(conf.public.PUSHER_APP_KEY, pusherSettings);
    const channel = pusher.subscribe('test');

    ['client-test', 'test'].map(evt => {
      channel.bind(evt, (data) => {
        console.log(evt, data);
      });
    });

    

    // let events = {};
    // Object.entries(params.events).map(([channel, events]) => {
    //   events[channel] = {};
    //   Object.entries(events).map(([event, callback]) => {
    //     events[channel][event] = false;
    //     console.log({ channel, event, callback: callback.toString() });
    //   });
    // });

    // console.log(events);

    const r = ref({
      // test: true,
      params,

      trigger(channelName, eventName, data) {
        channel.trigger(eventName, data);
      },
    });

    return r;
  };
  const socket = useWebsocket({
    events: {
      test: {
        test: () => {
          console.log('aaa');
        },
        aaa: () => {
          console.log('aaa');
        },
      },
    },
  });
  
  // const pusherSettings = {
  //   cluster: conf.public.PUSHER_APP_CLUSTER,
  //   wsHost: conf.public.PUSHER_HOST,
  //   wsPort: conf.public.PUSHER_PORT,
  //   wssPort: conf.public.PUSHER_PORT,
  //   encrypted: conf.public.PUSHER_SCHEME=='https',
  //   forceTLS: conf.public.PUSHER_SCHEME=='https',
  //   // disableStats: true,
  //   // enabledTransports: ['ws', 'wss'],
  //   // scheme: conf.public.PUSHER_SCHEME === 'https' ? 'wss' : 'ws',
  // };

  // const pusher = new Pusher(conf.public.PUSHER_APP_KEY, pusherSettings);
  // const channel = pusher.subscribe('test');

  // channel.bind('test', (data) => {
  //   console.log('test', data);
  // });

  // const pusherTrigger = (channel, event, data) => {
  //   const p = new Pusher({
  //     appId: conf.public.PUSHER_APP_ID,
  //     key: conf.public.PUSHER_APP_KEY,
  //     secret: conf.public.PUSHER_APP_SECRET,
  //     cluster: conf.public.PUSHER_APP_CLUSTER,
  //   });

  //   channel.trigger(event, data);
  //   console.log(p.trigger(channel, event, data));
  // };

  // console.clear();
  // console.log(JSON.stringify(conf.public, null, 2));
  // console.log(JSON.stringify(pusherSettings, null, 2));
  // // console.log({ pusher, channel });
  // console.log(pusher.allChannels());
</script>