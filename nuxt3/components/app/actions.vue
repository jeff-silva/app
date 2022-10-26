<template>
  <v-app-bar class="px-3" v-bind="{height:50, color:'grey-lighten-5', elevation:2, location:'bottom'}">
    <div style="display:flex; align-items:center; justify-content:center; gap:10px; margin:0 auto;">
      <slot />
    </div>
  </v-app-bar>
</template>