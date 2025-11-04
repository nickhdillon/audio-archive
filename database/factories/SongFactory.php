<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Song>
 */
class SongFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $filename = Str::slug($this->faker->words(2, true)) . '.mp3';
        $album = $this->faker->sentence(2, true);
        $artist = $this->faker->name;

        return [
            'user_id' => User::factory(),
            'filename' => $filename,
            'title' => $this->faker->sentence(3, true),
            'album' => $album,
            'track_number' => $this->faker->numberBetween(1, 15),
            'playtime' => '3:30',
            'artist' => $artist,
            'genre' => Arr::random([
                'Pop', 'Rock', 'Hip-Hop', 'Jazz', 'Classical', 'Electronic', 'Country', 'R&B'
            ]),
            'path' => "users/1/files/{$artist}/{$album}/{$filename}"
        ];
    }
}
