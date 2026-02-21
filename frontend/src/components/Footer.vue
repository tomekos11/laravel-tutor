<template>
  <footer class="bg-slate-950 border-t border-slate-800">
    <!-- CTA tylko dla niezalogowanych -->
    <div
      v-if="!userStore.isUserLogged"
      class="max-w-6xl mx-auto px-6 py-10 flex flex-col md:flex-row md:items-center gap-6"
    >
      <div class="flex-1">
        <h2 class="text-xl md:text-2xl font-bold mb-2">
          Gotowy, żeby ruszyć z nauką na serio?
        </h2>
        <p class="text-slate-300 text-sm max-w-xl">
          Opowiedz nam o swoich celach, a w kilka minut zaproponujemy korepetytorów,
          którzy pasują do Ciebie nie tylko terminami, ale też sposobem pracy.
        </p>
      </div>

      <div class="flex flex-col sm:flex-row gap-3">
        <q-btn
          color="primary"
          unelevated
          class="bg-sky-500 hover:bg-sky-400 px-5"
          label="Znajdź korepetytora"
          no-caps
          :to="{ name: 'find-tutor' }"
        />
        <q-btn
          flat
          color="white"
          class="border border-slate-600 hover:bg-slate-800 px-4"
          label="Zobacz, jak to działa"
          no-caps
          :to="{ name: 'about-us' }"
        />
      </div>
    </div>

    <!-- Dolny pasek: kontakt + social (zawsze; dla zalogowanych to jedyna część stopki) -->
    <div
      :class="{ 'border-t border-slate-800': !userStore.isUserLogged }"
    >
      <div class="max-w-6xl mx-auto px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
        <div class="flex items-center gap-2">
          <q-icon name="mail" size="18px" />
          <a href="mailto:kontakt@korepmatch.pl" class="hover:text-sky-300">
            kontakt@korepmatch.pl
          </a>
        </div>

        <div class="flex items-center gap-3">
          <span class="hidden sm:inline-block mr-1">Znajdziesz nas też tutaj:</span>

          <q-btn
            flat
            round
            dense
            color="white"
            icon="ondemand_video"
            class="hover:text-sky-300"
            type="a"
            href="https://youtube.com/"
            target="_blank"
          />
          <q-btn
            flat
            round
            dense
            color="white"
            icon="done"
            class="hover:text-sky-300"
            type="a"
            href="https://facebook.com/"
            target="_blank"
          />
          <q-btn
            flat
            round
            dense
            color="white"
            icon="close"
            class="hover:text-sky-300"
            type="a"
            href="https://linkedin.com/"
            target="_blank"
          />
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { useUserStore } from 'src/stores/user-store';

defineOptions({ name: 'AppFooter' });
const userStore = useUserStore();
</script>
