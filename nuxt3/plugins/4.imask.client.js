// https://imask.js.org/
import IMask from 'imask';

export default defineNuxtPlugin(async (nuxtApp) => {

  // Presets list
  const imaskPresets = {
    'phone-br': {
      mask: [
        { mask: '(00) 0000-0000' },
        { mask: '(00) 00000-0000' },
      ],
    },
  };

  nuxtApp.vueApp.directive('imask', {
    input: false,
    settings: {},
    imask: false,

    fireEvent(el, eventName, data) {
      let e = document.createEvent('CustomEvent');
      e.initCustomEvent(eventName, true, true, data);
      el.dispatchEvent(e);
    },

    beforeMount(el, bind, vnode, prevVnode) {
      el.input = el.tagName=='INPUT' ? el : el.querySelector('input');

      el.settings = (() => {
        let settings = bind.value;
  
        if (typeof bind.value=='string') {
          settings = imaskPresets[bind.value] || {
            mask: bind.value,
          };
        }
  
        if (Array.isArray(bind.value)) {
          settings = {
            mask: bind.value.map((mask) => {
              return { mask };
            }),
          };
        }

        return settings;
      })();


      el.imask = IMask(el.input, el.settings)
    },

    updated(el, bind, vnode, prevVnode) {
      el.imask.masked.value = el.input.value;
      el.imask.updateOptions(el.settings);
      el.imask._onChange();
    },
    
    unmounted(el, bind, vnode, prevVnode) {
      el.imask.destroy();
    },
  });
});