<template>
  <v-row>
    <v-col cols="12"><v-text-field label="Aaa" /></v-col>
    <v-col cols="6"><v-text-field label="Aaa" /></v-col>
    <v-col cols="6"><v-text-field label="Aaa" /></v-col>
  </v-row>

  <v-bottom-navigation>
    <v-btn :to="`/admin/${props.name}`">
      <v-icon>mdi-close</v-icon>
      <span>Cancel</span>
    </v-btn>

    <v-btn>
      <v-icon>mdi-content-save-outline</v-icon>
      <span>Save</span>
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