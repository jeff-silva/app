<template>
  <label class="d-block bg-grey-lighten-4 elevation-1 pa-3 rounded" :style="`height:${props.height}; cursor:pointer;`">
    <v-img
      v-if="file.data && file.data.url && file.data.is_image"
      :src="file.data.url"
      height="100%"
    />
    <input type="file" class="d-none" @change="file.fileChangeHandler($event)" />
  </label>
</template>

<script setup>
  import { ref, defineProps, defineEmits, watch } from 'vue';
  import axios from 'axios';

  const props = defineProps({
    modelValue: {
      type: [ Boolean, File ],
      default: false,
    },
    height: {
      type: [ String ],
      default: "200px",
    },
  });

  const emit = defineEmits([
    'update:modelValue',
  ]);

  const file = ref({
    data: false,
    fileChangeHandler($event) {
      const fileObj = $event.target.files[0] || false;
      if (!fileObj) return;
      this.upload(fileObj);
    },

    async upload(file) {
      var formData = new FormData();
      formData.append('content', file);
      try {
        const { data } = await axios.post('api://app_file', formData);
        if (data.id) {
          emit('update:modelValue', data.id);
          this.data = data;
        }
      } catch (err) {}
    },
  });

  const fileLoadHandler = async () => {
    if (!props.modelValue) return;
    try {
        const { data } = await axios.get(`api://app_file/${props.modelValue}`);
        file.value.data = data.data;
      } catch (err) {}
  };

  watch([ props ], ([ propsNew ]) => {
    fileLoadHandler();
  });

  fileLoadHandler();
</script>