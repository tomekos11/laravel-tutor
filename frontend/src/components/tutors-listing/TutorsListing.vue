<template>
  <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
    <q-card
      v-for="tutor in tutors"
      :key="tutor.id"
      flat
      bordered
      class="!bg-gray-800 border-slate-800 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-200"
    >
      <q-card-section class="row items-center q-gutter-md min-w-100">
        <q-avatar size="72px">
          <img :src="tutor.avatar" :alt="tutor.name" />
        </q-avatar>

        <div class="col">
          <div class="text-h6 text-weight-bold text-slate-50">
            {{ tutor.name }}
          </div>
          <div class="text-caption text-slate-400">
            {{ tutor.specialization }}
          </div>

          <div class="row items-center q-mt-xs">
            <q-rating
              v-model="tutor.rating"
              max="5"
              size="20px"
              color="amber"
              icon="star_border"
              icon-selected="star"
              icon-half="star_half"
              :fractions="2"
              readonly
            />
            <div class="text-caption text-slate-400 q-ml-sm">
              {{ tutor.rating.toFixed(1) }} / 5
            </div>
          </div>
        </div>
      </q-card-section>

      <!-- Opis korepetytora -->
      <q-card-section>
        <p class="text-body2 text-slate-200 text-justify leading-relaxed">
          {{ tutor.description }}
        </p>
      </q-card-section>

      <q-separator color="slate-800" />

      <q-card-section class="row items-center justify-between">
        <div class="column">
          <div class="text-caption text-slate-400">
            Cena za godzinę
          </div>
          <div class="text-subtitle1 text-sky-400 text-weight-bold">
            {{ tutor.pricePerHour }} zł
          </div>
        </div>

        <q-chip
          color="sky-500"
          text-color="black"
          outline
          class="font-semibold"
        >
          {{ tutor.mode }}
        </q-chip>
      </q-card-section>
    </q-card>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

type Tutor = {
  id: number
  name: string
  specialization: string
  rating: number
  pricePerHour: number
  avatar: string
  mode: string
  description: string
}

const tutors = ref<Tutor[]>([
  {
    id: 1,
    name: 'Anna Kowalska',
    specialization: 'Matematyka / Analiza',
    rating: 4.5,
    pricePerHour: 120,
    avatar: 'https://i.pravatar.cc/150?img=47',
    mode: 'Online',
    description:
      'Pomagam przygotować się do matury rozszerzonej, kolosów z analizy oraz egzaminów poprawkowych. Stawiam na intuicję, obrazki i dużo zadań z prawdziwych arkuszy.'
  },
  {
    id: 2,
    name: 'Jan Nowak',
    specialization: 'Informatyka / Algorytmy',
    rating: 4.8,
    pricePerHour: 150,
    avatar: 'https://i.pravatar.cc/150?img=12',
    mode: 'Online / Stacjonarnie',
    description:
      'Tłumaczę algorytmy bez „magii” – krok po kroku, z przykładami na tablicy i w kodzie. Przygotowuję do rekrutacji technicznych, olimpiad oraz kolosów na studiach.'
  },
  {
    id: 3,
    name: 'Katarzyna Wiśniewska',
    specialization: 'Język angielski (C1)',
    rating: 4.2,
    pricePerHour: 100,
    avatar: 'https://i.pravatar.cc/150?img=32',
    mode: 'Stacjonarnie',
    description:
      'Konwersacje nastawione na naturalną komunikację, przełamywanie bariery językowej i poprawną wymowę. Materiały dobieram pod Twoje cele – od matury po rozmowy o pracę.'
  }
])
</script>
