<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Enums\Repeat;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'avatar' => null,
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'shuffle' => false,
            'repeat' => Repeat::OFF,
            'remember_token' => Str::random(10),
        ];
    }
}
