<template>
  <v-app>
    <v-layout>
      <v-navigation-drawer
        v-model="drawer"
        v-bind="{
          width: 250,
        }"
      >
        <div class="d-flex flex-column" style="height:100vh;">
          <v-sheet class="pa-3">
            Sheet
          </v-sheet>
          <v-divider />
          <div class="flex-grow-1" style="overflow:auto;">
            <app-nav variant="plain" :items="[
              {to:'/admin', name:'Dashboard'},
              {to:'/admin', name:'Users', icon:'mdi-account', children: [
                {to:'/admin?page=users/search', name:'Search'},
                {to:'/admin?page=users/new', name:'Create'},
              ]},
              {to:'/admin', name:'Products', icon:'mdi-tshirt-crew', children: [
                {to:'/admin?page=products/search', name:'Search'},
                {to:'/admin?page=products/new', name:'Create'},
              ]},
            ]" />
          </div>
          <v-divider />
          <app-nav
            variant="plain"
            density="compact"
            :items="[
              {name:'Sair', onClick:app.logout},
            ]"
          />
        </div>
      </v-navigation-drawer>
      <v-main style="height:100vh; overflow-y:scroll; overflow-x:hidden;">
        <v-app-bar class="px-md-3" v-bind="{height:50, color:'grey-lighten-4', elevation:0, location:'top'}">
          <v-btn icon="mdi-menu" size="small" @click="drawer=true" v-if="!breakpoints.xs"></v-btn>
        </v-app-bar>
        <v-container>
          <slot />
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
      navs: [
        {type:'title', name:'Admin'},
        {type:'item', to:'/', name:'Dashboard'},
        {type:'title', name:'User'},
        {type:'item', to:'/', name:'Create'},
        {type:'item', to:'/', name:'List'},
        {type:'divider'},
        {type:'item', to:'/', name:'Dashboard'},
      ],
      app: useApp(),
    };
  },
};
</script>