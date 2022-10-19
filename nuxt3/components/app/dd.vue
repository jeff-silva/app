<template>
  <pre class="pa-2 rounded" style="background:#222; color:lime;">{{ typeof modelValue }}: {{ jsonString }}</pre>
</template>

<script>
export default {
  props: {
    modelValue: {
      type: [Boolean, Number, String, Array, Object],
    },
  },
  computed: {
    jsonString() {
      return JSON.stringify(this.modelValue, 2, ' ');
    },
  },
};
</script>