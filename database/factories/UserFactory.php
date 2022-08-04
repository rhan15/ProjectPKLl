<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        static $pin;
        return [
            'phone_num' => str::random(12),
            'verified' => $verified = $this->faker->randomElement([User::VERIFIED_USER, User::UNVERIFIED_USER]),
            'pin' => $pin ?: $pin = bcrypt('secret'), // password
            'admin' => $admin = $this->faker->randomElement([User::ADMIN_USER, User::REGULAR_USER]),
            'access_token' => User::generateAccessToken(),
            'otp' => $verified == User::VERIFIED_USER ? null : User::generateOTP(),

            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
