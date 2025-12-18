<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-neutral-900">
    <flux:header container class="flex items-center bg-white dark:bg-neutral-900 -mb-4 lg:-mb-6! overflow-scroll [scrollbar-width:none] w-full max-w-6xl mx-auto">
        <flux:dropdown position="top" align="start" class="absolute bg-white dark:bg-neutral-900 -ml-6 pl-5 pr-1 z-50">
            @if (auth()->user()->avatar)
                <flux:sidebar.profile
                    :avatar="Storage::disk('s3')->url('users/' . auth()->id() . '/avatars/' . auth()->user()->avatar)"
                    :chevron="false"
                    circle
                />
            @else
                <flux:sidebar.profile
                    :initials="auth()->user()->initials()"
                    :chevron="false"
                    circle
                />
            @endif

            <flux:menu class="w-[220px]">
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <div
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    @if (auth()->user()->avatar)
                                        <img
                                            src="{{ Storage::disk('s3')->url('users/' . auth()->id() . '/avatars/' . auth()->user()->avatar) }}" />
                                    @else
                                        <p>{{ auth()->user()->initials() }}</p>
                                    @endif
                                </div>
                            </span>

                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:radio.group x-data variant="segmented" size="sm" x-model="$flux.appearance">
                    <flux:radio value="light" icon="sun" />
                    <flux:radio value="dark" icon="moon" />
                    <flux:radio value="system" icon="computer-desktop" />
                </flux:radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>

        <flux:tabs variant="pills" scrollable class="ml-11 gap-1.5! pr-4 h-7!">
            <x-nav-pill
                :route="route('artists')"
                :current="request()->routeIs('artists', 'artist*')"
            >
                Artists
            </x-nav-pill>

            <x-nav-pill
                :route="route('albums')"
                :current="request()->routeIs('albums', 'album*')"
            >
                Albums
            </x-nav-pill>

            <x-nav-pill
                :route="route('songs')"
                :current="request()->routeIs('songs')"
            >
                Songs
            </x-nav-pill>

            <x-nav-pill
                :route="route('playlists')"
                :current="request()->routeIs('playlists', 'playlist*')"
            >
                Playlists
            </x-nav-pill>
        </flux:tabs>
    </flux:header>

    {{ $slot }}

    <livewire:playlist-form />

    @persist('toast')
        <flux:toast.group position="top end">
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @persist('audio-player')
        <livewire:audio-player />
    @endpersist

    @filepondScripts
    @fluxScripts
    @livewireScriptConfig
</body>

</html>
