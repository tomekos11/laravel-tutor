<template>
  <q-page class="bg-slate-900 text-slate-50">
    <!-- HERO / INTRO -->
    <section class="max-w-5xl mx-auto px-6 pt-16 pb-10">
      <div class="mb-6">
        <div class="inline-flex items-center gap-2 rounded-full bg-slate-800/70 px-3 py-1 mb-4 text-xs uppercase tracking-wide text-sky-300">
          <span class="h-2 w-2 rounded-full bg-sky-400"></span>
          Dołącz jako korepetytor
        </div>

        <h1 class="!text-3xl sm:text-4xl font-extrabold leading-tight mb-4 flex items-center gap-2">
          <q-icon name="school" color="sky-400" size="32px" />
          Zostań korepetytorem na naszej platformie
        </h1>

        <p class="text-slate-200 !text-sm sm:text-base max-w-2xl mb-3">
          Na początek potrzebujemy kilku podstawowych informacji, żeby utworzyć Twoje konto.
          Później uzupełnisz szczegóły profilu, dodasz swoje ogłoszenia, stawki i harmonogram zajęć.
        </p>
        <p class="text-slate-400 !text-xs sm:text-sm max-w-2xl">
          Pierwszy krok zajmie Ci tylko kilka minut – resztę dopracujesz już wygodnie z poziomu panelu korepetytora.
        </p>
      </div>
    </section>

    <!-- FORMULARZ -->
    <section class="max-w-5xl mx-auto px-6 pb-20">
      <q-card flat bordered class="!bg-slate-800 border-slate-800 rounded-3xl shadow-xl">
        <q-form @submit.prevent="onSubmit" class="p-6 sm:p-8 space-y-6">
          <!-- Podstawowe dane -->
          <div>
            <p class="text-sm font-semibold mb-1 flex items-center gap-2">
              <q-icon name="person" color="sky-400" size="20px" />
              Dane podstawowe
            </p>
            <p class="text-xs text-slate-400 mb-4">
              Na tej podstawie utworzymy Twoje konto i pierwszy szkic profilu.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <q-input
                v-model="form.firstName"
                label="Imię *"
                dense
                filled
                class="!bg-slate-300"
              />
              <q-input
                v-model="form.lastName"
                label="Nazwisko *"
                dense
                filled
                class="!bg-slate-300"
              />
              <q-input
                v-model="form.email"
                label="E‑mail *"
                type="email"
                dense
                filled
                class="!bg-slate-300"
              />
              <q-input
                v-model="form.phone"
                label="Telefon (opcjonalnie)"
                type="tel"
                dense
                filled
                class="!bg-slate-300"
              />
            </div>
          </div>

          <q-separator color="slate-800" />

          <!-- Informacje o nauczaniu -->
          <div>
            <p class="text-sm font-semibold mb-1 flex items-center gap-2">
              <q-icon name="auto_stories" color="sky-400" size="20px" />
              W czym i jak chcesz uczyć
            </p>
            <p class="text-xs text-slate-400 mb-4">
              Te informacje pomogą nam wstępnie dobrać uczniów do Twojego profilu. Szczegóły doprecyzujesz później.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <q-select
                v-model="form.subjects"
                :options="subjectOptions"
                multiple
                use-chips
                dense
                filled
                class="!bg-slate-300"
                label="Przedmioty, których uczysz *"
              />
              <q-select
                v-model="form.levels"
                :options="levelOptions"
                multiple
                use-chips
                dense
                filled
                class="!bg-slate-300"
                label="Poziomy nauczania *"
              />

              <q-option-group
                v-model="form.modes"
                :options="modeOptions"
                type="checkbox"
                color="sky-400"
                class="sm:col-span-2"
              />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
              <q-input
                v-model.number="form.baseRate"
                label="Orientacyjna stawka za 60 min (zł) *"
                type="number"
                dense
                filled
                class="!bg-slate-300"
              />
              <q-select
                v-model="form.city"
                :options="cityOptions"
                dense
                filled
                class="!bg-slate-300"
                label="Miasto (dla zajęć stacjonarnych)"
                clearable
              />
            </div>
          </div>

          <q-separator color="slate-800" />

          <!-- Doświadczenie i krótki opis -->
          <div>
            <p class="text-sm font-semibold mb-1 flex items-center gap-2">
              <q-icon name="workspace_premium" color="sky-400" size="20px" />
              Doświadczenie i krótki opis
            </p>
            <p class="text-xs text-slate-400 mb-4">
              Ten opis zobaczą uczniowie w Twoim profilu. Później możesz dodać więcej szczegółów, linki i pliki.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <q-select
                v-model="form.experienceYears"
                :options="experienceOptions"
                dense
                filled
                class="!bg-slate-300"
                label="Doświadczenie w latach"
                emit-value
                map-options
              />
              <q-input
                v-model="form.title"
                label="Krótki nagłówek profilu (np. „Matematyka maturalna bez stresu”)"
                dense
                filled
                class="!bg-slate-300"
              />
            </div>

            <q-input
              v-model="form.bio"
              type="textarea"
              rows="4"
              dense
              filled
              class="!bg-slate-300 mt-4"
              label="Kilka zdań o tym, jak uczysz (opcjonalnie)"
            />
          </div>

          <q-separator color="slate-800" />

          <!-- Zgody / bezpieczeństwo -->
          <div>
            <p class="text-sm font-semibold mb-1 flex items-center gap-2">
              <q-icon name="verified_user" color="sky-400" size="20px" />
              Transparentność i bezpieczeństwo
            </p>
            <p class="text-xs text-slate-400 mb-3">
              Potwierdzamy dostarczone przez Ciebie dokumenty i jasno pokazujemy status weryfikacji na Twoim profilu.
              Uczniowie widzą, z kim mają do czynienia – Ty również widzisz historię współpracy z uczniami.
            </p>

            <q-option-group
              v-model="form.agreements"
              :options="agreementOptions"
              type="checkbox"
              color="sky-400"
              class="text-xs text-slate-200"
            />
          </div>

          <!-- Przyciski -->
          <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-2">
            <div class="text-xs text-slate-400 max-w-sm">
              Po wysłaniu formularza utworzymy Twoje konto. W kolejnym kroku uzupełnisz profil,
              dodasz swoje ogłoszenia i ustawisz harmonogram zajęć.
            </div>

            <div class="flex gap-3 justify-end">
              <q-btn
                flat
                color="white"
                label="Wyczyść"
                @click="onReset"
              />
              <q-btn
                color="primary"
                unelevated
                class="bg-sky-500 hover:bg-sky-400 px-6"
                label="Utwórz konto korepetytora"
                type="submit"
              />
            </div>
          </div>
        </q-form>
      </q-card>
    </section>
  </q-page>
</template>

<script setup lang="ts">
import { reactive } from 'vue'

type Mode = 'online' | 'stationary' | 'hybrid'

interface TutorSignupForm {
  firstName: string
  lastName: string
  email: string
  phone: string
  subjects: string[]
  levels: string[]
  modes: Mode[]
  baseRate: number | null
  city: string | null
  experienceYears: number | null
  title: string
  bio: string
  agreements: string[]
}

const form = reactive<TutorSignupForm>({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  subjects: [],
  levels: [],
  modes: ['online'],
  baseRate: null,
  city: null,
  experienceYears: null,
  title: '',
  bio: '',
  agreements: []
})

const subjectOptions = [
  'Matematyka',
  'Informatyka',
  'Fizyka',
  'Chemia',
  'Język angielski',
  'Język polski'
]

const levelOptions = [
  'Szkoła podstawowa',
  'Liceum / technikum',
  'Matura podstawowa',
  'Matura rozszerzona',
  'Studia I stopnia',
  'Studia II stopnia'
]

const modeOptions = [
  { label: 'Zajęcia online', value: 'online' },
  { label: 'Zajęcia stacjonarne', value: 'stationary' },
  { label: 'Tryb mieszany (online + stacjonarnie)', value: 'hybrid' }
]

const cityOptions = [
  'Warszawa',
  'Kraków',
  'Wrocław',
  'Poznań',
  'Gdańsk',
  'Rzeszów'
]

const experienceOptions = [
  { label: '0–1 rok', value: 1 },
  { label: '2–3 lata', value: 3 },
  { label: '4–6 lat', value: 6 },
  { label: '7–10 lat', value: 10 },
  { label: '11+ lat', value: 11 }
]

const agreementOptions = [
  {
    label: 'Potwierdzam, że podane dane są prawdziwe i zgodne z dokumentami, które dostarczę do weryfikacji.',
    value: 'truthfulData'
  },
  {
    label: 'Akceptuję zasady transparentności profilu – uczniowie widzą mój status weryfikacji i opinie po zajęciach.',
    value: 'transparency'
  },
  {
    label: 'Wyrażam zgodę na kontakt w sprawie dopasowywania uczniów do mojego profilu.',
    value: 'contact'
  }
]

function onSubmit () {
  // tutaj podłączasz API / router
  console.log('Tutor signup form:', { ...form })
}

function onReset () {
  form.firstName = ''
  form.lastName = ''
  form.email = ''
  form.phone = ''
  form.subjects = []
  form.levels = []
  form.modes = ['online']
  form.baseRate = null
  form.city = null
  form.experienceYears = null
  form.title = ''
  form.bio = ''
  form.agreements = []
}
</script>
