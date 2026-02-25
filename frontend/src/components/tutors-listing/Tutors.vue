<template>
  <q-page class="bg-slate-900 text-slate-50">
    <section class="max-w-8xl mx-auto px-6 py-10">
      <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-extrabold mb-2">
          Znajdź idealnego korepetytora
        </h1>
        <p class="text-slate-300 text-sm max-w-2xl">
          Skorzystaj z filtrów po lewej stronie i kliknij „Zastosuj filtry”, aby pobrać listę korepetytorów.
        </p>
      </div>

      <div class="flex flex-col lg:flex-row gap-6">
        <!-- Lewa kolumna: filtry -->
        <div class="w-full lg:w-100 shrink-0">
          <TutorsFilters
            :filters="filters"
            @update:filters="(v) => Object.assign(filters, v)"
            @apply="fetchTutors(1)"
          />
        </div>

        <!-- Prawa kolumna: listing -->
        <div class="flex-1">
          <q-inner-loading :showing="loading" label="Ładowanie..." label-class="text-slate-400" />
          <div v-if="error" class="text-negative q-mb-md">
            {{ error }}
          </div>
          <TutorsListing
            v-else
            :tutors="normalizedTutors"
            :pagination-meta="paginationMeta"
            @page="fetchTutors"
          />
        </div>
      </div>
    </section>
  </q-page>
</template>

<script setup lang="ts">
import { reactive, ref, computed, onMounted } from 'vue'
import { api } from 'src/boot/axios'
import TutorsFilters from './TutorsFilters.vue'
import TutorsListing from './TutorsListing.vue'
import type { NormalizedTutor } from './TutorsListing.vue'

defineOptions({ name: 'TutorsPage' })

type LessonType = 'online' | 'stationary'
type GroupType = 'any' | '1v1' | 'small' | 'large'

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

export interface TutorListingMeta {
  current_page: number
  last_page: number
  per_page: number
  total: number
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

const loading = ref(false)
const error = ref<string | null>(null)
const offersFromApi = ref<AdvertisementFromApi[]>([])
const paginationMeta = ref<TutorListingMeta | null>(null)
const PER_PAGE = 12

function buildQueryParams (page: number): Record<string, string | number> {
  const params: Record<string, string | number> = {
    page,
    per_page: PER_PAGE
  }
  if (filters.category?.label) {
    params.category = filters.category.label
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

onMounted(async () => {
  await fetchTutors(1)
})
</script>
