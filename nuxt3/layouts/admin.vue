<template>
  <v-app>
    <v-layout>
      <v-navigation-drawer
        v-model="drawer"
        v-bind="{
          width: 200,
        }"
      >
        <slot name="sidebar">
          Sidebar
        </slot>
      </v-navigation-drawer>
      <v-main style="height:100vh; overflow-y:scroll; overflow-x:hidden;">
        <v-app-bar class="px-md-3" v-bind="{height:50, color:'grey-lighten-4', elevation:0, location:'top'}">
          <v-btn icon="mdi-menu" size="small" @click="drawer=true" v-if="!breakpoints.xs"></v-btn>
        </v-app-bar>
        <v-container>
          <slot />
          <pre>{{ breakpoints }}</pre>
        </v-container>
      </v-main>
    </v-layout>
  </v-app>
</template>


<script>
import { breakpointsVuetify, useBreakpoints } from '@vueuse/core';
const breakpoints = useBreakpoints(breakpointsVuetify);

export default {
  data() {
    return {
      drawer: breakpoints.xs,
      breakpoints,
    };
  },
};
</script>