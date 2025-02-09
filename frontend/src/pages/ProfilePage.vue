<template>
  <q-page class="q-pa-sm">
    <div class="flex">
      <q-img src="123" width="200px" height="200px" style="border: 1px solid black" />
      <div>
        <q-input v-model="name" outlined label="Imie" />
        <q-input v-model="surname" outlined label="Nazwisko" />
      </div>
    </div>
  </q-page>
</template>

<script setup lang="ts">
import { useUserStore } from 'src/stores/user-store';
import { ref } from 'vue';

const userStore = useUserStore();

const name = ref(userStore.name);
const surname = ref(userStore.surname);
</script>
