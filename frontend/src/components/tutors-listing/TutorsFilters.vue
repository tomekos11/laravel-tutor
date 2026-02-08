<template>
  <q-card
    flat
    bordered
    class="!bg-gray-600 border-slate-800 rounded-2xl shadow-md p-4 space-y-4"
  >
    <div class="text-subtitle1 text-weight-bold mb-1 flex items-center gap-2">
      <q-icon name="tune" color="sky-400" size="20px" />
      <span>Filtry korepetytorów</span>
    </div>
    <p class="text-xs text-slate-400">
      Zawęź listę korepetytorów po kategorii, cenie, typie zajęć i ocenach.
    </p>

    <!-- Kategoria -->
    <div>
      <div class="text-caption text-slate-300 mb-1">
        Kategoria
      </div>
      <q-select
        v-model="filters.category"
        :options="categoryOptions"
        label="Wybierz kategorię"
        dense
        clearable
        use-input
        input-debounce="0"
        @filter="onCategoryFilter"
        :option-label="opt => opt.label"
        :option-value="opt => opt.value"
        fill-input
        :hide-dropdown-icon="false"
        filled
        class="bg-slate-900 text-slate-50"
        popup-content-class="bg-slate-900 text-slate-50"
      />
    </div>

    <!-- Zakres cenowy -->
    <div>
      <div class="row items-center justify-between mb-1">
        <div class="text-caption text-slate-300">
          Zakres cenowy (zł / h)
        </div>
        <div class="text-caption text-slate-400">
          {{ filters.priceRange.min }} - {{ filters.priceRange.max }} zł
        </div>
      </div>
      <q-range
        v-model="filters.priceRange"
        :min="10"
        :max="300"
        :step="10"
        label-always
        color="sky-500"
        dense
      />
    </div>

    <!-- Typ zajęć (online/stacjonarnie) -->
    <div>
      <div class="text-caption text-slate-300 mb-1">
        Typ zajęć
      </div>
      <q-option-group
        v-model="filters.lessonType"
        :options="lessonTypeOptions"
        color="sky-400"
        type="checkbox"
        dense
        class="text-slate-100 text-xs"
      />
    </div>

    <!-- Zakres oceny -->
    <div>
      <div class="row items-center justify-between mb-1">
        <div class="text-caption text-slate-300">
          Minimalna ocena
        </div>
        <div class="text-caption text-slate-400">
          {{ filters.minRating.toFixed(1) }} +
        </div>
      </div>
      <q-slider
        v-model="filters.minRating"
        :min="0"
        :max="5"
        :step="0.5"
        label-always
        color="amber"
        markers
      />
    </div>

    <!-- Format zajęć -->
    <div>
      <div class="text-caption text-slate-300 mb-1">
        Format zajęć
      </div>
      <q-option-group
        v-model="filters.groupType"
        :options="groupTypeOptions"
        color="sky-400"
        type="radio"
        dense
        class="text-slate-100 text-xs"
      />
    </div>

    <!-- Dodatkowe filtry -->
    <q-separator spaced color="slate-800" />

    <div class="space-y-2 text-xs text-slate-200">
      <q-toggle
        v-model="filters.onlyWithAvatar"
        label="Tylko z avatarem"
        dense
        color="sky-400"
      />
      <q-toggle
        v-model="filters.onlyTopRated"
        label="Tylko najlepiej oceniani (4.5+)"
        dense
        color="sky-400"
      />
      <q-toggle
        v-model="filters.onlyAvailableEvenings"
        label="Dostępni w godzinach 18–22"
        dense
        color="sky-400"
      />
    </div>

    <div class="row q-mt-md q-gutter-sm">
      <q-btn
        color="primary"
        label="Zastosuj filtry"
        unelevated
        class="col bg-sky-500 hover:bg-sky-400 text-slate-900 font-semibold"
        @click="emitFilters"
      />
      <q-btn
        flat
        color="white"
        label="Wyczyść"
        class="col-4 border border-slate-700 hover:bg-slate-800 text-xs"
        @click="resetFilters"
      />
    </div>
  </q-card>
</template>

<script setup lang="ts">
import { reactive, ref, watch } from 'vue'

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

const emit = defineEmits<{
  (e: 'update:filters', value: Filters): void
}>()

const categoryOptionsSource = [
  { label: 'Matematyka', value: 'math' },
  { label: 'Informatyka', value: 'cs' },
  { label: 'Fizyka', value: 'physics' },
  { label: 'Chemia', value: 'chemistry' },
  { label: 'Język angielski', value: 'english' },
  { label: 'Język niemiecki', value: 'german' }
]

const categoryOptions = ref(categoryOptionsSource)

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

const lessonTypeOptions = [
  { label: 'Online', value: 'online' },
  { label: 'Stacjonarnie', value: 'stationary' }
]

const groupTypeOptions = [
  { label: 'Dowolny', value: 'any' },
  { label: '1 na 1', value: '1v1' },
  { label: 'Mała grupa (1–5)', value: 'small' },
  { label: 'Duża grupa (5+)', value: 'large' }
]

function onCategoryFilter (val: string, update: (cb: () => void) => void) {
  update(() => {
    if (!val) {
      categoryOptions.value = categoryOptionsSource
      return
    }

    const needle = val.toLowerCase()
    categoryOptions.value = categoryOptionsSource.filter(opt =>
      opt.label.toLowerCase().includes(needle)
    )
  })
}

function emitFilters () {
  emit('update:filters', { ...filters })
}

function resetFilters () {
  filters.category = null
  filters.priceRange.min = 10
  filters.priceRange.max = 300
  filters.lessonType = ['online', 'stationary']
  filters.minRating = 0
  filters.groupType = 'any'
  filters.onlyWithAvatar = false
  filters.onlyTopRated = false
  filters.onlyAvailableEvenings = false
  emitFilters()
}

watch(
  () => ({ ...filters }),
  () => {
    emitFilters()
  },
  { deep: true }
)
</script>
