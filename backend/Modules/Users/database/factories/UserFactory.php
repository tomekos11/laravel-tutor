<?php

namespace Modules\Users\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Users\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->userName,
            'email'    => $this->faker->unique()->safeEmail,
            'phone'    => $this->faker->unique()->numerify('#########'),
            'name'     => $this->faker->firstName,
            'surname'  => $this->faker->lastName,
            'birthday' => $this->faker->date(),
            'password' => Hash::make('password'),
        ];
    }
}
