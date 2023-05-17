<template>
    <v-list
      :variant="variant"
      :density="density"
    >
        <slot name="prepend"></slot>

        <!-- Level 0 -->
        <template v-for="(item, i) in items">

            <!-- Item -->
            <v-list-item
                v-if="noChildren(item)"
                :prepend-icon="iconShow? (item.icon||iconDefault): null"
                :title="item.name"
                :to="item.to"
                @click.native="onClick($event, item)"
            ></v-list-item>

            <!-- Children -->
            <v-list-group v-else>
                <template #activator="{ props }">
                    <v-list-item
                        v-bind="props"
                        :prepend-icon="iconShow? (item.icon||iconDefault): null"
                        :title="item.name"
                        @click.native="onClick($event, item)"
                    ></v-list-item>
                </template>

                <!-- level 1 -->
                <template v-for="(iitem, ii) in item.children">

                    <!-- Item -->
                    <v-list-item
                        v-if="noChildren(iitem)"
                        :title="iitem.name"
                        :to="iitem.to"
                        @click.native="onClick($event, iitem)"
                    ></v-list-item>

                    <!-- Children -->
                    <div v-else>
                        <v-list-item
                          :title="iitem.name"
                          @click.native="onClick($event, item)"
                        ></v-list-item>
                        <v-menu anchor="end" activator="parent">
                            <app-nav
                              :items="iitem.children"
                              :icon-show="false"
                              :variant="variant"
                              :density="density"
                            />
                        </v-menu>
                    </div>
                </template>
            </v-list-group>
        </template>
        <slot name="append"></slot>
    </v-list>
</template>

<script>
export default {
    props: {
        items: {default:()=>([]), type:Array},
        iconShow: {default:true},
        iconDefault: {default:null},
        variant: {default:'text'},
        density: {default:'default'},
    },
    methods: {
        noChildren(item) {
            return !(Array.isArray(item.children) && item.children.length);
        },
        onClick(ev, item) {
            if (typeof item.onClick != 'function') return;
            item.onClick(ev, item);
        },
    },
}
</script>