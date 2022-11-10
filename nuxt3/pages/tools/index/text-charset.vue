<template>
  <v-row>
    <v-col cols="12">
      <v-table>
        <tbody>
          <tr v-for="codes in chars">
            <td v-for="c in codes" style="text-align: center;">
              <span v-html="c.code" style="font-size:30px;" />
            </td>
          </tr>
        </tbody>
      </v-table>
      <div class="d-flex align-center">
        <div class="flex-grow-1" style="overflow:hidden;">
          <v-pagination
            v-model="params.page"
            :length="999"
          />
        </div>
        <v-text-field type="number" v-model.number="params.page" :hide-details="true" />
      </div>
    </v-col>
  </v-row>
</template>

<script>
  export default {
    data: () => ({
      params: {
        page: 1,
      },
    }),

    computed: {
      chars() {
        const chunk = (arr, size) => Array.from({ length: Math.ceil(arr.length / size) }, (v, i) =>arr.slice(i * size, i * size + size));
        let start = Math.max(0, (this.params.page-1) * 100);
        let final = start + 100;
        let chars = [];
        for(let c=start; c<final; c++) {
          chars.push({
            id: c,
            code: `&#${c};`,
          });
        }
        return chunk(chars, 10);
      },
    },
  };
</script>