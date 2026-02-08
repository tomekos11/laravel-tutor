<template>
  <q-layout view="lHh Lpr lff">
    <q-header elevated>
      <q-toolbar class="bg-slate-950">
        <q-toolbar-title>
          <router-link
            :to="{ name: 'home' }"
            class="text-h6 text-decoration-none d-flex items-center"
          >
            <q-icon
              name="hotel_class"
              class="text-h4"
            />
            Tutor App
          </router-link>
        </q-toolbar-title>

        <!-- <template v-if="userStore.isDataFetched"> -->

        <div class="d-flex q-mx-xl gap-20">
          <q-btn
            to="/"
            class="text-white q-px-md"
            dense
            unelevated
            no-caps
          >
            Baza zadań
          </q-btn>
  
          <q-btn
            to="/"
            class="text-white q-px-md"
            dense
            unelevated
            no-caps
          >
            Homework
          </q-btn>
  
  
          <q-btn
            class="text-white q-px-md"
            dense
            unelevated
            no-caps
            :to="{
              name: 'become-tutor'
            }"
          >
            Zostań korepetytorem
          </q-btn>
  
        </div>
  
        <q-btn
          v-if="userStore.isUserLogged"
          rounded
          dense
          icon="person"
          class="bg-purple-1 text-grey-9"
        >
  
          <q-menu>
            <q-list>
              <q-item class="bg-purple-4 text-dark text-center">
                <q-item-section>
                  <template v-if="userStore.name">
                    {{ userStore.name }} {{ userStore.surname }}
                  </template>
                  <template v-else>
                    {{ userStore.username }}
                  </template>
                </q-item-section>
              </q-item>
              <q-item clickable :to="{name: 'profile'}">
                <q-item-section side>
                  <q-icon name="face" />
                </q-item-section>
                <q-item-section> Edytuj Profil </q-item-section>
              </q-item>
              <q-item clickable @click="logout">
                <q-item-section side>
                  <q-icon name="logout" />
                </q-item-section>
                <q-item-section> Wyloguj</q-item-section>
              </q-item>
            </q-list>
          </q-menu>
        </q-btn>
  
        <router-link
          v-else
          to="/login"
          class="q-ml-xl text-white text-bold"
        >
          Zaloguj
        </router-link>
        <!-- </template> -->

      </q-toolbar>
    </q-header>

    <q-page-container>
      <router-view />
    </q-page-container>

    <q-footer>
      <Footer />
    </q-footer>
  </q-layout>
</template>

<script setup lang="ts">
import { api } from 'src/boot/axios';
import Footer from 'src/components/Footer.vue';
import { useUserStore } from 'src/stores/user-store';

const userStore = useUserStore()

const logout = async() => {
  try {
    await api.get('/api/logout');
    userStore.clearUser();
  } catch (err) {
    console.log(err)
  }
  
}

</script>