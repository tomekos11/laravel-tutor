<template>
  <q-page class="bg-slate-900 text-slate-50">
    <!-- HERO / INTRO -->
    <section class="max-w-7xl mx-auto px-6 pt-16 pb-12">
      <div>
        <div class="inline-flex items-center gap-2 rounded-full bg-slate-800/70 px-3 py-1 mb-4 text-xs uppercase tracking-wide text-sky-300">
          <span class="h-2 w-2 rounded-full bg-sky-400"></span>
          Jak działa nasza platforma
        </div>

        <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight mb-4">
          Przejrzysty proces,
          <span class="text-sky-400">od pierwszego kliknięcia do efektów</span>
        </h1>

        <p class="text-slate-200 text-sm sm:text-base max-w-xl mb-6">
          Łączymy inteligentne dopasowywanie korepetytorów, dopracowaną platformę e‑learningową
          i pełną transparentność, żeby obie strony dokładnie wiedziały, z kim współpracują i nad czym pracują.
        </p>

        <div class="flex flex-wrap gap-3">
          <q-btn
            color="primary"
            unelevated
            class="px-6 py-2 font-semibold rounded-full bg-sky-500 hover:bg-sky-400"
            label="Rozpocznij dobieranie korepetytora"
            :to="{
              name: 'find-tutor'
            }"
          />
          <q-btn
            flat
            color="white"
            class="px-4 py-2 font-semibold rounded-full border border-slate-600 hover:bg-slate-800"
            label="Zobacz przykładową lekcję"
          />
        </div>
      </div>
    </section>

    <!-- Stepper / Jak to działa w skrócie -->
    <section class="max-w-7xl mx-auto px-6 pt-16 pb-12">
      <div class="relative">
        <div class="absolute -inset-4 bg-sky-500/20 blur-3xl rounded-3xl"></div>

        <div class="relative rounded-3xl bg-slate-800 border border-slate-700 p-6 shadow-2xl">
          <p class="text-xs uppercase text-slate-400 tracking-wide mb-3">
            3 kroki do startu
          </p>

          <div class="space-y-4">
            <div class="flex items-start gap-3">
              <div class="h-7 w-7 rounded-full bg-sky-500 flex items-center justify-center text-slate-900 text-sm font-bold">
                1
              </div>
              <div>
                <p class="text-sm font-semibold">Opowiedz nam o swoich celach</p>
                <p class="text-xs text-slate-300">
                  Poziom, przedmioty, terminy, styl nauki – na tej podstawie konfigurujemy Twoje dopasowanie.
                </p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <div class="h-7 w-7 rounded-full bg-sky-500/60 flex items-center justify-center text-slate-900 text-sm font-bold">
                2
              </div>
              <div>
                <p class="text-sm font-semibold">Proponujemy korepetytorów</p>
                <p class="text-xs text-slate-300">
                  Otrzymujesz listę dopasowanych nauczycieli z jasnym opisem doświadczenia, specjalizacji i formy zajęć.
                </p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <div class="h-7 w-7 rounded-full bg-emerald-500 flex items-center justify-center text-slate-900 text-sm font-bold">
                3
              </div>
              <div>
                <p class="text-sm font-semibold">Rezerwujesz i uczysz się na naszej platformie</p>
                <p class="text-xs text-slate-300">
                  Terminy, płatności, lekcje online i materiały – wszystko ogarniasz w jednym panelu.
                </p>
              </div>
            </div>
          </div>

          <div class="mt-5 flex items-center justify-between text-xs text-slate-400">
            <span>Potrzebujesz czegoś niestandardowego? Napisz do nas.</span>
            <span class="text-sky-300 font-semibold">Pomoc dla rodziców</span>
          </div>
        </div>
      </div>
    </section>

    <!-- SEKCJA: Dobieranie korepetytora -->
    <section class="bg-slate-950/60 border-y border-slate-800">
      <div class="max-w-7xl mx-auto px-6 py-14 grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
        <div>
          <h2 class="text-2xl font-bold mb-3 flex items-center gap-2">
            <q-icon name="psychology_alt" color="sky-400" size="30px" />
            Jak dobieramy korepetytora do ucznia
          </h2>
          <p class="text-slate-300 text-sm leading-relaxed mb-5">
            Zamiast przypadkowego scrollowania profili, prowadzimy Cię przez krótki, konkretny wywiad.
            Pytamy o cele, poziom, terminy, budżet i preferowany styl pracy – na tej podstawie nasz algorytm
            i zespół rekomendują korepetytorów, którzy naprawdę pasują do Twojej sytuacji.
          </p>

          <div class="space-y-3">
            <div class="flex items-start gap-3">
              <q-icon name="flag" color="sky-400" size="22px" class="mt-1 shrink-0" />
              <div>
                <p class="text-sm font-semibold">Cele i terminy na pierwszym miejscu</p>
                <p class="text-xs text-slate-300">
                  Matura, kolokwium, powtórka roku, olimpiada czy nadrobienie zaległości – dopasowanie startuje od tego, co chcesz osiągnąć i w jakim czasie.
                </p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <q-icon name="person_search" color="sky-400" size="22px" class="mt-1 shrink-0" />
              <div>
                <p class="text-sm font-semibold">Styl nauki, który naprawdę Ci leży</p>
                <p class="text-xs text-slate-300">
                  Podchodzisz zadaniowo, potrzebujesz tłumaczenia „jak dla człowieka”, a może lubisz dowody i teorię?
                  Dopasowujemy korepetytorów także po sposobie tłumaczenia i prowadzenia zajęć.
                </p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <q-icon name="calendar_month" color="sky-400" size="22px" class="mt-1 shrink-0" />
              <div>
                <p class="text-sm font-semibold">Dostępność i forma zajęć</p>
                <p class="text-xs text-slate-300">
                  Online, stacjonarnie, 1‑na‑1 lub mała grupa – pokazujemy tylko tych korepetytorów, którzy
                  faktycznie mogą Cię przyjąć w wybranym trybie i godzinach.
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Kafelek z przykładowym dopasowaniem -->
        <div class="rounded-3xl bg-slate-900 border border-slate-800 p-6 shadow-xl">
          <p class="text-xs uppercase text-slate-400 tracking-wide mb-3">
            Przykładowe dopasowanie
          </p>

          <div class="space-y-4">
            <div class="bg-slate-800/70 rounded-2xl p-4 flex items-start gap-3">
              <div class="w-10 h-10 rounded-full bg-sky-500/20 flex items-center justify-center">
                <q-icon name="school" color="sky-400" size="22px" />
              </div>
              <div class="flex-1">
                <p class="text-sm font-semibold mb-1">
                  „Matura rozszerzona z matematyki, poziom ok. 50%”
                </p>
                <p class="text-xs text-slate-300">
                  Uczeń szuka zajęć 1× w tygodniu, wieczorami, z naciskiem na zadania z arkuszy maturalnych.
                </p>
              </div>
            </div>

            <div class="bg-slate-800/70 rounded-2xl p-4 flex items-start gap-3">
              <q-avatar size="44px">
                <img src="https://i.pravatar.cc/120?img=15" alt="Korepetytor" />
              </q-avatar>
              <div class="flex-1">
                <div class="flex items-center justify-between mb-1">
                  <p class="text-sm font-semibold">Marta • Matematyka</p>
                  <div class="flex items-center gap-1 text-xs text-amber-300">
                    <q-icon name="star" size="16px" />
                    <span>4.9 / 5</span>
                  </div>
                </div>
                <p class="text-xs text-slate-300 mb-1">
                  6 lat doświadczenia, przygotowanie do matury, praca na arkuszach CKE i autorskich zestawach.
                </p>
                <p class="text-xs text-sky-300">
                  Dopasowanie do ucznia: 97% (cele, poziom, styl pracy, terminy).
                </p>
              </div>
            </div>
          </div>

          <div class="mt-5 flex items-center justify-between text-xs text-slate-400">
            <span>Możesz porównać kilku korepetytorów obok siebie.</span>
            <span class="text-sky-300 font-semibold">Zobacz więcej dopasowań</span>
          </div>
        </div>
      </div>
    </section>

    <!-- SEKCJA: Platforma e‑learningowa -->
    <section class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
      <div class="order-2 lg:order-1">
        <h2 class="text-2xl font-bold mb-3 flex items-center gap-2">
          <q-icon name="cast_for_education" color="sky-400" size="30px" />
          Platforma e‑learningowa, która ogarnia wszystko
        </h2>
        <p class="text-slate-300 text-sm leading-relaxed mb-5">
          Nasza platforma nie jest tylko komunikatorem wideo. To kompletne środowisko do pracy nad
          materiałem: od pierwszej lekcji, przez zadania domowe, aż po powtórkę przed egzaminem.
          Uczeń i korepetytor widzą ten sam plan działania i postępy w jednym miejscu.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="bg-slate-900 rounded-2xl p-4">
            <div class="flex items-center gap-2 mb-2">
              <q-icon name="video_chat" color="sky-400" size="22px" />
              <p class="text-sm font-semibold">Lekcje online i tablica</p>
            </div>
            <p class="text-xs text-slate-300 leading-relaxed">
              Wbudowany pokój do zajęć z interaktywną tablicą, udostępnianiem materiałów i notatek.
            </p>
          </div>

          <div class="bg-slate-900 rounded-2xl p-4">
            <div class="flex items-center gap-2 mb-2">
              <q-icon name="task_alt" color="sky-400" size="22px" />
              <p class="text-sm font-semibold">Zadania i testy</p>
            </div>
            <p class="text-xs text-slate-300 leading-relaxed">
              Korepetytor przydziela zadania domowe, a uczeń ma jasną listę rzeczy do zrobienia przed kolejną lekcją.
            </p>
          </div>

          <div class="bg-slate-900 rounded-2xl p-4">
            <div class="flex items-center gap-2 mb-2">
              <q-icon name="folder_special" color="sky-400" size="22px" />
              <p class="text-sm font-semibold">Materiały w jednym miejscu</p>
            </div>
            <p class="text-xs text-slate-300 leading-relaxed">
              Arkusze, prezentacje, notatki i nagrania zajęć – wszystko przypięte do konkretnych tematów i lekcji.
            </p>
          </div>

          <div class="bg-slate-900 rounded-2xl p-4">
            <div class="flex items-center gap-2 mb-2">
              <q-icon name="insights" color="sky-400" size="22px" />
              <p class="text-sm font-semibold">Postęp pod kontrolą</p>
            </div>
            <p class="text-xs text-slate-300 leading-relaxed">
              Widzisz, jakie tematy zostały już przerobione, co idzie dobrze, a co wymaga jeszcze powtórki.
            </p>
          </div>
        </div>
      </div>

      <!-- Mock panel platformy -->
      <div class="order-1 lg:order-2">
        <div class="relative">
          <div class="absolute -inset-4 bg-sky-500/20 blur-3xl rounded-3xl"></div>
          <div class="relative rounded-3xl bg-slate-800 border border-slate-700 p-6 shadow-2xl">
            <div class="flex items-center justify-between mb-4">
              <div>
                <p class="text-xs uppercase text-slate-400 tracking-wide">Twoja lekcja</p>
                <p class="text-sm font-semibold">Analiza funkcji – zadania maturalne</p>
              </div>
              <q-chip dense color="sky-500" text-color="white" class="text-xs">
                60 min • Online
              </q-chip>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
              <div class="bg-slate-900/70 rounded-2xl p-3">
                <p class="text-xs text-slate-400 mb-1">Plan lekcji</p>
                <ul class="text-xs text-slate-200 space-y-1">
                  <li>• Powtórka definicji i własności</li>
                  <li>• 3 zadania z arkuszy CKE</li>
                  <li>• Zadanie domowe: 5 zadań otwartych</li>
                </ul>
              </div>
              <div class="bg-slate-900/70 rounded-2xl p-3">
                <p class="text-xs text-slate-400 mb-1">Materiały</p>
                <ul class="text-xs text-slate-200 space-y-1">
                  <li>• „Funkcje – ściąga.pdf”</li>
                  <li>• „Zadania – zbiór.docx”</li>
                  <li>• Nagranie poprzedniej lekcji</li>
                </ul>
              </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-300">
              <div class="flex items-center gap-2">
                <q-icon name="check_circle" color="emerald-400" size="18px" />
                <span>Połączenie testowe OK</span>
              </div>
              <span class="text-sky-300 font-semibold">Dołącz do lekcji</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SEKCJA: Weryfikacja i transparentność -->
    <section class="bg-slate-950/60 border-t border-slate-800">
      <div class="max-w-7xl mx-auto px-6 py-14 grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-1">
          <div class="flex">
            <q-icon name="verified_user" color="sky-400" size="144px" />
            <h2 class="text-2xl font-bold mb-3 flex items-center gap-2">
              Weryfikujemy dokumenty, budujemy zaufanie
            </h2>
          </div>
          <p class="text-slate-300 text-sm leading-relaxed mb-4">
            Potwierdzamy wszystkie dostarczone przez korepetytorów dokumenty – dyplomy, certyfikaty,
            informacje o doświadczeniu. Dzięki temu obie strony wiedzą, z kim mają do czynienia,
            zanim zaczną współpracę.
          </p>
          <p class="text-slate-400 text-xs leading-relaxed">
            Status weryfikacji jest widoczny na profilu, a każda strona ma dostęp do przejrzystej historii współpracy
            i wystawionych opinii.
          </p>
        </div>

        <!-- 2 wiersze x 3 kolumny -->
        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
          <!-- 1 -->
          <div class="bg-slate-900 rounded-2xl p-5">
            <div class="flex items-center gap-2 mb-2">
              <q-icon name="badge" color="sky-400" size="32px" />
              <p class="text-sm font-semibold">Profil korepetytora</p>
            </div>
            <p class="text-xs text-slate-300 leading-relaxed">
              Jasno opisane wykształcenie, doświadczenie, specjalizacje i poziomy, z którymi pracuje dany korepetytor.
            </p>
          </div>

          <!-- 2 -->
          <div class="bg-slate-900 rounded-2xl p-5">
            <div class="flex items-center gap-2 mb-2">
              <q-icon name="fact_check" color="sky-400" size="32px" />
              <p class="text-sm font-semibold">Weryfikacja dokumentów</p>
            </div>
            <p class="text-xs text-slate-300 leading-relaxed">
              Dokumenty i informacje są sprawdzane, a status weryfikacji widoczny jest na profilu nauczyciela.
            </p>
          </div>

          <!-- 3 -->
          <div class="bg-slate-900 rounded-2xl p-5">
            <div class="flex items-center gap-2 mb-2">
              <q-icon name="visibility" color="sky-400" size="32px" />
              <p class="text-sm font-semibold">Transparentne opinie i historia</p>
            </div>
            <p class="text-xs text-slate-300 leading-relaxed">
              Uczniowie widzą oceny i opinie wystawione po zajęciach, a korepetytor widzi historię współpracy z uczniem.
            </p>
          </div>

          <!-- 4 -->
          <div class="bg-slate-900 rounded-2xl p-5">
            <div class="flex items-center gap-2 mb-2">
              <q-icon name="handshake" color="sky-400" size="32px" />
              <p class="text-sm font-semibold">Dwustronna weryfikacja</p>
            </div>
            <p class="text-xs text-slate-300 leading-relaxed">
              Korepetytor widzi, z kim pracuje: poziom ucznia, historię zajęć i oczekiwania, zanim zaakceptuje współpracę.
            </p>
          </div>

          <!-- 5 -->
          <div class="bg-slate-900 rounded-2xl p-5">
            <div class="flex items-center gap-2 mb-2">
              <q-icon name="gavel" color="sky-400" size="32px" />
              <p class="text-sm font-semibold">Jasne zasady współpracy</p>
            </div>
            <p class="text-xs text-slate-300 leading-relaxed">
              Regulamin, polityka odwołań i zasady płatności są proste i czytelne dla obu stron jeszcze przed pierwszą lekcją.
            </p>
          </div>

          <!-- 6 -->
          <div class="bg-slate-900 rounded-2xl p-5">
            <div class="flex items-center gap-2 mb-2">
              <q-icon name="security" color="sky-400" size="32px" />
              <p class="text-sm font-semibold">Bezpieczne płatności</p>
            </div>
            <p class="text-xs text-slate-300 leading-relaxed">
              Rozliczenia odbywają się przez 1zabezpieczone płatności online, a historia faktur i sesji jest zawsze dostępna.
            </p>
          </div>
        </div>
      </div>
    </section>

  </q-page>
</template>
