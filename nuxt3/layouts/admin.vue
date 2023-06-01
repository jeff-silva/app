<template>
  <v-defaults-provider
    :defaults="{
      VTextField: { variant: 'outlined' },
      VTextarea: { variant: 'outlined' },
      VSelect: { variant: 'outlined' },
    }"
  >
    <v-app>
      <!-- Auth -->
      <template v-if="!app.auth.user">
        <div class="h-100 d-flex align-center justify-center bg-grey-lighten-3">
          <div style="width:400px; max-width:90vw;">
            <v-card>
              <v-tabs v-model="auth.tab">
                <v-tab value="login">Login</v-tab>
                <v-tab value="register">Register</v-tab>
                <v-tab value="password">Password</v-tab>
              </v-tabs>
              <v-divider />
              <v-window v-model="auth.tab">
                <v-window-item value="login">
                  <app-auth-login />
                </v-window-item>
                <v-window-item value="register">
                  <app-auth-register />
                </v-window-item>
                <v-window-item value="password">
                  <app-auth-password />
                </v-window-item>
              </v-window>
            </v-card>
          </div>
        </div>
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
                      {to:'/admin/app_user', name:'Users list'},
                      {to:'/admin/app_user_group', name:'Groups list'},
                    ]},
                    {to:'/admin', name:'Arquivos', icon:'mdi-account', children: [
                      {to:'/admin/app_file', name:'Search'},
                      {to:'/admin/app_file/new', name:'Create'},
                    ]},
                    {to:'/admin', name:'Endereços', icon:'mdi-account', children: [
                      {to:'/admin/app_place', name:'Search'},
                      {to:'/admin/app_place/new', name:'Create'},
                    ]},
                    {to:'/', name:'Settings', icon:'mdi-cog', children: [
                      {to:'/admin/settings', name:'Configurações'},
                      {to:'/admin/app_mail_template', name:'E-mail templates'},
                    ]},
                  ]" />
                </slot>
              </div>
              <app-nav
                variant="plain"
                density="compact"
                :items="[
                  {name:'Switch account', onClick() { drawer.account = true; }},
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

      <v-dialog v-model="drawer.account">
        <v-card style="width:400px; max-width:90vw; margin:0 auto;">
          <v-card-title>Switch account</v-card-title>
          <app-auth-login />
          <v-divider />
          <v-card-actions>
            <v-spacer />
            <v-btn @click="drawer.account=false">Close</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-app>
  </v-defaults-provider>
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
      default: false,
    },
  });

  const drawer = ref({
    main: breakpoints.xs,
    account: false,
  });

  const auth = ref({
    tab: 'login',
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