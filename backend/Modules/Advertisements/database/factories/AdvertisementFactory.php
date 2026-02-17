<?php

namespace Modules\Advertisements\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Advertisements\Models\Advertisement;
use Modules\Users\Models\User;
use Modules\Courses\Models\Field;

class AdvertisementFactory extends Factory
{
    protected $model = Advertisement::class;

    public function definition(): array
    {
        return [
            'user_id'     => function () {
                return User::query()->inRandomOrder()->value('id')
                    ?? User::factory()->create()->id;
            },
            'field_id'    => function () {
                $fieldId = Field::query()->inRandomOrder()->value('id');
                if ($fieldId) {
                    return $fieldId;
                }

                // fallback: minimalny rekord, jeśli pola jeszcze nie zostały zseedowane
                return Field::query()->create(['name' => $this->faker->word])->id;
            },
            'price'       => $this->faker->randomFloat(2, 50, 500),
            'description' => $this->faker->paragraph(3),
            'address'     => $this->faker->address,
        ];
    }
}
