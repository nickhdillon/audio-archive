<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky collapsible stashable
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            <flux:sidebar.brand
                href="{{ route('artists') }}"
                logo="{{ asset('icon.png') }}"
                name="Audio Archive"
            />

            <flux:sidebar.collapse
                class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="circle-user-round" :href="route('artists')" :current="request()->routeIs('artists', 'artist*')"
                wire:navigate>
                Artists
            </flux:sidebar.item>

            <flux:sidebar.item icon="disc-album" :href="route('albums')" :current="request()->routeIs('albums', 'album*')"
                wire:navigate>
                Albums
            </flux:sidebar.item>

            <flux:sidebar.item icon="music-4" :href="route('songs')" :current="request()->routeIs('songs')"
                wire:navigate>
                Songs
            </flux:sidebar.item>

            {{-- <flux:sidebar.item icon="boom-box" :href="route('artists')" :current="request()->routeIs('artists')"
                wire:navigate>
                Genres
            </flux:sidebar.item> --}}

            {{-- <flux:sidebar.item icon="library-big" :href="route('artists')" :current="request()->routeIs('artists')"
                wire:navigate>
                Playlists
            </flux:sidebar.item> --}}

            <flux:sidebar.item icon="upload" :href="route('upload')" :current="request()->routeIs('upload')"
                wire:navigate>
                Upload
            </flux:sidebar.item>
        </flux:sidebar.nav>

        <flux:spacer />

        <!-- Desktop User Menu -->
        <flux:dropdown position="bottom" align="start">
            @if (auth()->user()->avatar)
                <flux:sidebar.profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                    :avatar="Storage::disk('s3')->url('users/' . auth()->id() . '/avatars/' . auth()->user()->avatar)"
                    icon-trailing="chevrons-up-down" />
            @else
                <flux:sidebar.profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down" />
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
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden text-zinc-800! dark:text-zinc-200!" icon="panel-left" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            @if (auth()->user()->avatar)
                <flux:profile :initials="auth()->user()->initials()"
                    :avatar="Storage::disk('s3')->url('users/' . auth()->id() . '/avatars/' . auth()->user()->avatar)"
                    icon-trailing="chevrons-up-down" />
            @else
                <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevrons-up-down" />
            @endif

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    @if (auth()->user()->avatar)
                                        <img
                                            src="{{ Storage::disk('s3')->url('users/' . auth()->id() . '/avatars/' . auth()->user()->avatar) }}" />
                                    @else
                                        <p>{{ auth()->user()->initials() }}</p>
                                    @endif
                                </span>
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
                    <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>Settings</flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
                </flux:menu.item>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @persist('toast')
        <flux:toast.group position="top end">
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @filepondScripts
    @fluxScripts
</body>

</html>
