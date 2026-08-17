<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Users\Models\User;

class TutorListingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::query()
            ->whereHas('userRoles', function ($q) {
                $q->where('role_id', 2);
            })
            ->with([
                'preference',
                'certificates',
                'receivedRatings',
                'userSchools.school',
            ]);

        // Filtry
        if ($request->filled('category')) {
            $category = $request->get('category');
            $query->whereHas('certificates', function ($q) use ($category) {
                $q->where('name', 'like', '%' . $category . '%');
            });
        }

        if ($request->filled('price_min') || $request->filled('price_max')) {
            $query->whereHas('preference', function ($q) use ($request) {
                if ($request->filled('price_min') && $request->filled('price_max')) {
                    $q->whereBetween(DB::raw('CAST(hourly_price AS DECIMAL(10,2))'), [
                        (float) $request->price_min,
                        (float) $request->price_max,
                    ]);
                } elseif ($request->filled('price_min')) {
                    $q->whereRaw('CAST(hourly_price AS DECIMAL(10,2)) >= ?', [(float) $request->price_min]);
                } else {
                    $q->whereRaw('CAST(hourly_price AS DECIMAL(10,2)) <= ?', [(float) $request->price_max]);
                }
            });
        }

        if ($request->filled('format')) {
            $formats = is_array($request->format) ? $request->format : explode(',', $request->get('format'));
            $formats = array_map('trim', $formats);
            if ($formats !== []) {
                $query->whereHas('preference', function ($q) use ($formats) {
                    $q->whereIn('tutoring_format', $formats);
                });
            }
        }

        $minRating = $request->filled('min_rating') ? (float) $request->min_rating : 0;
        $onlyTopRated = $request->boolean('only_top_rated');
        $ratingThreshold = $minRating;
        if ($onlyTopRated && 4.5 > $ratingThreshold) {
            $ratingThreshold = 4.5;
        }
        if ($ratingThreshold > 0) {
            $query->whereHas('receivedRatings')
                ->withAvg('receivedRatings', 'rating')
                ->having('received_ratings_avg_rating', '>=', $ratingThreshold);
        } else {
            $query->withAvg('receivedRatings', 'rating');
        }

        if ($request->boolean('only_with_avatar')) {
            $query->whereNotNull('image')->where('image', '!=', '');
        }

        $perPage = max(1, min(50, (int) $request->get('per_page', 12)));
        $paginator = $query->paginate($perPage);

        $items = collect($paginator->items())->map(fn (User $user) => $this->formatTutor($user));

        return response()->json([
            'data' => $items,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ]);
    }

    /**
     * Wyświetla pełny profil korepetytora (do widoku profilu publicznego).
     */
    public function show(int $id): JsonResponse
    {
        // Uwaga: nie filtrujemy po roli "tutor" (role_id=2) — ogłoszenia
        // (Modules/Advertisements/database/seeders/AdvertisementSeeder.php)
        // mogą być przypisane do dowolnego użytkownika, więc autor ogłoszenia
        // musi być widoczny jako profil niezależnie od przypisanej roli.
        $user = User::query()
            ->with([
                'preference',
                'certificates',
                'receivedRatings.reviewer',
                'userSchools.school',
                'advertisements.field',
                'advertisements.locations',
                'advertisements.levels',
            ])
            ->findOrFail($id);

        return response()->json([
            'data' => $this->formatTutorProfile($user),
        ]);
    }

    private function formatTutorProfile(User $user): array
    {
        $ratings = $user->receivedRatings;
        $ratingAvg = $ratings->isEmpty() ? null : round($ratings->avg('rating'), 2);

        $certificateNames = $user->certificates->pluck('name')->all();
        $specialization = $certificateNames !== [] ? $certificateNames[0] : 'Korepetytor';
        $price = $user->preference?->hourly_price ?? '0';
        $format = $user->preference?->tutoring_format ?? 'online';

        $modeLabels = [
            'online'  => 'Online',
            'offline' => 'Stacjonarnie',
            'hybrid'  => 'Online / Stacjonarnie',
        ];

        return [
            'id'             => $user->id,
            'name'           => trim(($user->name ?? '') . ' ' . ($user->surname ?? '')),
            'email'          => $user->email,
            'phone'          => $user->phone,
            'avatar'         => $user->image ?? null,
            'specialization' => $specialization,
            'rating'         => $ratingAvg !== null ? (float) $ratingAvg : 0.0,
            'rating_count'   => $ratings->count(),
            'price_per_hour' => (float) $price,
            'format'         => $format,
            'mode'           => $modeLabels[$format] ?? 'Online',

            'certificates'   => $user->certificates->map(fn ($c) => [
                'id'               => $c->id,
                'name'             => $c->name,
                'issued_by'        => $c->issued_by,
                'issue_identifier' => $c->issue_identifier,
                'issue_date'       => $c->issue_date,
                'link'             => $c->link,
                'img'              => $c->img,
            ])->all(),

            'schools'        => $user->userSchools->map(fn ($us) => [
                'id'         => $us->id,
                'title'      => $us->title,
                'type'       => $us->type,
                'school'     => $us->school?->name,
                'city'       => $us->school?->city,
                'begin_date' => $us->begin_date,
                'end_date'   => $us->end_date,
            ])->all(),

            'ratings'        => $ratings->sortByDesc('created_at')->values()->map(fn ($r) => [
                'id'          => $r->id,
                'rating'      => $r->rating,
                'description' => $r->description,
                'reviewer'    => trim(($r->reviewer?->name ?? '') . ' ' . ($r->reviewer?->surname ?? '')),
                'created_at'  => $r->created_at,
            ])->all(),

            'advertisements' => $user->advertisements->map(fn ($ad) => [
                'id'          => $ad->id,
                'category'    => $ad->field?->name,
                'price'       => $ad->price,
                'description' => $ad->description,
                'address'     => $ad->address,
                'levels'      => $ad->levels->pluck('name')->all(),
                'formats'     => $ad->locations->pluck('name')->all(),
            ])->all(),
        ];
    }

    private function formatTutor(User $user): array
    {
        $ratingAvg = $user->receivedRatings->isEmpty()
            ? null
            : round($user->receivedRatings->avg('rating'), 2);

        $certificateNames = $user->certificates->pluck('name')->all();
        $specialization = $certificateNames !== [] ? $certificateNames[0] : 'Korepetytor';
        $price = $user->preference?->hourly_price ?? '0';
        $format = $user->preference?->tutoring_format ?? 'online';

        $modeLabels = [
            'online'  => 'Online',
            'offline' => 'Stacjonarnie',
            'hybrid'  => 'Online / Stacjonarnie',
        ];
        $mode = $modeLabels[$format] ?? 'Online';

        return [
            'id'              => $user->id,
            'name'            => trim(($user->name ?? '') . ' ' . ($user->surname ?? '')),
            'specialization'  => $specialization,
            'rating'          => $ratingAvg !== null ? (float) $ratingAvg : 0.0,
            'price_per_hour'  => (float) $price,
            'avatar'          => $user->image ?? null,
            'mode'            => $mode,
            'format'          => $format,
            'description'     => 'Korepetytor z doświadczeniem. Skontaktuj się, aby poznać szczegóły oferty.',
            'categories'      => $certificateNames,
            'has_avatar'      => ! empty($user->image),
        ];
    }
}
