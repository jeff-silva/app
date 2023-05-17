<template>
  <v-select
    :items="data.resp.data"
    item-title="name"
    item-value="id"
    v-model="value"
    :loading="data.loading"
  ></v-select>
</template>

<script>
  export default {
    props: {
      modelValue: {
        type: [Boolean, Number, String, Array],
        default: false,
      },
      model: {
        type: String,
        default: 'users',
      },
    },

    computed: {
      value: {
        get() { return this.modelValue; },
        set(value) {
          this.$emit('update:modelValue', value);
        },
      },
    },

    data() {
      return {
        data: useAxios({
          url: `/api/${this.model}`,
          autoSubmit: true,
        }),
      };
    },
  };
</script>