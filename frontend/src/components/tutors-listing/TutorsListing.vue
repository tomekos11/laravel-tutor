<template>
  <div>
    <p v-if="tutors.length === 0" class="text-slate-400 text-body2">
      {{ emptyMessage }}
    </p>
    <template v-else>
      <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        <q-card
          v-for="tutor in tutors"
          :key="tutor.id"
          flat
          bordered
          class="bg-gray-800! border-slate-800 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-200"
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
                  :model-value="tutor.rating"
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

      <div
        v-if="paginationMeta && paginationMeta.last_page > 1"
        class="row justify-center q-mt-lg"
      >
        <q-pagination
          v-model="currentPage"
          :max="paginationMeta.last_page"
          :max-pages="7"
          direction-links
          boundary-links
          color="sky-500"
          active-color="sky-400"
          @update:model-value="onPageChange"
        />
      </div>
      <p v-if="paginationMeta" class="text-caption text-slate-500 text-center q-mt-md">
        Strona {{ paginationMeta.current_page }} z {{ paginationMeta.last_page }}
        (łącznie {{ paginationMeta.total }} korepetytorów)
      </p>
    </template>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import type { TutorListingMeta } from './Tutors.vue'

export interface NormalizedTutor {
  id: number
  name: string
  specialization: string
  rating: number
  pricePerHour: number
  avatar: string
  mode: string
  format: 'online' | 'offline' | 'hybrid'
  description: string
  categories: string[]
  hasAvatar: boolean
}

const props = defineProps<{
  tutors: NormalizedTutor[]
  paginationMeta: TutorListingMeta | null
}>()

const emit = defineEmits<{
  (e: 'page', page: number): void
}>()

const currentPage = ref(1)

watch(
  () => props.paginationMeta?.current_page,
  (page) => {
    if (page != null) currentPage.value = page
  },
  { immediate: true }
)

function onPageChange (page: number) {
  emit('page', page)
}

const emptyMessage = computed(() => {
  return 'Ustaw filtry i kliknij „Zastosuj filtry”, aby zobaczyć listę korepetytorów. Jeśli nie ma wyników, zmień kryteria.'
})
</script>
