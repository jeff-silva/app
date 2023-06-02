<template>
  <form @submit.prevent="edit.save()">
    <slot name="edit-fields" v-bind="slotBind({ edit })"></slot>
  
    <v-bottom-navigation>
      <v-btn :to="`/admin/${props.name}`">
        <v-icon>mdi-close</v-icon>
        <span>Cancel</span>
      </v-btn>
  
      <v-btn type="submit" :loading="edit.saving">
        <v-icon>mdi-content-save-outline</v-icon>
        <span>Save</span>
      </v-btn>
  
    </v-bottom-navigation>
  </form>
</template>

<script setup>
  import { ref, defineProps, onMounted } from 'vue';
  import axios from 'axios';

  const route = useRoute();
  const router = useRouter();

  const props = defineProps({
    name: {
      type: String,
      default: '',
    },
    editParams: {
      type: Object,
      default: () => ({}),
    },
  });

  const edit = ref({
    loading: false,
    saving: false,
    params: props.searchParams,
    data: {},
    async load() {
      if (isNaN(route.query.edit || null)) return;
      if (this.loading) return;
      this.loading = true;
      try {
        const params = props.editParams;
        const { data } = await axios.get(`api://${props.name}/${route.query.edit}`, { params });
        this.data = data;
      } catch(err) {}
      this.loading = false;
    },
    async save() {
      if (this.saving) return;
      this.saving = true;
      try {
        const { data } = await axios.post(`api://${props.name}`, this.data);
        this.data = data;
        router.push(`/admin/${props.name}`);
      } catch(err) {}
      this.saving = false;
    },
  });

  const slotBind = (merge={}) => {
    return {
      ...merge
    };
  };

  onMounted(() => {
    edit.value.load();
  });
</script>