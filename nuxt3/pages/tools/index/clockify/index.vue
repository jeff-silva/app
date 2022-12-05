<template>
  <div>
    <v-row>
      <v-col cols="12" md="6">
        <v-text-field
          label="Informe seu token"
          v-model="token"
          @update:model-value="clockifyInit()"
        >
          <template #details>
            Disponível em  <a href='https://app.clockify.me/user/settings' target='_blank'>https://app.clockify.me/user/settings</a>
            no fim da página
          </template>
        </v-text-field>

        <v-combobox
          label="Workspace"
          v-model="workspace.item"
          :items="workspace.items"
          item-title="name"
          :loading="workspace.loading"
          :hide-details="true"
          @update:model-value="clockifyWorkspaceUserTimeEntries()"
        ></v-combobox>
      </v-col>
      <v-col cols="12" md="6">
        <v-text-field
          label="À receber"
          v-model="userTimeEntries.total.amount"
          :prefix="userTimeEntries.total.currency"
          readonly
        ></v-text-field>

        <v-table>
          <tbody>
            <tr v-for="(o, i) in userTimeEntries.items" :key="i">
              <td>{{ o.description }}</td>
              <td>{{ o.timeInterval.duration.replace('PT', '').replace('H', 'h ').replace('M', 'm ').replace('S', 's') }}</td>
            </tr>
          </tbody>
        </v-table>
      </v-col>
    </v-row>
  </div>
</template>

<script>
  import { useStorage } from '@vueuse/core';
  
  export default {
    data: () => ({
      // token: '',
      // token: 'MmM1MTVhZjMtM2ZhMS00YWVhLTk0MWYtZjNiY2Q3MDFiZmEw',
      token: useStorage('clockify-token', ''),
      workspace: {
        loading: false,
        item: false,
        items: [],
      },
      userTimeEntries: {
        loading: false,
        total: {seconds:0, amount:0, currency:'BRL'},
        params: {
          start: '2022-11-30T00:00:00.000z',
          end: '2022-12-31T00:00:00.000z',
        },
        item: false,
        items: [],
      },
      axios: false,
    }),

    methods: {
      async clockifyInit() {
        if (!this.token) return;
        this.axios = this.$axios.create({
          baseURL: 'https://api.clockify.me/api/v1',
          headers: { 'X-Api-Key': this.token },
        });
        await this.clockifyWorkspaces();
      },

      async clockifyWorkspaces() {
        this.workspace.loading = true;
        const { data } = await this.axios.get('/workspaces');
        this.workspace.items = data;
        this.workspace.loading = false;
        if (data.length==1) {
          this.workspace.item = data[0];
          await this.clockifyWorkspaceUserTimeEntries();
        }
      },

      async clockifyWorkspaceUserTimeEntries() {
          if (!this.workspace.item) return;
          const workspaceId = this.workspace.item.id;
          if (!workspaceId) return;
          const userId = '6387e81231d7900df31310d3';
          this.userTimeEntries.loading = true;
          const { params } = this.userTimeEntries;
          const { data } = await this.axios.get(`/workspaces/${workspaceId}/user/${userId}/time-entries`, { params });
          this.userTimeEntries.loading = false;
          this.userTimeEntries.total.seconds = 0;
          this.userTimeEntries.total.amount = 0;

          this.userTimeEntries.items = data.map((item) => {
            const regex = /PT((\d{0,2})H)*((\d{0,2})M)*((\d{0,2})S)*/gm;
            const [,, h,, m,, s ] = [ ...item.timeInterval.duration.matchAll(regex) ][0];
            let durationData = { h, m, s };
            for(let i in durationData) { durationData[i] = parseInt(durationData[i]); }
            item.timeInterval.durationData = durationData;
            return item;
          });

          this.userTimeEntries.items.forEach((item) => {
            const seconds = item.timeInterval.durationData.s + (item.timeInterval.durationData.m*60) + (item.timeInterval.durationData.h*60*60);
            this.userTimeEntries.total.seconds += seconds;
          });

          const amount = (this.userTimeEntries.total.seconds / 60 / 60) * 13;
          const { data: currency } = await this.$axios.get(`https://api.exchangerate.host/latest?base=AUD&amount=${amount}`);
          this.userTimeEntries.total.amount = currency.rates.BRL.toFixed(2);
        },
    },

    mounted() {
      this.clockifyInit();
    }
  };
</script>