<template>
  {{ user }}
  {{ error }}
  <router-view />
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { api } from './boot/axios';

const user = ref<any>(null);
const error = ref<string | null>(null);

onMounted(async () => {
  try {
    const res = await api.get('/api/me');
    if (res.data.success) {
      user.value = res.data.user; 
    } else {
      error.value = res.data.message || 'Unknown error';
    }
  } catch (err) {
    error.value = 'Failed to fetch user data';
  }
});

</script>
