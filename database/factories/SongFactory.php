<?php

namespace Database\Factories;

use App\Models\Album;
use Illuminate\Support\Str;
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
        $album = Album::factory()->create();
        $artist = $album->artist;

        $filename = Str::slug($this->faker->words(2, true)) . '.mp3';
        $title = $this->faker->sentence(3, true);

        return [
            'album_id' => $album->id,
            'title' => $title,
            'slug' => Str::slug($title),
            'filename' => $filename,
            'playtime' => '3:30',
            'track_number' => $this->faker->numberBetween(1, 15),
            'path' => "users/{$artist->user_id}/files/{$artist->name}/{$album->name}/{$filename}",
        ];
    }
}
