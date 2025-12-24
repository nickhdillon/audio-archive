<div class="space-y-6 mb-22">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <flux:heading size="xl">
            {{ $album->name }}
        </flux:heading>

        <div class="flex -my-1 items-center gap-6">            
            <div class="flex items-center gap-4">
                <button class="hover:scale-110 cursor-pointer bg-neutral-800 dark:bg-neutral-100 flex items-center justify-center rounded-full size-7"
                    wire:click='playSongs'
                >
                    <flux:icon.play class="size-[15px] stroke-neutral-50 dark:stroke-neutral-800 fill-neutral-100 dark:fill-neutral-800" />
                </button>

                <button wire:click='playSongs(true)' class="hover:scale-110 cursor-pointer">
                    <flux:icon.shuffle class="size-[18px] stroke-[2.5px] text-neutral-800 dark:text-neutral-100" />
                </button>
            </div>

            <flux:input
                icon="magnifying-glass"
                placeholder="Search..."
                wire:model.live.debounce.300ms='search'
                clearable
                class="max-w-[250px] sm:max-w-[225px]"
            />
        </div>
    </div>

    <div class="flex flex-col divide-y divide-neutral-300 dark:divide-neutral-700">
        @foreach ($songs as $song)
            <div class="flex items-center justify-between gap-4 py-3 first:pt-0 last:pb-0">
                <button class="flex text-left cursor-pointer flex-1 min-w-0 items-center group gap-2.5"
                    x-on:click="$dispatch('change-song', { song: 
                        @js([
                            'id' => $song->id,
                            'title' => $song->title,
                            'artist' => $song->display_artist,
                            'path' => Storage::disk('s3')->url($song->path),
                            'playtime' => $song->playtime,
                            'album' => $album->name,
                            'artwork' => Storage::disk('s3')->url($album->artwork_url)
                        ])
                    })"
                >
                    <div class="size-10 bg-neutral-100 dark:bg-neutral-700 rounded-sm border border-neutral-300 dark:border-neutral-700 shadow-xs shadow-black/10 dark:shadow-black/20 flex items-center justify-center">
                        @if ($song->album->artwork_url)
                            <img
                                src="{{ Storage::disk('s3')->url($song->album->artwork_url) }}"
                                class="object-cover inset-0 rounded-[3px] w-full"
                                loading='lazy'
                            />
                        @else
                            <flux:icon.music-2 class="text-neutral-400 size-5" />
                        @endif
                    </div>

                    <div class="flex flex-col flex-1 min-w-0">
                        <p class="text-sm duration-200 truncate ease-in-out group-hover:text-neutral-600 dark:group-hover:text-neutral-400">
                            {{ $song->track_number }}. {{ $song->title }}
                        </p>

                        <p class="text-xs text-neutral-600 dark:text-neutral-400 truncate">
                            {{ $song->display_artist }}
                        </p>
                    </div>
                </button>

                <div class="flex items-center gap-3">
                    <flux:dropdown>
                        <flux:button variant="ghost" size="sm" class="hover:bg-transparent! -mr-1 w-2! cursor-pointer">
                            <flux:icon.ellipsis-horizontal class="text-neutral-800 dark:text-neutral-100" />
                        </flux:button>

                        <flux:menu>
                            <flux:menu.submenu icon="plus-circle" heading="Add to playlist">
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
</div>
