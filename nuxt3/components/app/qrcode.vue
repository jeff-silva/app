<template>
  <img :src="qrcodeUrl" alt="">
</template>

<script>
export default {
  props: {
      modelValue: {default:""},
      size: {default:"300x300"},
  },
  computed: {
      qrcodeUrl() {
          return `https://chart.googleapis.com/chart?cht=qr&chs=${this.size}&chl=${this.modelValue}`;
      },
  },
}
</script>