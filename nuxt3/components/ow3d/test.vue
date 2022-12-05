<template>
  <div>
    ow3d-test
    <app-dd>player: {{ player }}</app-dd>
  </div>
</template>

<script setup>
  import { useGeolocation, useMagicKeys, watchDebounced } from '@vueuse/core';
  const geo = useGeolocation();

  const playerSpeed = 15;
  const player = ref({
    name: 'John',
    lat: 0,
    lng: 0,
  });

  setTimeout(() => {
    player.value.lat = geo.coords.value.latitude;
    player.value.lng = geo.coords.value.longitude;
  }, 100);

  const playerSetPosition = (coords = {}) => {
    for(let k in coords) {
      player.value[k] += coords[k];
    }
  };

  watchDebounced(player.value, () => {
    console.log('Aaaa');
  }, { debounce: 1000, maxWait: 1000 });
  
  const { up, down, left, right, w, a, s, d } = useMagicKeys();
  watch([w, up],    (v) => v? playerSetPosition({ lat: -playerSpeed }): null);
  watch([s, down],  (v) => v? playerSetPosition({ lat: playerSpeed }): null);
  watch([a, left],  (v) => v? playerSetPosition({ lng: -playerSpeed }): null);
  watch([d, right], (v) => v? playerSetPosition({ lng: playerSpeed }): null);
</script>