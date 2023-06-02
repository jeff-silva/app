export default defineNuxtPlugin(async (nuxtApp) => {
  nuxtApp.provide('filter', {
    numberFilesize(size) {
      size = parseInt(size);
      let i = size == 0 ? 0 : Math.floor(Math.log(size) / Math.log(1024));
      return (size / Math.pow(1024, i)).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
    },
  });
});