<?php

namespace Modules\Advertisements\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Advertisements\Models\Advertisement;

class AdvertisementsController extends Controller
{
    public function index(): JsonResponse
    {
        $ads = Advertisement::with([
                'field',
                'user.receivedRatings',
                'locations',
                'levels',
            ])->get();

        $data = $ads->map(function (Advertisement $ad) {
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
            ];
        });

        return response()->json($data);
    }
}
