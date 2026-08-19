<?php

namespace Modules\Advertisements\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdvertisementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Można później dodać middleware autoryzacji
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id'     => 'prohibited',
            'price'       => 'sometimes|numeric|min:0|max:999999.99',
            'description' => 'sometimes|string|max:5000',
            'field_id'    => 'sometimes|exists:course__fields,id',
            'address'     => 'sometimes|nullable|string|max:255',
            'travel_radius_km' => 'sometimes|nullable|integer|min:0|max:1000',
            'price_per_km' => 'sometimes|nullable|numeric|min:0|max:999.99',
            'level_ids'   => 'sometimes|array',
            'level_ids.*' => 'exists:course__levels,id',
            'location_ids' => 'sometimes|array',
            'location_ids.*' => 'exists:advertisement__locations,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_id.prohibited' => 'Nie można zmieniać user_id ogłoszenia.',
            'price.numeric' => 'Cena musi być liczbą.',
            'price.min' => 'Cena nie może być ujemna.',
            'description.max' => 'Opis nie może przekraczać 5000 znaków.',
            'field_id.exists' => 'Wybrana kategoria nie istnieje.',
            'address.max' => 'Adres nie może przekraczać 255 znaków.',
            'travel_radius_km.integer' => 'Promień dojazdu musi być liczbą całkowitą.',
            'price_per_km.numeric' => 'Cena za km musi być liczbą.',
            'level_ids.array' => 'Poziomy muszą być tablicą.',
            'level_ids.*.exists' => 'Jeden z wybranych poziomów nie istnieje.',
            'location_ids.array' => 'Lokalizacje muszą być tablicą.',
            'location_ids.*.exists' => 'Jedna z wybranych lokalizacji nie istnieje.',
        ];
    }
}
