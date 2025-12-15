<?php

namespace Database\Seeders;

use App\Enums\Preset;
use App\Models\EqPreset;
use Illuminate\Database\Seeder;

class EqPresetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect(Preset::cases())->each(function (Preset $preset): void {
            EqPreset::create([
                'name' => $preset->label(),
                'gains' => $preset->gains(),
                'is_system' => true
            ]);
        });
    }
}
