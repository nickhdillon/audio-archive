<?php

use App\Models\User;
use Livewire\Volt\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public string $name = '';

    public string $email = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore(Auth::id())],
        ];
    }

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfileInformation(): void
    {
        $this->validate();

        auth()
            ->user()
            ->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

        $this->dispatch('profile-updated', name: $this->name);
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your avatar, name, and email address')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            @livewire('avatar')

            <flux:field>
                <flux:label>Name</flux:label>

                <flux:input type="text" wire:model='name' required />

                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label>Email</flux:label>

                <flux:input type="email" wire:model='email' required />

                <flux:error name="email" />
            </flux:field>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" size="sm" class="w-full">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>