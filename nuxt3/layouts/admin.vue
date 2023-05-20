<template>
  <v-app>

    <!-- Auth -->
    <template v-if="!app.auth.user">
      auth
      <!-- <nuxt-layout name="auth">
        <app-auth-login :redirect="$route.path" />
      </nuxt-layout> -->
    </template>

    <!-- Screen -->
    <template v-if="app.auth.user">
      <v-layout>
        <v-navigation-drawer
          v-model="drawer.main"
          v-bind="{
            width: 250,
            border: 0,
            class: 'border-e',
          }"
        >
          <div class="d-flex flex-column" style="height:100vh;">
            <div class="pa-3 text-subtitle-2 text-uppercase" v-if="app.auth.user">
              {{ app.auth.user.name }}
            </div>
            <div class="flex-grow-1" style="overflow:auto;">
              <slot name="sidebar">
                <app-nav variant="plain" :items="[
                  {to:'/admin', name:'Dashboard'},
                  {to:'/admin', name:'Users', icon:'mdi-account', children: [
                    {to:'/admin/app_user', name:'Search'},
                    {to:'/admin/app_user/new', name:'Create'},
                    {to:'/admin/app_user_group', name:'Groups'},
                    {to:'/admin/app_user_group/new', name:'New group'},
                  ]},
                  {to:'/admin', name:'Arquivos', icon:'mdi-account', children: [
                    {to:'/admin/app_file', name:'Search'},
                    {to:'/admin/app_file/new', name:'Create'},
                  ]},
                  {to:'/admin', name:'Endereços', icon:'mdi-account', children: [
                    {to:'/admin/app_place', name:'Search'},
                    {to:'/admin/app_place/new', name:'Create'},
                  ]},
                  {to:'/admin', name:'Products', icon:'mdi-tshirt-crew', children: [
                    {to:'/admin?page=products/search', name:'Search'},
                    {to:'/admin?page=products/new', name:'Create'},
                  ]},
                  {to:'/admin', name:'Loto', icon:'mdi-slot-machine', children: [
                    {to:'/admin/loto/loto_megasena', name:'Megasena'},
                    {to:'/admin/loto/loto_lotofacil', name:'Lotofácil'},
                  ]},
                  {to:'/admin', name:'Tur', icon:'mdi-bus-marker', children: [
                    {to:'/admin/tur/trip', name:'Trips'},
                    {to:'/admin/tur/vehicle', name:'Vehicles'},
                    {to:'/admin/tur/driver', name:'Drivers'},
                    {to:'/admin/tur/vehicle_type', name:'Vehicle Types'},
                    {to:'/admin/tur/vehicle_brand', name:'Vehicle Brands'},
                    {to:'/admin/tur/vehicle_model', name:'Vehicle Models'},
                  ]},
                  {to:'/', name:'Settings', icon:'mdi-cog', children: [
                    {to:'/admin/settings', name:'Configurações'},
                    {to:'/dev', name:'Dev'},
                  ]},
                ]" />
              </slot>
            </div>
            <app-nav
              variant="plain"
              density="compact"
              :items="[
                {to:'/auth?redirect=back', name:'Trocar conta'},
                {name:'Sair', onClick() { app.logout(); }},
              ]"
            />
          </div>
        </v-navigation-drawer>
        <v-main style="height:100vh; overflow-y:scroll; overflow-x:hidden; overflow-y:auto;">
          <v-app-bar class="px-md-3" v-bind="{height:50, color:'grey-lighten-4', elevation:0, location:'top'}">
            <v-container class="pa-0" :fluid="props.containerFluid">
              <v-btn icon="mdi-menu" size="small" @click="drawer.main=true" class="d-lg-none"></v-btn>
            </v-container>
          </v-app-bar>
          <v-container class="pa-0 pa-md-3" :fluid="props.containerFluid">
            <slot />
          </v-container>
        </v-main>
      </v-layout>
    </template>
  </v-app>
</template>


<script setup>
  import { ref, defineProps } from 'vue';
  import { breakpointsVuetify, useBreakpoints } from '@vueuse/core';
  import useApp from '@/composables/useApp';

  const app = useApp();
  const breakpoints = useBreakpoints(breakpointsVuetify);

  const props = defineProps({
    containerFluid: {
      type: Boolean,
      default: true,
    },
  });

  const drawer = ref({
    main: breakpoints.xs,
  });
</script>


<!-- <script>
import { breakpointsVuetify, useBreakpoints } from '@vueuse/core';
const breakpoints = useBreakpoints(breakpointsVuetify);

export default {
  props: {
    auth: {
      type: Boolean,
      default: true,
    },
    containerFluid: {
      type: Boolean,
      default: true,
    },
    navWidth: {
      type: Number,
      default: 250,
    },
  },

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
</script> -->