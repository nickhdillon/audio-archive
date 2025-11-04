<?php

namespace Database\Seeders;

use App\Models\Song;
use App\Models\User;
use Illuminate\Database\Seeder;

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Song::factory()
            ->for(User::factory()->create())
            ->count(10)
            ->create();
    }
}
