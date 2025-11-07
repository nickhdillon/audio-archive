<?php

namespace Database\Factories;

use App\Models\Artist;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Album>
 */
class AlbumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->sentence(3, true);

        return [
            'artist_id' => Artist::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
