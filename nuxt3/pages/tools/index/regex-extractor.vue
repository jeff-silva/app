<template>
  <v-row>
    <v-col cols="12" md="6">
      <v-textarea label="Cole o texto" v-model="params.text" />
    </v-col>
    <v-col cols="12" md="6">
      <v-select label="O que extrair?" :items="types" v-model="params.type"></v-select>
      <v-table>
        <tbody>
          <tr v-for="item in outputs">
            <td>{{ item }}</td>
          </tr>
        </tbody>
      </v-table>
    </v-col>
  </v-row>
</template>

<script>
  export default {
    data() {
      return {
        params: {
          text: [
            'alkjdsaf jhlasjkdfh asljf name@grr.la asdlkfa hsdjfh http://site.com',
            'asd jsadfh laksjdfh asdsdfsd dssss@mail.com',
            'xxxxxxxxxxx@mail.com',
            'asdf kasjdfh kasjdhf sadkjf @ asldkfj .com',
            'https://name.com?name=aaa ftp://user@addr.com http://www.site.com',
          ].join("\n"),
          type: 'links',
        },
        types: [
          {
            value: 'emails',
            regex:'[a-zA-Z0-9._-]{3,}@[a-zA-Z0-9.-]{3,}\.[a-zA-Z]{2,4}',
            title: 'E-mails',
          },
          {
            value: 'links',
            regex:'(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])',
            title: 'Links',
          },
        ],
      };
    },
    computed: {
      outputs() {
        for(let i in this.types) {
          if (this.types[i].value == this.params.type) {
            let r = new RegExp(this.types[i].regex, 'gi');
            return this.params.text.match(r) || [];
          }
        }
        return [];
      },
    },
  };
</script>