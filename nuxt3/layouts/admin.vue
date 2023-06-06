<template>
  <v-defaults-provider
    :defaults="{
      VTextField: { variant: 'outlined' },
      VTextarea: { variant: 'outlined' },
      VFileInput: { variant: 'outlined' },
      VSelect: { variant: 'outlined' },
    }"
  >

    <!-- <pre>{{ Object.keys(app) }}</pre> -->

    <!-- Loading -->
    <template v-if="!app.ready">
      <div class="d-flex align-center justify-center" style="height:100vh;">
        <div>
          <div class="text-disabled">Loading</div>
          <v-progress-linear indeterminate />
        </div>
      </div>
    </template>

    <!-- Auth -->
    <template v-if="app.ready && !app.user">
      <div class="d-flex align-center justify-center bg-grey-lighten-3" style="height:100vh;">
        <div style="width:400px; max-width:90vw;">
          <v-card>
            <v-tabs v-model="auth.tab">
              <v-tab value="login">Login</v-tab>
              <v-tab value="register">Register</v-tab>
              <v-tab value="password">Password</v-tab>
            </v-tabs>
            <v-window v-model="auth.tab">
              <v-window-item value="login">
                <v-card-text>
                  <app-auth-login />
                  <br>
                  <app-auth-switch />
                </v-card-text>
              </v-window-item>
              <v-window-item value="register">
                <v-card-text>
                  <app-auth-register />
                </v-card-text>
              </v-window-item>
              <v-window-item value="password">
                <v-card-text>
                  <app-auth-password />
                </v-card-text>
              </v-window-item>
            </v-window>
          </v-card>
        </div>
      </div>
    </template>


    <!-- Logged -->
    <template v-if="app.ready && app.user">
      <v-layout>
        <v-app-bar @click="drawer.main=true">
          <v-btn icon="mdi-menu" flat class="d-lg-none"></v-btn>
          <v-spacer></v-spacer>
          <div class="px-2">Welcome {{ app.user.name }}</div>
          <v-spacer></v-spacer>
          <div class="px-2">...</div>
        </v-app-bar>
  
        <v-navigation-drawer
          v-model="drawer.main"
          v-bind="{
            width: 250,
            border: 0,
            class: 'border-e',
          }"
        >
          <div class="d-flex flex-column" style="height: calc(100vh - 64px);">
            <div class="flex-grow-1" style="overflow:auto;">
              <slot name="sidebar">
                <app-nav variant="plain" :items="[
                  {to:'/admin', name:'Dashboard'},
                  {to:'/admin', name:'Users', icon:'mdi-account', children: [
                    {to:'/admin/app_user', name:'Users list'},
                    {to:'/admin/app_user_group', name:'Groups list'},
                  ]},
                  {to:'/', name:'Settings', icon:'mdi-cog', children: [
                    {to:'/admin/settings', name:'Configurações'},
                    {to:'/admin/app_mail_template', name:'E-mail templates'},
                    {to:'/admin/app_file', name:'Uploads'},
                    {to:'/admin/app_place', name:'Places'},
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
  
        <v-main>
          <v-container class="pt-8 pt-md-10" style="max-width:1200px;">
            <template v-if="hasPermission">
              <slot></slot>
            </template>
            <template v-else>
              <v-alert type="error">
                Sorry, you have no permission to see this screen.
              </v-alert>
            </template>
          </v-container>
        </v-main>
      </v-layout>
    </template>


    <!-- Switch account -->
    <v-dialog v-model="drawer.account">
      <v-card style="width:400px; max-width:90vw; margin:0 auto;">
        <v-card-title>Switch account</v-card-title>
        <v-divider />
        <v-card-text>
          <app-auth-login />
          <br>
          <app-auth-switch />
        </v-card-text>
        <v-divider />
        <v-card-actions>
          <v-spacer />
          <v-btn @click="drawer.account=false">Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-defaults-provider>
</template>


<script setup>
  import { ref, computed, defineProps } from 'vue';
  import { breakpointsVuetify, useBreakpoints } from '@vueuse/core';
  
  import useApp from '@/composables/useApp';
  const app = useApp();

  const route = useRoute();

  const hasPermission = computed(() => {
    if (!app.ready) return false;
    const permissionKey = `view:${route.name}`;
    if (typeof app.permissions[permissionKey] != 'undefined') {
      return app.user.app_user_group.permissions.includes(permissionKey);
    }
    return true;
  });

  const breakpoints = useBreakpoints(breakpointsVuetify);

  const props = defineProps({
    containerFluid: {
      type: Boolean,
      default: false,
    },
  });

  const drawer = ref({
    main: breakpoints.md,
    account: false,
  });

  const auth = ref({
    tab: 'login',
  });
</script>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
  html, body { font-family: 'Montserrat', sans-serif; }
</style>
