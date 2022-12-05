<template>
  <div>
    soon
    <!-- <v-table>
      <thead>
        <tr>
          <th>x</th>
          <template v-for="f in table.fields">
            <th>
              <v-text-field
                v-model="f.name"
                label="Campo"
                :hide-details="true"
                density="compact"
                variant="plain"
                append-inner-icon="mdi-close"
                @click:appendInner="tableFieldRemove(f)"
              />
            </th>
          </template>
          <th class="p-0">
            <v-btn block @click="tableFieldAdd()">Add</v-btn>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(r, rowIndex) in table.rows">
          <td>
            <v-text-field
              v-model="r.name"
              :hide-details="true"
              density="compact"
              variant="plain"
              append-inner-icon="mdi-close"
              @click:appendInner="tableRowRemove(f)"
            />
          </td>
          <template v-for="(f, fieldIndex) in table.fields">
            <td class="pa-0">
              <input type="text" v-model="table.data[rowIndex][fieldIndex].value" style="width:100%; padding:10px; border:solid 1px red;" />
            </td>
          </template>
          <td></td>
        </tr>
        <tr>
          <td :colspan="table.fields.length+2">
            <v-btn block @click="tableRowAdd()">Add row</v-btn>
          </td>
        </tr>
      </tbody>
    </v-table>
    <app-dd class="mt-5">$data: {{ $data }}</app-dd> -->
  </div>
</template>

<script>
  export default {
    data: () => ({
      table: {
        fields: [],
        rows: [],
        data: [],
      },
    }),

    methods: {
      tableFieldAdd(data={}) {
        this.table.fields.push(data);
        const index = this.table.fields.length-1;
        this.table.data.forEach((row, i) => {
          this.table.data[index][i] = this.table.data[index][i] || {
            value: '',
          };
        });
      },
      tableFieldRemove(data) {
        const index = this.table.fields.indexOf(data);
        this.table.fields.splice(index, 1);
      },
      tableRowAdd(data={}) {
        this.table.rows.push(data);
        const index = this.table.rows.length-1;
        this.table.data[index] = this.table.data[index] || [];
      },
      tableRowRemove(data) {
        const index = this.table.rows.indexOf(data);
        this.table.rows.splice(index, 1);
      },
    },
  };
</script>