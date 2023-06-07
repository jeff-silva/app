<template>
  <v-defaults-provider
    :defaults="{
      VBtn: {
        rounded: 0,
        elevation: 1,
        size: 'x-small',
      },
    }"
  >
    <div>
      <div class="d-flex flex-wrap mb-3" style="gap:15px;">
        <div>
          <v-btn icon="mdi-format-bold" />
          <v-btn icon="mdi-format-italic" />
          <v-btn icon="mdi-format-underline" />
        </div>
        <div>
          <v-btn icon="mdi-upload" />
          <v-btn icon="mdi-link" />
        </div>
        <div>
          <v-btn
            icon="mdi-language-html5"
            @click="editor.setHtml(true)"
            :color="editor.html ? 'grey-lighten-2' : null"
          />
          <v-btn
            icon="mdi-xml"
            @click="editor.setHtml(false)"
            :color="!editor.html ? 'grey-lighten-2' : null"
          />
        </div>
      </div>
  
      <v-input
        v-if="editor.html"
        :hide-details="true"
        class="border"
      >
        <div class="w-100">
          <div
            class="pa-3"
            ref="editorRef"
            contenteditable
            @keyup="emit('update:modelValue', $event.target.innerHTML)"
          >&nbsp;</div>
          
          <div
            class="bg-blue-grey-darken-3 pa-3"
            v-if="!editor.html"
          >
            {{ props.modelValue }}
          </div>
        </div>
      </v-input>
  
      <monaco-editor
        v-if="!editor.html"
        style="min-height:100px; height:auto;"
        lang="html"
        :model-value="props.modelValue"
        @update:modelValue="emit('update:modelValue', $event)"
        :options="{
          theme: 'vs-dark',
          automaticLayout: true,
        }"
      />
    </div>
  </v-defaults-provider>
</template>

<script setup>
  import { ref, onMounted, watch, defineProps, defineEmits } from 'vue';

  const props = defineProps({
    modelValue: {
      type: [ String ],
      default: '',
    },
  });

  const emit = defineEmits(['update:modelValue']);
  const editorRef = ref(null);
  
  const editor = ref({
    html: true,
    setHtml(html) {
      this.html = html;
      setTimeout(() => {
        if (html && editorRef.value) {
          editorRef.value.innerHTML = props.modelValue;
        }
      }, 100);
    },
  });

  const setValueHandler = () => {
    const canUpdate = !(editorRef.value && (document.activeElement==editorRef.value || editorRef.value.contains(document.activeElement)));
    if (canUpdate && editorRef.value) { editorRef.value.innerHTML = props.modelValue; }
  };

  watch([ props ], ([ propsNew ]) => {
    setValueHandler();
  });

  onMounted(() => {
    setValueHandler();
  });
</script>