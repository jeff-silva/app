<template>
  <div>
    <h2>Rotas</h2>
    <v-divider class="mb-3" />
    <v-sheet elevation="1">
      <v-table>
        <thead>
          <tr>
            <th>Name</th>
            <th>URI</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="r in appRoutes.resp" :key="r.name">
            <td>{{ r.name }}</td>
            <td>/{{ r.uri }}</td>
          </tr>
        </tbody>
      </v-table>
    </v-sheet>

    <v-divider class="my-5" />

    <div class="d-flex align-center">
      <div>
        <v-btn @click="appTest.submit()" :disabled="appTest.loading">Refresh</v-btn>
      </div>
      <v-divider :vertical="true" class="mx-5" />
      <div>From {{ appTest.resp.start }} ~ {{ appTest.resp.final }}</div>
      <v-divider :vertical="true" class="mx-5" />
      <div>{{ appTest.resp.items.length }} itens</div>
    </div>
    <v-divider class="my-5" />
    <transition-group name="list" tag="div" class="v-row">
      <v-col v-for="item in appTest.resp.items" :key="item.number" cols="1">
        <v-card class="pa-2 text-center">
          {{ item.number }}
        </v-card>
      </v-col>
    </transition-group>
  </div>
</template>

<script>
export default {
  data() {
    return {
      appTest: useAxios({
        method: 'get',
        url: '/api/app/test',
        submit: true,
        resp: {
          start: 0,
          final: 0,
          items: [],
        },
      }),
      appRoutes: useAxios({
        method: 'get',
        url: '/api/app/routes',
        submit: true,
        resp: [],
      }),
    };
  },
};
</script>

<style>
.list-enter-active,
.list-leave-active {
  transition: all 200ms ease;
}
.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateX(30px);
}
</style>