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
