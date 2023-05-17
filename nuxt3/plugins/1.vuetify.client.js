import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import 'vuetify/styles';

export default defineNuxtPlugin(async (nuxtApp) => {
  nuxtApp.vueApp.use(createVuetify({
    components,
    directives,
    // icons: { defaultSet: 'mdi' },
  }));
});