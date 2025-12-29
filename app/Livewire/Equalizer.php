<?php

declare(strict_types=1);

namespace App\Livewire;

use Flux\Flux;
use App\Models\User;
use Livewire\Component;
use App\Models\EqPreset;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection;

class Equalizer extends Component
{
    public User $user;

    public int|string $preset = '';

    public bool $is_system_preset = false;

    public bool $is_user_preset = false;

    public string $name = '';

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('eq_presets', 'name')->where(function (Builder $query): Builder {
                    return $query->where('user_id', $this->user->id);
                })
            ]
        ];
    }

    public function mount(): void
    {
        $this->user = auth()->user();

        $preset = EqPreset::find($this->user->eq_preset_id);

        if ($preset) {
            $this->preset = $preset->id;
            $this->is_system_preset = $preset->is_system;
            $this->is_user_preset = $preset->user()->exists() ? true : false;
        } else {
            $this->preset = 'Manual';
        }
    }

    #[Computed]
    public function presets(): Collection
    {
        return EqPreset::query()
            ->where('is_system', true)
            ->orWhere('user_id', $this->user->id)
            ->get();
    }

    public function updatedPreset(): void
    {
        $this->user->update(['eq_preset_id' => $this->preset]);

        $this->reset(['is_user_preset', 'is_system_preset']);

        $preset_model = EqPreset::find($this->preset);

        $this->is_user_preset = $preset_model->user()->exists();

        $this->is_system_preset = $preset_model->is_system;
    }

    #[On('eq-manual-change')]
    public function clearPreset(): void
    {
        $this->user->update(['eq_preset_id' => null]);

        $this->preset = 'Manual';

        $this->is_user_preset = false;
        $this->is_system_preset = false;
    }

    public function resetPreset(): void
    {
        $this->user->update([
            'eq_preset_id' => null,
            'eq_values' => null
        ]);

        $this->resetExcept('user');

        $this->dispatch('preset-reset');
    }

    #[On('eq-changed')]
    public function saveEQ(array $eq_values): void
    {
        $this->user->update(['eq_values' => $eq_values]);
    }

    public function saveAsPreset(): void
    {
        $this->validate();

        $new_preset = EqPreset::create([
            'user_id' => $this->user->id,
            'name' => $this->name,
            'gains' => $this->user->eq_values
        ]);

        $this->user->update(['eq_preset_id' => $new_preset->id]);

        Flux::toast(
            variant: 'success',
            text: 'Preset successfully created',
        );

        Flux::modals()->close();

        $this->redirectRoute('media.edit', navigate: true);
    }

    public function deletePreset(): void
    {
        EqPreset::find($this->preset)->delete();

        $this->user->update(['eq_values' => null]);

        Flux::toast(
            variant: 'success',
            text: 'Preset successfully deleted',
        );

        Flux::modals()->close();

        $this->redirectRoute('media.edit', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.equalizer');
    }
}
