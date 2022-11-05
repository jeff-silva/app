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
          <v-divider />
          <div class="flex-grow-1" style="overflow:auto;">
            <slot name="sidebar">
              <app-nav variant="plain" :items="[
                {to:'/tools', name:'Ferramentas', icon:'mdi-cog', children: [
                  {to:'/tools/search', name:'Busca'},
                  {to:'/tools/whatsapp', name:'Whatsapp link'},
                ]},
                {to:'/dev', name:'Dev', icon:'mdi-cog', children: [
                  {to:'/dev/api', name:'API'},
                  {to:'/dev/auth', name:'Auth'},
                  {to:'/dev/test', name:'Test'},
                ]},
                {to:'/admin', name:'Admin', icon:'mdi-cog'},
              ]" />
            </slot>
          </div>
        </div>
      </v-navigation-drawer>
      <v-main style="height:100vh; overflow-y:scroll; overflow-x:hidden; overflow-y:auto;">
        <v-app-bar class="px-md-3" v-bind="{height:50, color:'grey-lighten-4', elevation:0, location:'top'}">
          <v-btn icon="mdi-menu" size="small" @click="drawer=true" v-if="!breakpoints.xs"></v-btn>
        </v-app-bar>
        <v-container style="max-width:1200px;">
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