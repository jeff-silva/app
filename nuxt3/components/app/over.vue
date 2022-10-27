<template>
  <div>

    <!-- Dialog -->
    <v-dialog v-if="type=='dialog'" v-model="shown" v-bind="{...overBind}">
      <template #activator="bind">
        <slot name="activator" v-bind="bind" />
      </template>

      <slot />
    </v-dialog>

    <!-- Menu -->
    <v-menu v-else-if="type=='menu'" v-bind="{...overBind}">
      <template #activator="bind">
        <slot name="activator" v-bind="bind" />
      </template>

      <v-card>
        <v-card-text>
          <slot />
        </v-card-text>
      </v-card>
    </v-menu>

    <!-- Side -->
    <v-menu v-else-if="type=='side'" v-bind="{...overBind}">
      <template #activator="bind">
        <slot name="activator" v-bind="bind" />
      </template>

      <div style="display:flex; gap:5px; margin:0 10px;">
        <slot />
      </div>
    </v-menu>

    <!-- Drawer -->
    <div v-else-if="type=='drawer'">
      <slot name="activator" v-bind="drawerBind()" />

      <v-navigation-drawer v-model="shown" v-bind="{temporary:true, order:-1, ...overBind}">
        <slot />
      </v-navigation-drawer>
    </div>

    <!-- <pre>$data: {{ $data }}</pre> -->
  </div>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: 'dialog', // dialog, menu, side, drawer
    },
    overBind: {
      type: Object,
      default: () => ({}),
    },
  },

  methods: {
    drawerBind() {
      return {
        props: {
          onClick: (ev) => {
            this.shown = !this.shown;
          },
        },
      };
    },
  },

  data() {
    return {
      shown: false,
    };
  },
};
</script>