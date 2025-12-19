<?php

declare(strict_types=1);

use App\Models\User;
use App\Enums\Preset;
use App\Models\EqPreset;
use App\Livewire\Equalizer;
use function Pest\Livewire\livewire;

beforeEach(fn() => $this->actingAs(User::factory()->create()));

it("can load a user's preset", function() {
    $preset = Preset::BASS_BOOST;

    $preset_model = EqPreset::factory()->create([
        'name' => $preset->label(),
        'gains' => $preset->gains(),
        'is_system' => true
    ]);

    auth()->user()->update([
        'eq_preset_id' => $preset_model->id,
        'eq_values' => $preset_model->gains
    ]);

    livewire(Equalizer::class)
        ->assertSet('preset', $preset_model->id)
        ->assertSet('is_system_preset', true)
        ->assertSet('is_user_preset', false)
        ->assertHasNoErrors();
});

it("can change a user's preset", function() {
    $preset_1 = Preset::BASS_BOOST;

    $preset_model_1 = EqPreset::factory()->create([
        'name' => $preset_1->label(),
        'gains' => $preset_1->gains(),
        'is_system' => true
    ]);

    auth()->user()->update([
        'eq_preset_id' => $preset_model_1->id,
        'eq_values' => $preset_model_1->gains
    ]);

    $preset_2 = Preset::ROCK;

    $preset_model_2 = EqPreset::factory()->create([
        'name' => $preset_2->label(),
        'gains' => $preset_2->gains(),
        'is_system' => true
    ]);

    livewire(Equalizer::class)
        ->call('saveEQ', $preset_model_2->gains)
        ->set('preset', $preset_model_2->id)
        ->assertSet('is_system_preset', true)
        ->assertSet('is_user_preset', false)
        ->assertHasNoErrors();
});

it('can set the preset to manual', function() {
    $preset_1 = Preset::BASS_BOOST;

    $preset_model = EqPreset::factory()->create([
        'name' => $preset_1->label(),
        'gains' => $preset_1->gains(),
        'is_system' => true
    ]);

    auth()->user()->update([
        'eq_preset_id' => $preset_model->id,
        'eq_values' => $preset_model->gains
    ]);

    $component = livewire(Equalizer::class)
        ->assertSet('preset', $preset_model->id)
        ->assertSet('is_system_preset', true)
        ->assertSet('is_user_preset', false);

    $preset_2 = Preset::ROCK;

    auth()->user()->update([
        'eq_preset_id' => null,
        'eq_values' => $preset_2->gains()
    ]);

    $component->call('clearPreset')
        ->assertSet('preset', 'Manual')
        ->assertSet('is_system_preset', false)
        ->assertSet('is_user_preset', false)
        ->assertHasNoErrors();
});

it('can reset the preset', function() {
    $preset = Preset::BASS_BOOST;

    $preset_model = EqPreset::factory()->create([
        'name' => $preset->label(),
        'gains' => $preset->gains(),
        'is_system' => true
    ]);

    auth()->user()->update([
        'eq_preset_id' => $preset_model->id,
        'eq_values' => $preset_model->gains
    ]);

    livewire(Equalizer::class)
        ->assertSet('preset', $preset_model->id)
        ->assertSet('is_system_preset', true)
        ->assertSet('is_user_preset', false)
        ->call('resetPreset')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('media.edit');

    $this->assertDatabaseHas('users', [
        'id' => auth()->id(),
        'eq_preset_id' => null,
        'eq_values' => null,
    ]);
});

it('can create a user preset', function() {
    $preset = Preset::BASS_BOOST;

    auth()->user()->update(['eq_values' => $preset->gains()]);

    livewire(Equalizer::class)
        ->set('name', 'Metal')
        ->call('saveAsPreset')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('media.edit');

    $this->assertDatabaseCount('eq_presets', 1);
});

it('can delete a user preset', function() {
    $preset = Preset::BASS_BOOST;

    $preset_model = EqPreset::factory()->create([
        'user_id' => auth()->id(),
        'name' => $preset->label(),
        'gains' => $preset->gains(),
        'is_system' => false
    ]);

    auth()->user()->update([
        'eq_preset_id' => $preset_model->id,
        'eq_values' => $preset_model->gains
    ]);

    livewire(Equalizer::class)
        ->call('deletePreset')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('media.edit');

    $this->assertDatabaseCount('eq_presets', 0);
});

test('component can render', function () {
    livewire(Equalizer::class)
        ->assertHasNoErrors();
});
