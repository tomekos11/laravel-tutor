<?php

namespace Modules\Advertisements\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Advertisements\Models\Advertisement;
use Modules\Advertisements\Models\Location;
use Modules\Advertisements\Http\Requests\StoreAdvertisementRequest;
use Modules\Advertisements\Http\Requests\UpdateAdvertisementRequest;
use Modules\Courses\Models\Field;
use Modules\Courses\Models\Level;
use Modules\Users\Models\Role;
use Modules\Users\Models\UserRole;

class AdvertisementsController extends Controller
{
    /**
     * Słowniki (id + nazwa) potrzebne do formularza tworzenia/edycji ogłoszenia.
     */
    public function formOptions(): JsonResponse
    {
        return response()->json([
            'data' => [
                'fields'    => Field::orderBy('name')->get(['id', 'name']),
                'levels'    => Level::orderBy('name')->get(['id', 'name']),
                'locations' => Location::orderBy('name')->get(['id', 'name']),
            ],
        ]);
    }

    /**
     * Ogłoszenia zalogowanego użytkownika (do panelu zarządzania własnymi ofertami).
     */
    public function mine(Request $request): JsonResponse
    {
        $ads = Advertisement::with(['field', 'user.receivedRatings', 'locations', 'levels'])
            ->withCount('groups')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $ads->map(fn ($ad) => $this->formatAdvertisement($ad))->all(),
        ]);
    }

    public function filters(): JsonResponse
    {
        $categoryNames = Advertisement::query()
            ->join('course__fields as fields', 'advertisement__advertisements.field_id', '=', 'fields.id')
            ->whereNotNull('fields.name')
            ->select('fields.name')
            ->distinct()
            ->orderBy('fields.name')
            ->pluck('fields.name')
            ->values();

        $locationNames = Advertisement::query()
            ->join('advertisement__advertisement_locations as ad_locations', 'advertisement__advertisements.id', '=', 'ad_locations.advertisement_id')
            ->join('advertisement__locations as locations', 'ad_locations.location_id', '=', 'locations.id')
            ->whereNotNull('locations.name')
            ->select('locations.name')
            ->distinct()
            ->orderBy('locations.name')
            ->pluck('locations.name')
            ->values();

        $cities = Advertisement::query()
            ->whereNotNull('address')
            ->pluck('address')
            ->map(function ($address) {
                return $this->extractCityFromAddress(is_string($address) ? $address : null);
            })
            ->filter()
            ->unique(fn ($city) => Str::lower((string) $city))
            ->sort()
            ->values();

        $formats = $locationNames
            ->filter(function ($locationName) use ($cities) {
                return ! $cities->contains(function ($city) use ($locationName) {
                    return Str::lower((string) $city) === Str::lower((string) $locationName);
                });
            })
            ->values();

        return response()->json([
            'data' => [
                'categories' => $this->toOptions($categoryNames),
                'formats'    => $this->toOptions($formats),
                'cities'     => $this->toOptions($cities),
            ],
        ]);
    }

    /**
     * Display a listing of the resource with pagination and filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Advertisement::with([
            'field',
            'user.receivedRatings',
            'locations',
            'levels',
        ]);

        // Filtrowanie po poziomie
        if ($request->has('level_id')) {
            $query->whereHas('levels', function ($q) use ($request) {
                $q->where('course__levels.id', $request->level_id);
            });
        }

        // Filtrowanie po lokalizacji
        if ($request->has('location_id')) {
            $query->whereHas('locations', function ($q) use ($request) {
                $q->where('advertisement__locations.id', $request->location_id);
            });
        }

        // Filtrowanie po formacie zajęć (po nazwach lokalizacji)
        if ($request->filled('format')) {
            $formatFilter = $request->input('format');
            $formats = is_array($formatFilter) ? $formatFilter : explode(',', (string) $formatFilter);
            $formats = collect($formats)
                ->map(static fn ($format) => trim((string) $format))
                ->filter()
                ->values();

            if ($formats->isNotEmpty()) {
                $query->whereHas('locations', function ($q) use ($formats) {
                    $q->whereIn('advertisement__locations.name', $formats->all());
                });
            }
        }

        // Filtrowanie po miejscowości (prefiks adresu "Miasto, ...")
        if ($request->filled('city')) {
            $city = trim((string) $request->get('city'));
            if ($city !== '') {
                $query->where('address', 'like', $city . '%');
            }
        }

        // Filtrowanie po kategorii (field) – po id lub po nazwie
        if ($request->has('field_id')) {
            $query->where('field_id', $request->field_id);
        }
        if ($request->filled('category')) {
            $category = $request->get('category');
            $query->whereHas('field', function ($q) use ($category) {
                $q->where('name', 'like', '%' . $category . '%');
            });
        }

        // Filtrowanie po cenie (min)
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        // Filtrowanie po cenie (max)
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Filtrowanie po użytkowniku
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Minimalna średnia ocena korepetytora
        $minRating = $request->filled('min_rating') ? (float) $request->min_rating : 0;
        if ($request->boolean('only_top_rated')) {
            $minRating = max($minRating, 4.5);
        }
        if ($minRating > 0) {
            $tutorIds = \Modules\Users\Models\User::query()
                ->whereHas('receivedRatings')
                ->withAvg('receivedRatings', 'rating')
                ->having('received_ratings_avg_rating', '>=', $minRating)
                ->pluck('id');
            $query->whereIn('user_id', $tutorIds);
        }

        // Tylko ogłoszenia korepetytorów z avatarem
        if ($request->boolean('only_with_avatar')) {
            $query->whereHas('user', function ($q) {
                $q->whereNotNull('image')->where('image', '!=', '');
            });
        }

        // Sortowanie
        $allowedSortBy = ['created_at', 'price', 'id'];
        $sortBy = $request->get('sort_by', 'created_at');
        if (!in_array($sortBy, $allowedSortBy, true)) {
            $sortBy = 'created_at';
        }

        $sortOrder = strtolower((string) $request->get('sort_order', 'desc'));
        $sortOrder = in_array($sortOrder, ['asc', 'desc'], true) ? $sortOrder : 'desc';

        $query->orderBy($sortBy, $sortOrder);

        // Paginacja
        $perPage = (int) $request->get('per_page', 15);
        $perPage = max(1, min($perPage, 100));
        $ads = $query->paginate($perPage);

        /** @var array<int, mixed> $items */
        $items = $ads->items();
        $data = collect($items)->map(function ($ad) {
            return $this->formatAdvertisement($ad);
        })->all();

        return response()->json([
            'data'          => $data,
            'meta'          => [
                'current_page' => $ads->currentPage(),
                'last_page'    => $ads->lastPage(),
                'per_page'     => $ads->perPage(),
                'total'        => $ads->total(),
            ],
            'current_page'  => $ads->currentPage(),
            'last_page'     => $ads->lastPage(),
            'per_page'      => $ads->perPage(),
            'total'         => $ads->total(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdvertisementRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $ad = Advertisement::create([
            'user_id' => $request->user()->id,
            'price' => $validated['price'],
            'description' => $validated['description'],
            'field_id' => $validated['field_id'],
            'address' => $validated['address'],
        ]);

        // Synchronizacja poziomów
        if (array_key_exists('level_ids', $validated)) {
            $ad->levels()->sync($validated['level_ids'] ?? []);
        }

        // Synchronizacja lokalizacji
        if (array_key_exists('location_ids', $validated)) {
            $ad->locations()->sync($validated['location_ids'] ?? []);
        }

        $ad->load(['field', 'user.receivedRatings', 'locations', 'levels']);
        $ad->loadCount('groups');

        // Kto wystawia ogłoszenie, jest traktowany jako korepetytor - nadaj rolę "tutor" jeśli jej jeszcze nie ma.
        $tutorRole = Role::where('name', 'tutor')->first();
        if ($tutorRole) {
            UserRole::firstOrCreate(['user_id' => $request->user()->id, 'role_id' => $tutorRole->id]);
        }

        return response()->json([
            'message' => 'Ogłoszenie zostało utworzone pomyślnie.',
            'data' => $this->formatAdvertisement($ad),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $ad = Advertisement::with([
            'field',
            'user.receivedRatings',
            'locations',
            'levels',
        ])->findOrFail($id);

        return response()->json([
            'data' => $this->formatAdvertisement($ad),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdvertisementRequest $request, int $id): JsonResponse
    {
        $ad = Advertisement::findOrFail($id);

        if ($ad->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Brak uprawnień do edycji tego ogłoszenia.'], 403);
        }

        $validated = $request->validated();

        $ad->update(array_filter([
            'price' => $validated['price'] ?? null,
            'description' => $validated['description'] ?? null,
            'field_id' => $validated['field_id'] ?? null,
            'address' => $validated['address'] ?? null,
        ], static fn ($value) => $value !== null));

        // Synchronizacja poziomów
        if (array_key_exists('level_ids', $validated)) {
            $ad->levels()->sync($validated['level_ids'] ?? []);
        }

        // Synchronizacja lokalizacji
        if (array_key_exists('location_ids', $validated)) {
            $ad->locations()->sync($validated['location_ids'] ?? []);
        }

        $ad->load(['field', 'user.receivedRatings', 'locations', 'levels']);

        return response()->json([
            'message' => 'Ogłoszenie zostało zaktualizowane pomyślnie.',
            'data' => $this->formatAdvertisement($ad),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $ad = Advertisement::findOrFail($id);

        if ($ad->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Brak uprawnień do usunięcia tego ogłoszenia.'], 403);
        }

        $ad->delete();

        return response()->json([
            'message' => 'Ogłoszenie zostało usunięte pomyślnie.',
        ]);
    }

    /**
     * Format advertisement data for API response.
     */
    private function toOptions(Collection $values): array
    {
        return $values
            ->map(static function ($value) {
                $normalized = trim((string) $value);

                return [
                    'label' => $normalized,
                    'value' => $normalized,
                ];
            })
            ->filter(static fn (array $option) => $option['value'] !== '')
            ->values()
            ->all();
    }

    private function extractCityFromAddress(?string $address): ?string
    {
        if ($address === null) {
            return null;
        }

        $city = trim((string) Str::of($address)->before(','));

        return $city !== '' ? $city : null;
    }

    private function formatAdvertisement(Advertisement $ad): array
    {
        $user = $ad->user;

        $ratingAvg   = $user?->receivedRatings->avg('rating');
        $ratingCount = $user?->receivedRatings->count() ?? 0;

        return [
            'id'           => $ad->id,
            'category'     => $ad->field?->name,
            'field_id'     => $ad->field_id,
            'tutor_name'   => trim(($user?->name ?? '') . ' ' . ($user?->surname ?? '')),
            'price'        => $ad->price,
            'description'  => $ad->description,
            'address'      => $ad->address,

            'rating'       => $ratingAvg !== null ? round($ratingAvg, 2) : null,
            'rating_count' => $ratingCount,

            'levels'       => $ad->levels->pluck('name')->all(),
            'level_ids'    => $ad->levels->pluck('id')->all(),
            'formats'      => $ad->locations->pluck('name')->all(),
            'location_ids' => $ad->locations->pluck('id')->all(),

            'groups_count' => $ad->groups_count ?? $ad->groups()->count(),

            'tutor'        => [
                'id'       => $user?->id,
                'name'     => $user?->name,
                'surname'  => $user?->surname,
                'email'    => $user?->email,
                'phone'    => $user?->phone,
                'image'    => $user?->image,
            ],

            'created_at'   => $ad->created_at,
            'updated_at'   => $ad->updated_at,
        ];
    }
}
