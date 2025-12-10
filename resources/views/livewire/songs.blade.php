<div class="space-y-4 max-w-4xl mb-16 mx-auto">
    <flux:heading size="xl">
        Songs
    </flux:heading>

    <div class="flex flex-col divide-y divide-neutral-200 dark:divide-neutral-600">
        @foreach ($songs as $song)
            <div class="flex items-center justify-between py-3 gap-4 first:pt-0 last:pb-0">
                <button class="flex text-left flex-1 min-w-0 cursor-pointer items-center group gap-2.5"
                    x-on:click="$dispatch('change-song', { song: 
                        @js([
                            'id' => $song->id,
                            'title' => $song->title,
                            'artist' => $song->display_artist,
                            'path' => Storage::disk('s3')->url($song->path),
                            'playtime' => $song->playtime,
                            'artwork' => $song->album->artwork_url
                        ])
                    })"
                >
                    <div class="size-10 bg-neutral-100 dark:bg-neutral-700 rounded-sm border border-neutral-200 dark:border-neutral-600 shadow-xs flex items-center justify-center">
                        @if ($song->album->artwork_url)
                            <img
                                src="{{ config('filesystems.disks.s3.url') . $song->album->artwork_url }}"
                                class="object-cover inset-0 rounded-[3px] w-full"
                                loading='lazy'
                            />
                        @else
                            <flux:icon.music-2 class="text-neutral-400 size-5" />
                        @endif
                    </div>

                    <div class="flex flex-col flex-1 min-w-0">
                        <p class="text-sm truncate duration-200 ease-in-out group-hover:text-neutral-600 dark:group-hover:text-neutral-400">
                            {{ $song->title }}
                        </p>

                        <p class="text-xs space-x-0.5 text-neutral-600 dark:text-neutral-400 truncate">
                            <span>{{ $song->display_artist }}</span>

                            <span>·</span>

                            <span>{{ $song->album->name }}</span>
                        </p>
                    </div>
                </button>

                <div class="flex items-center gap-3">
                    <flux:dropdown>
                        <flux:button variant="ghost" size="sm" class="hover:bg-transparent! -mr-1 w-2! cursor-pointer">
                            <flux:icon.ellipsis-horizontal class="text-neutral-800 dark:text-neutral-100" />
                        </flux:button>

                        <flux:menu>
                            <flux:menu.submenu icon="plus" heading="Add to playlist">
                                <flux:modal.trigger name="add-playlist">
                                    <button
                                        class="flex w-full items-center gap-2 px-2.5 py-1.5 font-medium text-sm text-start rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600 group"
                                        type="button"
                                    >
                                        <flux:icon.plus class="text-neutral-400 group-hover:text-neutral-800 dark:text-neutral-400 dark:group-hover:text-neutral-100 size-4.5 stroke-2" />
                                    
                                        <p>New playlist</p>
                                    </button>
                                </flux:modal.trigger>

                                <flux:menu.radio.group class="flex flex-col">
                                    @foreach ($playlists as $playlist) 
                                        <button
                                            class="px-2.5 py-1.5 font-medium text-sm text-start rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600"
                                            wire:click='addToPlaylist({{ $playlist->id }}, {{ $song->id }})'
                                        >
                                            {{ $playlist->name }}
                                        </button>
                                    @endforeach
                                </flux:menu.radio.group>
                            </flux:menu.submenu>

                            <flux:menu.item
                                icon="list-plus"
                                x-on:click="$dispatch('add-to-queue', { song_id: {{ $song->id }} })"
                            >
                                Add to queue
                            </flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>                    
                </div>
            </div>
        @endforeach
    </div>

    <livewire:playlist-form />

    <flux:pagination :paginator="$songs" class="border-neutral-200! dark:border-neutral-600! -mt-1" />
</div>
