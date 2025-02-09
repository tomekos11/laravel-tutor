<template>
  <q-page class="row items-center justify-evenly">
    <q-card
      square
      class="q-pa-sm relative-position q-pa-none bg-purple-3"
      style="width: 100vw; height: calc(100vh - 50px); overflow: hidden"
    >
      <div
        class="absolute-center row flex-center text-white"
        style="max-width: 35%; min-width: 400px; background: rgba(0, 0, 0, 0.7); z-index: 3"
      >
        <div class="col-10">
          <login-tile v-if="$route.name === 'login'" />
          <register-tile v-else-if="$route.name === 'register'" />
        </div>
      </div>
      <svg
        style="z-index: 2; filter: drop-shadow(0px 0px 12px black)"
        class="absolute-bottom"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 1440 320"
      >
        <defs>
          <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" style="stop-color: #6a1b9a; stop-opacity: 1" />
            <stop offset="80%" style="stop-color: #c73fdf; stop-opacity: 1" />
            <stop offset="100%" style="stop-color: #e92ed6; stop-opacity: 1" />
          </linearGradient>
        </defs>
        <path
          fill="url(#gradient)"
          fill-opacity="1"
          d="M0,128L120,160C240,192,480,256,720,234.7C960,213,1200,107,1320,53.3L1440,0L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"
        />
      </svg>
      <q-img
        v-if="$q.screen.gt.sm"
        :src="leftImgSrc"
        class="absolute-center"
        fit="fill"
        decoding="sync"
        height="200"
      />
    </q-card>
  </q-page>
</template>

<script setup lang="ts">
import leftImgSrc from '../assets/login-background.jpg';
import LoginTile from 'src/components/LoginTile.vue';
import RegisterTile from 'src/components/RegisterTile.vue';

let a = 'xd';

a = '123';

console.log(a);
</script>
