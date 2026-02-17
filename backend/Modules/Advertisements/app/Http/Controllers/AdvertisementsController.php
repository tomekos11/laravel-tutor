<?php

namespace Modules\Advertisements\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Modules\Advertisements\Models\Advertisement;
use Modules\Advertisements\Http\Requests\StoreAdvertisementRequest;
use Modules\Advertisements\Http\Requests\UpdateAdvertisementRequest;

class AdvertisementsController extends Controller
{
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

        // Filtrowanie po kategorii (field)
        if ($request->has('field_id')) {
            $query->where('field_id', $request->field_id);
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
            'data' => $data,
            'current_page' => $ads->currentPage(),
            'last_page' => $ads->lastPage(),
            'per_page' => $ads->perPage(),
            'total' => $ads->total(),
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
    private function formatAdvertisement(Advertisement $ad): array
    {
        $user = $ad->user;

        $ratingAvg   = $user?->receivedRatings->avg('rating');
        $ratingCount = $user?->receivedRatings->count() ?? 0;

        return [
            'id'           => $ad->id,
            'category'     => $ad->field?->name,
            'tutor_name'   => trim(($user?->name ?? '') . ' ' . ($user?->surname ?? '')),
            'price'        => $ad->price,
            'description'  => $ad->description,
            'address'      => $ad->address,

            'rating'       => $ratingAvg !== null ? round($ratingAvg, 2) : null,
            'rating_count' => $ratingCount,

            'levels'       => $ad->levels->pluck('name')->all(),
            'formats'      => $ad->locations->pluck('name')->all(),

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
