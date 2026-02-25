<template>
  <q-layout view="lHh lpR lFf" class="bg-slate-900 text-slate-50">
    <q-drawer
      v-model="drawerOpen"
      show-if-above
      bordered
      :width="350"
      :breakpoint="1024"
      class="bg-slate-800"
      content-class="overflow-x-hidden"
    >
      <div class="relative p-2">
        <q-btn
          flat
          round
          dense
          icon="close"
          color="grey-5"
          class="absolute top-2 right-2 z-10"
          @click="drawerOpen = false"
          aria-label="Zamknij filtry"
        />
        <TutorsFilters
          :category-options="availableFilters.categories"
          :format-options="availableFilters.formats"
          :city-options="availableFilters.cities"
          @update:filters="onFiltersUpdate"
          @apply="refreshTutors"
        />
      </div>
    </q-drawer>

    <q-page-container>
      <q-page class="px-6 py-10">
        <!-- Przycisk otwarcia filtrów – widoczny gdy drawer zamknięty -->
        <q-btn
          v-show="!drawerOpen"
          fab
          icon="tune"
          color="sky-500"
          class="fixed left-4 bottom-8 z-50 shadow-lg"
          @click="drawerOpen = true"
          aria-label="Pokaż filtry"
        />

        <div class="mb-6">
          <h1 class="text-2xl md:text-3xl font-extrabold mb-2">
            Znajdź idealnego korepetytora
          </h1>
          <p class="text-slate-300 text-sm max-w-2xl">
            Zmień filtry w panelu po lewej – lista odświeży się automatycznie.
          </p>
        </div>

        <div>
          <div v-if="error" class="text-negative q-mb-md">
            {{ error }}
          </div>
          <template v-else-if="loading">
            <div
              class="grid gap-6"
              :class="drawerOpen ? 'grid-cols-1 md:grid-cols-2 xl:grid-cols-3' : 'grid-cols-1 md:grid-cols-3 xl:grid-cols-4'"
            >
              <TutorCardSkeleton v-for="n in 6" :key="n" />
            </div>
          </template>
          <TutorsListing
            v-else
            :tutors="normalizedTutors"
            :pagination-meta="paginationMeta"
            :drawer-open="drawerOpen"
            @page="fetchTutors"
          />
        </div>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup lang="ts">
import { reactive, ref, computed, onMounted, watch } from 'vue'
import { api } from 'src/boot/axios'
import TutorsFilters from './TutorsFilters.vue'
import TutorsListing from './TutorsListing.vue'
import TutorCardSkeleton from './TutorCardSkeleton.vue'
import type { NormalizedTutor } from './TutorsListing.vue'

defineOptions({ name: 'TutorsPage' })

interface FilterOption {
  label: string
  value: string
}

interface AdvertisementFiltersFromApi {
  categories: FilterOption[]
  formats: FilterOption[]
  cities: FilterOption[]
}

/** Jedna oferta z API GET /api/advertisements */
interface AdvertisementFromApi {
  id: number
  category: string | null
  tutor_name: string
  price: number
  description: string
  address?: string
  rating: number | null
  rating_count?: number
  levels?: string[]
  formats?: string[]
  tutor?: {
    id: number
    name: string | null
    surname: string | null
    email: string | null
    phone: string | null
    image: string | null
  }
}

export interface Filters {
  category: FilterOption | null
  format: FilterOption | null
  city: FilterOption | null
  priceRange: {
    min: number
    max: number
  }
  minRating: number
  onlyWithAvatar: boolean
  onlyTopRated: boolean
}

export interface TutorListingMeta {
  current_page: number
  last_page: number
  per_page: number
  total: number
}

const filters = reactive<Filters>({
  category: null,
  format: null,
  city: null,
  priceRange: {
    min: 10,
    max: 300
  },
  minRating: 0,
  onlyWithAvatar: false,
  onlyTopRated: false
})

const loading = ref(false)
const error = ref<string | null>(null)
const offersFromApi = ref<AdvertisementFromApi[]>([])
const paginationMeta = ref<TutorListingMeta | null>(null)
const availableFilters = ref<AdvertisementFiltersFromApi>({
  categories: [],
  formats: [],
  cities: []
})
const PER_PAGE = 12
const drawerOpen = ref(true)

let debounceTimer: ReturnType<typeof setTimeout> | null = null
function onFiltersUpdate (v: Filters) {
  Object.assign(filters, v)
}

function buildQueryParams (page: number): Record<string, string | number> {
  const params: Record<string, string | number> = {
    page,
    per_page: PER_PAGE
  }
  if (filters.category?.value) {
    params.category = filters.category.value
  }
  if (filters.format?.value) {
    params.format = filters.format.value
  }
  if (filters.city?.value) {
    params.city = filters.city.value
  }
  if (filters.priceRange.min > 10 || filters.priceRange.max < 300) {
    params.price_min = filters.priceRange.min
    params.price_max = filters.priceRange.max
  }
  if (filters.minRating > 0) params.min_rating = filters.minRating
  if (filters.onlyWithAvatar) params.only_with_avatar = '1'
  if (filters.onlyTopRated) params.only_top_rated = '1'
  return params
}

async function fetchAvailableFilters () {
  try {
    const { data } = await api.get<{ data: AdvertisementFiltersFromApi }>('/api/advertisements/filters')
    const payload = data?.data
    availableFilters.value = {
      categories: Array.isArray(payload?.categories) ? payload.categories : [],
      formats: Array.isArray(payload?.formats) ? payload.formats : [],
      cities: Array.isArray(payload?.cities) ? payload.cities : []
    }
  } catch {
    availableFilters.value = {
      categories: [],
      formats: [],
      cities: []
    }
  }
}

function mapOfferToTutor (ad: AdvertisementFromApi): NormalizedTutor {
  const tutor = ad.tutor
  const avatar = tutor?.image ?? null
  const mode = ad.formats?.length ? ad.formats.join(' / ') : 'Online'
  return {
    id: ad.id,
    name: ad.tutor_name || 'Korepetytor',
    specialization: ad.category || 'Korepetycje',
    rating: ad.rating ?? 0,
    pricePerHour: ad.price,
    avatar: avatar ?? `https://i.pravatar.cc/150?u=${ad.id}`,
    mode,
    format: 'online',
    description: ad.description || 'Skontaktuj się, aby poznać szczegóły oferty.',
    categories: ad.category ? [ad.category] : [],
    hasAvatar: !!avatar
  }
}

async function fetchTutors (page: number) {
  loading.value = true
  error.value = null
  try {
    const params = buildQueryParams(page)
    const { data } = await api.get<{ data: AdvertisementFromApi[]; meta: TutorListingMeta }>('/api/advertisements', { params })
    const list = data?.data ?? []
    const meta = data?.meta ?? null
    offersFromApi.value = Array.isArray(list) ? list : []
    paginationMeta.value = meta
  } catch (e: unknown) {
    const msg = e && typeof e === 'object' && 'message' in e ? String((e as Error).message) : 'Nie udało się załadować listy ofert.'
    error.value = msg
    offersFromApi.value = []
    paginationMeta.value = null
  } finally {
    loading.value = false
  }
}

const normalizedTutors = computed<NormalizedTutor[]>(() => {
  return offersFromApi.value.map(mapOfferToTutor)
})

watch(
  filters,
  () => {
    if (debounceTimer) clearTimeout(debounceTimer)
    debounceTimer = setTimeout(() => {
      void fetchTutors(1)
      debounceTimer = null
    }, 400)
  },
  { deep: true }
)

function refreshTutors () {
  if (debounceTimer) {
    clearTimeout(debounceTimer)
    debounceTimer = null
  }
  void fetchTutors(1)
}

onMounted(async () => {
  await fetchAvailableFilters()
  await fetchTutors(1)
})
</script>
