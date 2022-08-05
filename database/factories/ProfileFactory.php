<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'gender' => $gender = $this->faker->randomElement([Profile::GENDER_MALE, Profile::GENDER_FEMALE, Profile::GENDER_OTHER]),
            'birth_date' => $this->faker->date(),
            'user_id' => $this->faker->unique()->numberBetween(1, User::count()),
        ];
    }
}
