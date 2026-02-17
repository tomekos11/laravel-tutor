<?php

namespace Modules\Advertisements\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdvertisementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id'     => 'prohibited',
            'price'       => 'required|numeric|min:0|max:999999.99',
            'description' => 'required|string|max:5000',
            'field_id'    => 'required|exists:course__fields,id',
            'address'     => 'required|string|max:255',
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
            'user_id.prohibited' => 'Nie można ustawiać user_id ręcznie. Ogłoszenie zostanie przypisane do zalogowanego użytkownika.',
            'price.required' => 'Cena jest wymagana.',
            'price.numeric' => 'Cena musi być liczbą.',
            'price.min' => 'Cena nie może być ujemna.',
            'description.required' => 'Opis jest wymagany.',
            'description.max' => 'Opis nie może przekraczać 5000 znaków.',
            'field_id.required' => 'Kategoria jest wymagana.',
            'field_id.exists' => 'Wybrana kategoria nie istnieje.',
            'address.required' => 'Adres jest wymagany.',
            'address.max' => 'Adres nie może przekraczać 255 znaków.',
            'level_ids.array' => 'Poziomy muszą być tablicą.',
            'level_ids.*.exists' => 'Jeden z wybranych poziomów nie istnieje.',
            'location_ids.array' => 'Lokalizacje muszą być tablicą.',
            'location_ids.*.exists' => 'Jedna z wybranych lokalizacji nie istnieje.',
        ];
    }
}
