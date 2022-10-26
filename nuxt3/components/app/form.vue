<template>
  <form @submit.prevent="request.submit()">
    <slot />
    <pre>$props: {{ $props }}</pre>
    <pre>$data: {{ $data }}</pre>
  </form>
</template>

<script>
import useAxios from '@/composables/useAxios';

export default {
  props: {
    modelValue: {
      default: () => ({}),
      type: Object,
    },
    method: {
      default: 'get',
      type: String,
    },
    action: {
      default: '/api/app/openapi',
      type: String,
    },
  },
  // computed: {
  //   value: {
  //     get() { return this.modelValue; },
  //     set(value) { this.$emit('update:modelValue', value); },
  //   },
  // },
  methods: {
    submit() {
      alert('aa');
    },
  },
  data() {
    return {
      request: useAxios({
        method: this.method,
        url: this.action,
        data: this.modelValue,
      }),
    };
  },
};
</script>