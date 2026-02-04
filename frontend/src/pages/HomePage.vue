<template>
  <q-page class="bg-slate-900 text-slate-50">
    <!-- HERO -->
    <section class="max-w-7xl mx-auto px-6 pt-16 pb-24">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div>
          <div class="inline-flex items-center gap-2 rounded-full bg-slate-800/70 px-3 py-1 mb-4 text-xs uppercase tracking-wide text-sky-300">
            <span class="h-2 w-2 rounded-full bg-sky-400"></span>
            Nowa jakość korepetycji
          </div>

          <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight mb-4">
            Inteligentne dopasowanie
            <span class="text-sky-400">uczniów i korepetytorów</span>
          </h1>

          <p class="text-slate-200 text-base sm:text-lg max-w-xl mb-6">
            Nie jesteśmy zwykłym katalogiem ogłoszeń. Nasz system dobiera korepetytorów do uczniów
            na podstawie celów, stylu nauki i dostępności – tak, aby każda godzina naprawdę miała sens.
          </p>

          <p class="text-slate-300 text-sm sm:text-base max-w-xl mb-8">
            Udostępniamy pełną platformę e‑learningową z materiałami, zadaniami, testami i notatkami.
            Wszystko, czego potrzebuje uczeń <span class="font-semibold">i</span> korepetytor – w jednym miejscu.
            Dzięki temu mamy pewność, że proces nauki jest spójny od pierwszego kontaktu aż po ostatni egzamin.
          </p>

          <div class="flex flex-wrap gap-3">
            <q-btn
              color="primary"
              unelevated
              no-caps
              class="px-6 py-2 font-semibold rounded-full bg-sky-500 hover:bg-sky-400"
              label="Znajdź idealnego korepetytora"
              :to="{
                name: 'find-tutor'
              }"
            />
            <q-btn
              flat
              color="white"
              no-caps
              :to="{
                name: 'become-tutor'
              }"
              class="px-4 py-2 font-semibold rounded-full border border-slate-600 hover:bg-slate-800"
              label="Zostań korepetytorem"
            />
          </div>

          <div class="flex flex-wrap items-center gap-4 mt-6 text-xs text-slate-400">
            <div class="flex items-center gap-2">
              <q-icon name="verified" color="green-4" size="18px" />
              Zweryfikowani korepetytorzy
            </div>
            <div class="flex items-center gap-2">
              <q-icon name="lock" color="blue-4" size="18px" />
              Bezpieczne płatności i rezerwacje
            </div>
          </div>
        </div>

        <!-- Hero ilustracja / mock panelu -->
        <div class="relative">
          <div class="absolute -inset-4 bg-sky-500/20 blur-3xl rounded-3xl"></div>

          <div class="relative rounded-3xl bg-slate-800 border border-slate-700 p-5 shadow-2xl">
            <div class="flex justify-between items-center mb-4">
              <div>
                <p class="text-xs uppercase text-slate-400 tracking-wide">
                  Szybkie dopasowanie
                </p>
                <p class="text-sm font-semibold">
                  Propozycje korepetytorów dla Ciebie
                </p>
              </div>
              <q-chip dense color="sky-500" text-color="white" class="text-xs">
                3 dopasowania w 15s
              </q-chip>
            </div>

            <div class="space-y-3">
              <div
                v-for="i in 3"
                :key="i"
                class="flex items-center justify-between rounded-2xl bg-slate-900/60 px-3 py-2"
              >
                <div class="flex items-center gap-3">
                  <q-avatar size="40px">
                    <img :src="`https://i.pravatar.cc/100?img=${20 + i}`" alt="" />
                  </q-avatar>
                  <div>
                    <p class="text-sm font-semibold">
                      {{ i === 1 ? 'Anna, matematyka' : i === 2 ? 'Marek, informatyka' : 'Kasia, języki' }}
                    </p>
                    <p class="text-xs text-slate-400">
                      Dopasowanie do Twoich celów: <span class="text-sky-300">98%</span>
                    </p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-sm font-semibold">
                    {{ i === 1 ? '120 zł / h' : i === 2 ? '150 zł / h' : '95 zł / h' }}
                  </p>
                  <div class="flex items-center justify-end gap-1 text-xs text-amber-300">
                    <q-icon name="star" size="16px" />
                    <span>{{ i === 1 ? '4.9' : i === 2 ? '4.8' : '4.7' }}/5</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="mt-4 flex items-center justify-between text-xs text-slate-400">
              <span>Filtrujemy po stylu nauki, poziomie i dostępności</span>
              <span class="text-sky-300 font-semibold">Zobacz wszystkich</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- STATS -->
    <section class="bg-slate-950/60 border-y border-slate-800">
      <div class="max-w-5xl mx-auto px-6 py-10">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
          <div class="bg-slate-900 rounded-2xl p-6 flex flex-col gap-2 items-start">
            <p class="text-xs uppercase text-slate-400 tracking-wide">
              Zaufało nam
            </p>
            <p class="text-3xl font-extrabold text-sky-400">
              12 500+
            </p>
            <p class="text-sm text-slate-300">
              uczniów, którzy zrealizowali z nami ponad <span class="font-semibold">180 000 godzin</span> zajęć.
            </p>
          </div>

          <div class="bg-slate-900 rounded-2xl p-6 flex flex-col gap-2 items-start">
            <p class="text-xs uppercase text-slate-400 tracking-wide">
              Sieć korepetytorów
            </p>
            <p class="text-3xl font-extrabold text-sky-400">
              1 300+
            </p>
            <p class="text-sm text-slate-300">
              zweryfikowanych korepetytorów z matematyki, informatyki, języków, przedmiotów ścisłych i humanistycznych.
            </p>
          </div>

          <div class="bg-slate-900 rounded-2xl p-6 flex flex-col gap-2 items-start">
            <p class="text-xs uppercase text-slate-400 tracking-wide">
              Skuteczność
            </p>
            <p class="text-3xl font-extrabold text-sky-400">
              94%
            </p>
            <p class="text-sm text-slate-300">
              uczniów poprawia swoje wyniki w ciągu pierwszych <span class="font-semibold">8 tygodni</span> regularnych zajęć.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- DLACZEGO JESTEŚMY INNI -->
    <section class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-3 gap-10">
      <div class="md:col-span-1">
        <h2 class="text-2xl font-bold mb-3 flex items-center gap-2">
          <q-icon name="hub" color="sky-400" size="56px" />
          Nie kolejny portal z ogłoszeniami
        </h2>
        <p class="text-slate-300 text-sm leading-relaxed">
          Zamiast zostawiać Cię sam na sam z dziesiątkami profili, prowadzimy Cię przez cały proces –
          od diagnozy potrzeb, przez dopasowanie korepetytora, aż po weryfikację postępów.
        </p>
      </div>

      <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Mądre dopasowanie, nie tylko filtr -->
        <div class="bg-slate-900 rounded-2xl p-5">
          <div class="flex items-center gap-2 mb-2">
            <q-icon name="tune" color="sky-400" size="72px" />
            <h3 class="text-sm font-semibold">Mądre dopasowanie, nie tylko filtr</h3>
          </div>
          <p class="text-xs text-slate-300 leading-relaxed">
            Bierzemy pod uwagę cele (matura, kolokwium, olimpiada), poziom wyjściowy, dostępność
            oraz preferowany styl nauki. Dzięki temu łączymy uczniów z korepetytorami, którzy naprawdę
            rozumieją ich potrzeby – nie tylko mają wolny termin.
          </p>
        </div>

        <!-- Platforma e‑learningowa w pakiecie -->
        <div class="bg-slate-900 rounded-2xl p-5">
          <div class="flex items-center gap-2 mb-2">
            <q-icon name="cast_for_education" color="sky-400" size="72px" />
            <h3 class="font-semibold">Platforma e‑learningowa w pakiecie</h3>
          </div>
          <p class="text-xs text-slate-300 leading-relaxed">
            Lekcje online, nagrania, tablica, zadania domowe, testy i notatki – wszystko w jednym
            miejscu. Udostępniamy narzędzia zarówno uczniom, jak i korepetytorom, więc nikt nie musi
            żonglować kilkoma aplikacjami naraz.
          </p>
        </div>

        <!-- Materiały dopasowane do programu -->
        <div class="bg-slate-900 rounded-2xl p-5">
          <div class="flex items-center gap-2 mb-2">
            <q-icon name="menu_book" color="sky-400" size="72px" />
            <h3 class="text-sm font-semibold">Materiały dopasowane do programu</h3>
          </div>
          <p class="text-xs text-slate-300 leading-relaxed">
            Baza zadań, arkuszy egzaminacyjnych i kart pracy ułożona pod polską podstawę programową
            oraz typowe wymagania uczelni. Korepetytor ma wszystko pod ręką, a uczeń dokładnie wie,
            nad czym aktualnie pracuje.
          </p>
        </div>

        <!-- Przejrzystość i bezpieczeństwo -->
        <div class="bg-slate-900 rounded-2xl p-5">
          <div class="flex items-center gap-2 mb-2">
            <q-icon name="verified_user" color="sky-400" size="72px" />
            <h3 class="text-sm font-semibold">Przejrzystość i bezpieczeństwo</h3>
          </div>
          <p class="text-xs text-slate-300 leading-relaxed">
            Jasne zasady rozliczeń, przejrzysty kalendarz zajęć i historia płatności. Możesz łatwo
            odwołać lub przełożyć lekcję, a wszystkie zmiany są widoczne dla obu stron w czasie rzeczywistym.
          </p>
        </div>
      </div>
    </section>


    <!-- CTA KOŃCOWE -->
    <section class="border-t border-slate-800">
      <div class="max-w-4xl mx-auto px-6 py-12 text-center">
        <h2 class="text-2xl font-bold mb-3">
          Gotowy, żeby ruszyć z nauką na serio?
        </h2>
        <p class="text-slate-300 text-sm max-w-2xl mx-auto mb-6">
          Opowiedz nam o swoich celach, a w kilka minut zaproponujemy korepetytorów,
          którzy pasują do Ciebie nie tylko terminami, ale też sposobem pracy.
        </p>
        <div class="flex justify-center gap-3">
          <q-btn
            color="primary"
            unelevated
            no-caps
            class="px-6 py-2 font-semibold rounded-full bg-sky-500 hover:bg-sky-400"
            label="Znajdź korepetytora"
          />
          <q-btn
            flat
            color="white"
            no-caps
            class="px-4 py-2 font-semibold rounded-full border border-slate-600 hover:bg-slate-800"
            label="Zobacz, jak to działa"
            :to="{
              name: 'about-us'
            }"
          />
        </div>
      </div>
    </section>
  </q-page>
</template>

<script setup lang="ts">
import Tutors from 'src/components/tutors-listing/Tutors.vue';

  
</script>