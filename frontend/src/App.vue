<template>
  <router-view />
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { api } from './boot/axios';
import { useUserStore } from './stores/user-store';

onMounted(async () => {
  try {
    const res = await api.get('/api/me');
    if (res.data.success) {
      useUserStore().setUser(res.data.user);
    }
  } catch (err) {
    console.error(err);
  }
});
</script>
