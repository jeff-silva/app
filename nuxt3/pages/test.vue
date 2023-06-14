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

    <v-btn :disabled="valid.invalid()">Send</v-btn>
    <br><br>

    <!-- <pre>valid.errorsList(): {{ valid.errorsList() }}</pre> -->
    <!-- <pre>valid: {{ valid.valid() }}</pre> -->
    <!-- <pre>data: {{ data }}</pre> -->
    <!-- <pre>valid: {{ valid }}</pre> -->

    <v-row>
      <v-col cols="4" v-for="(w, index) in websocket.list" :key="index">
        <pre
          style="font-size:14px;"
          class="pa-3"
          :class="{
            'bg-warning': w.loading,
            'bg-error': w.status=='close',
          }"
        >{{ w }}</pre>
        <v-btn
          block
          @click="w.send(w, { index })"
          :loading="w.loading"
        >Send</v-btn>
      </v-col>
    </v-row>
    <pre>websocket: {{ websocket }}</pre>
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

  const createWebsocket = (url) => {
    const socket = new WebSocket(url);
    console.log(`Connection: ${url}`);

    socket.addEventListener('open', (event) => {
      console.log(`${url}: Conexão WebSocket aberta`);
    });

    socket.addEventListener('message', (event) => {
      console.log(`${url}: Mensagem recebida do servidor:`, event.data);
    });

    socket.addEventListener('close', (event) => {
      console.log(`${url} Conexão WebSocket fechada`);
    });

    socket.addEventListener('error', (event) => {
      console.error(`${url} Erro na conexão WebSocket:`, event);
    });

    return socket;
  };


  

  import Pusher from 'pusher-js';
  console.log(JSON.stringify(conf.public, null, 2));

  const pusher = new Pusher('app', {
    cluster: conf.public.PUSHER_APP_CLUSTER,
    wsHost: conf.public.PUSHER_HOST,
    wsPort: conf.public.PUSHER_PORT,
    wssPort: conf.public.PUSHER_PORT,
    encrypted: conf.public.PUSHER_SCHEME=='https',
    forceTLS: conf.public.PUSHER_SCHEME=='https',
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    scheme: conf.public.PUSHER_SCHEME === 'https' ? 'wss' : 'ws',
  });

  const channel = pusher.subscribe('test');

  channel.bind('init', (data) => {
    console.log(data);
  });

  console.log({ pusher, channel });
  console.log(pusher.allChannels());



  const websocket = ref({
    list: [
      // `http://127.0.0.1:${conf.public.WEBSOCKET_PORT}`,
      `ws://0.0.0.0:${conf.public.WEBSOCKET_PORT}`,
      `wss://0.0.0.0:${conf.public.WEBSOCKET_PORT}`,
      `ws://127.0.0.1:${conf.public.WEBSOCKET_PORT}`,
      `wss://127.0.0.1:${conf.public.WEBSOCKET_PORT}`,
      `ws://localhost:${conf.public.WEBSOCKET_PORT}`,
      `wss://localhost:${conf.public.WEBSOCKET_PORT}`,
      `ws://laravel.test:${conf.public.WEBSOCKET_PORT}`,
      `wss://laravel.test:${conf.public.WEBSOCKET_PORT}`,
      `ws://soketi:${conf.public.WEBSOCKET_PORT}`,
      `wss://soketi:${conf.public.WEBSOCKET_PORT}`,
      // 'wss://stream.binance.com:9443/ws/!miniTicker@arr',
    ].map(url => {
      return {
        open: false,
        loading: false,
        status: false,
        url,
        ws: false,
        data: false,
        send: (self, data) => {
          console.log({ self, data });
        },
      };
    }),
    loadAll() {
      this.list.map((item) => {
        item.loading = true;
        const ws = new WebSocket(item.url);
        ws.addEventListener("open", (event) => {
          item.loading = false;
          item.status = 'open';
        });

        ws.addEventListener("message", (event) => {
          item.loading = false;
          item.data = JSON.parse(event.data);
          // console.log("Message from server ", event.data);
        });

        ws.addEventListener("error", (event) => {
          item.loading = false;
          item.status = 'error';
        });

        ws.addEventListener("close", (event) => {
          item.loading = false;
          item.status = 'close';
        });

        item.ws = ws;
        console.log(ws);
      });
    },
  });

  onMounted(() => {
    setTimeout(() => {
      websocket.value.loadAll();
    }, 1000);
  });


  // const conns = [
  //   'ws://laravel.tes:6001',
  //   'wss://laravel.tes:6001',
  //   'ws://localhost:6001',
  //   'wss://localhost:6001',
  //   'ws://localhost:6001/test',
  //   'wss://localhost:6001/test',
  // ].map(host => createWebsocket(host));
</script>