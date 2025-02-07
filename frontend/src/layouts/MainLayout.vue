<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar class="bg-purple-14">
        <q-toolbar-title>
          <router-link
            :to="{ name: 'login' }"
            class="text-h6 text-decoration-none d-flex items-center"
          >
            <q-icon
              name="hotel_class"
              class="text-h4"
            />
            Tutor App
          </router-link>
        </q-toolbar-title>

        <div class="d-flex q-mx-xl gap-20">
          <router-link
            to="/"
            class="text-white"
          >
            Zajęcia
          </router-link>
          <router-link
            to="/"
            class="text-white"
          >
            O nas
          </router-link>
          <router-link
            to="/"
            class="q-ml-xl text-white text-bold"
          >
            Zostań korepetytorem
          </router-link>
        </div>

        <q-btn
          v-if="useUserStore().isUserLogged"
          rounded
          dense
          icon="person"
          class="bg-purple-1 text-grey-9"
        />

        <router-link
          v-else
            to="/login"
            class="q-ml-xl text-white text-bold"
          >
            Zaloguj
          </router-link>

      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { useUserStore } from 'src/stores/user-store';

const userStore = useUserStore()
</script>