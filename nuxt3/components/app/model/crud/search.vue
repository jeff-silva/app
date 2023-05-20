<template>
  <div class="d-flex align-center mb-4">
    <v-spacer />
    <v-text-field
      :hide-details="true"
      append-inner-icon="mdi-magnify"
      density="compact"
      placeholder="Search"
      style="max-width:400px;"
    />
  </div>

  <v-table class="border" hover density="compact">
    <colgroup>
      <col width="60">
      <col width="*">
      <col width="*">
      <col width="*">
    </colgroup>
    <thead>
      <tr class="bg-grey-lighten-4">
        <th><v-checkbox v-bind="{ hideDetails: true }" /></th>
        <th>Aaa</th>
        <th>Aaa</th>
        <th>Aaa</th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <td><v-checkbox v-bind="{ hideDetails: true }" /></td>
        <td>Aaa</td>
        <td>Aaa</td>
        <td>Aaa</td>
      </tr>
      <tr>
        <td><v-checkbox v-bind="{ hideDetails: true }" /></td>
        <td>Aaa</td>
        <td>Aaa</td>
        <td>Aaa</td>
      </tr>
      <tr>
        <td><v-checkbox v-bind="{ hideDetails: true }" /></td>
        <td>Aaa</td>
        <td>Aaa</td>
        <td>Aaa</td>
      </tr>
    </tbody>
  </v-table>

  <v-pagination
    class="mt-4"
    :length="6"
  />

  <v-bottom-navigation>
    <v-btn :to="`/admin/${props.name}?edit=new`">
      <v-icon>mdi-plus</v-icon>
      <span>Add</span>
    </v-btn>
  </v-bottom-navigation>
</template>

<script setup>
  import { defineProps } from 'vue';

  const route = useRoute();

  const props = defineProps({
    name: {
      type: String,
      default: '',
    },
  });
</script>