<template>
  <q-card
    flat
    class="bg-transparent! rounded-2xl shadow-md p-3 space-y-2 overflow-hidden max-w-full min-w-0"
  >
    <div class="text-subtitle1 text-weight-bold mb-0 flex items-center gap-2">
      <q-icon name="tune" color="sky-400" size="20px" />
      <span>Filtry korepetytorów</span>
    </div>
    <p class="text-xs text-slate-400">
      Zawęź listę korepetytorów po kategorii, formacie zajęć, miejscowości, cenie i ocenach.
    </p>

    <!-- Kategoria -->
    <div class="min-w-0">
      <div class="text-caption text-slate-300 mb-0">
        Kategoria
      </div>
      <q-select
        v-model="filters.category"
        :options="filteredCategoryOptions"
        label="Wybierz kategorię"
        dense
        clearable
        use-input
        input-debounce="0"
        @filter="onCategoryFilter"
        :option-label="opt => opt.label"
        :option-value="opt => opt.value"
        fill-input
        hide-selected
        :hide-dropdown-icon="false"
        filled
        class="bg-gray-400 max-w-full"
        popup-content-class="!bg-slate-900 !text-slate-50"
      />
    </div>

    <!-- Format zajęć -->
    <div class="min-w-0">
      <div class="text-caption text-slate-300 mb-0">
        Format zajęć
      </div>
      <q-select
        v-model="filters.format"
        :options="filteredFormatOptions"
        label="Wybierz format"
        dense
        clearable
        use-input
        input-debounce="0"
        @filter="onFormatFilter"
        :option-label="opt => opt.label"
        :option-value="opt => opt.value"
        fill-input
        :hide-dropdown-icon="false"
        filled
        hide-selected
        class="bg-gray-400 max-w-full"
        popup-content-class="!bg-slate-900 !text-slate-50"
      />
    </div>

    <!-- Miejscowość -->
    <div class="min-w-0">
      <div class="text-caption text-slate-300 mb-0">
        Miejscowość
      </div>
      <q-select
        v-model="filters.city"
        :options="filteredCityOptions"
        label="Wybierz miejscowość"
        dense
        clearable
        use-input
        input-debounce="0"
        @filter="onCityFilter"
        :option-label="opt => opt.label"
        :option-value="opt => opt.value"
        fill-input
        :hide-dropdown-icon="false"
        filled
        hide-selected
        class="bg-gray-400 max-w-full"
        popup-content-class="!bg-slate-900 !text-slate-50"
      />
    </div>

    <!-- Zakres cenowy -->
    <div>
      <div class="row items-center justify-between mb-0">
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

    <!-- Zakres oceny -->
    <div>
      <div class="row items-center justify-between mb-0">
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

    <!-- Dodatkowe filtry -->
    <q-separator color="slate-800" />

    <div class="space-y-1 text-xs text-slate-200">
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
    </div>

    <div class="row q-mt-sm q-gutter-sm">
      <q-btn
        color="primary"
        label="Odśwież"
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

interface FilterOption {
  label: string
  value: string
}

interface Filters {
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

const props = withDefaults(defineProps<{
  categoryOptions: FilterOption[]
  formatOptions: FilterOption[]
  cityOptions: FilterOption[]
}>(), {
  categoryOptions: () => [],
  formatOptions: () => [],
  cityOptions: () => []
})

const emit = defineEmits<{
  (e: 'update:filters', value: Filters): void
  (e: 'apply'): void
}>()

const filteredCategoryOptions = ref<FilterOption[]>([])
const filteredFormatOptions = ref<FilterOption[]>([])
const filteredCityOptions = ref<FilterOption[]>([])

watch(
  () => props.categoryOptions,
  (options) => {
    filteredCategoryOptions.value = [...options]
  },
  { immediate: true }
)

watch(
  () => props.formatOptions,
  (options) => {
    filteredFormatOptions.value = [...options]
  },
  { immediate: true }
)

watch(
  () => props.cityOptions,
  (options) => {
    filteredCityOptions.value = [...options]
  },
  { immediate: true }
)

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

watch(
  filters,
  () => {
    emit('update:filters', {
      category: filters.category,
      format: filters.format,
      city: filters.city,
      priceRange: { ...filters.priceRange },
      minRating: filters.minRating,
      onlyWithAvatar: filters.onlyWithAvatar,
      onlyTopRated: filters.onlyTopRated
    })
  },
  { deep: true }
)

type SelectUpdateFn = (cb: () => void) => void

function filterOptions (options: FilterOption[], value: string): FilterOption[] {
  if (!value) return [...options]

  const needle = value.toLowerCase()
  return options.filter(opt => opt.label.toLowerCase().includes(needle))
}

function onCategoryFilter (val: string, update: SelectUpdateFn) {
  update(() => {
    filteredCategoryOptions.value = filterOptions(props.categoryOptions, val)
  })
}

function onFormatFilter (val: string, update: SelectUpdateFn) {
  update(() => {
    filteredFormatOptions.value = filterOptions(props.formatOptions, val)
  })
}

function onCityFilter (val: string, update: SelectUpdateFn) {
  update(() => {
    filteredCityOptions.value = filterOptions(props.cityOptions, val)
  })
}

function emitFilters () {
  emit('update:filters', {
    category: filters.category,
    format: filters.format,
    city: filters.city,
    priceRange: { ...filters.priceRange },
    minRating: filters.minRating,
    onlyWithAvatar: filters.onlyWithAvatar,
    onlyTopRated: filters.onlyTopRated
  })
  emit('apply')
}

function resetFilters () {
  filters.category = null
  filters.format = null
  filters.city = null
  filters.priceRange.min = 10
  filters.priceRange.max = 300
  filters.minRating = 0
  filters.onlyWithAvatar = false
  filters.onlyTopRated = false
  emitFilters()
}
</script>
