<template>
  <v-row>
    <v-col cols="12">
      <v-file-input label="PDF Linkedin" @input="onInput($event)" />
    </v-col>
    <v-col cols="12">
      <app-dd>$data: {{ $data }}</app-dd>
    </v-col>
  </v-row>
</template>

<script>
  export default {
    data() {
      return {
        linkedinData: false,
      };
    },

    methods: {
      onInput(ev) {
        const file = ev.target.files[0] || false;
        if (! file) return;
        const reader = new FileReader();
        reader.addEventListener('load', (event) => {
          console.log(event.target.result);
        });
        // reader.readAsDataURL(file);
        reader.readAsText(file);
        // reader.readAsBinaryString(file);
        // reader.readAsArrayBuffer(file);
        // console.log(reader);
      },
    },
  };
</script>