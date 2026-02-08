<template>
  <q-page class="bg-slate-900 text-slate-50">
    <section class="max-w-8xl mx-auto px-6 py-10">
      <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-extrabold mb-2">
          Znajdź idealnego korepetytora
        </h1>
        <p class="text-slate-300 text-sm max-w-2xl">
          Skorzystaj z filtrów po lewej stronie, aby zawęzić wyniki do korepetytorów,
          którzy najlepiej pasują do Twoich potrzeb, stylu nauki i budżetu.
        </p>
      </div>

      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Lewa kolumna: filtry -->
        <div class="w-full lg:w-100 shrink-0">
          <TutorsFilters v-model:filters="filters" />
        </div>

        <!-- Prawa kolumna: listing -->
        <div class="flex-1">
          <TutorsListing :filters="filters" />
        </div>
      </div>
    </section>
  </q-page>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import TutorsFilters from './TutorsFilters.vue'
import TutorsListing from './TutorsListing.vue'

type LessonType = 'online' | 'stationary'
type GroupType = 'any' | '1v1' | 'small' | 'large'

interface Filters {
  category: { label: string; value: string } | null
  priceRange: {
    min: number
    max: number
  }
  lessonType: LessonType[]
  minRating: number
  groupType: GroupType
  onlyWithAvatar: boolean
  onlyTopRated: boolean
  onlyAvailableEvenings: boolean
}

const filters = reactive<Filters>({
  category: null,
  priceRange: {
    min: 10,
    max: 300
  },
  lessonType: ['online', 'stationary'],
  minRating: 0,
  groupType: 'any',
  onlyWithAvatar: false,
  onlyTopRated: false,
  onlyAvailableEvenings: false
})
</script>
