<template>
  <v-select
    :model-value="props.modelValue"
    :label="props.label"
    :items="options.data"
    item-value="id"
    item-title="name"
    @update:modelValue="emit('update:modelValue', $event)"
  />
  <pre>{{ props }}</pre>
</template>

<script setup>
  import { ref, defineEmits, onMounted, defineProps } from 'vue';
  import axios from 'axios';

  const props = defineProps({
    modelValue: {
      type: [ Boolean, Object ],
      default: () => ({}),
    },
    name: {
      type: String,
      default: '',
    },
    label: {
      type: [ Boolean, String ],
      default: false,
    },
    params: {
      type: [ Object ],
      default: () => ({}),
    },
  });

  const emit = defineEmits(['update:modelValue']);

  const options = ref({
    loading: false,
    data: [],
    async load() {
      this.loading = true;
      try {
        const { data } = await axios.get(`api://${props.name}?per_page=99`);
        this.data = data.data;
      } catch(err) {}
      this.loading = false;
    },
  });

  onMounted(() => {
    options.value.load();
  });
</script>