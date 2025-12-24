<div class="space-y-4 mb-22">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">
            {{ $playlist->name }}
        </flux:heading>

        <div>
            <flux:modal.trigger name="{{ 'edit-playlist' . $playlist->id }}">
                <flux:button icon="pencil-square" variant="primary" size="sm">
                    Edit
                </flux:button>
            </flux:modal.trigger>

            <livewire:playlist-form :$playlist/>
        </div>
    </div>

    <div class="flex items-center justify-between gap-6">
        <div class="flex items-center gap-4 pb-1">
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
            class="max-w-[250px]!"
        />
    </div>

    <div class="flex flex-col divide-y divide-neutral-300 dark:divide-neutral-700"
        x-sort="$wire.handleSort($item, $position)"
    >
        @forelse ($songs as $song)
            <div class="flex items-center justify-between py-3 gap-4 first:pt-0 last:pb-0"
                x-sort:item="{{ $song->id }}"
                wire:key='{{ $song->id }}'
            >
                <span class="cursor-move" x-sort:handle>
                    <flux:icon.text-align-justify class="cursor-move size-4 -mr-1 dark:text-neutral-100" />
                </span>

                <button
                    x-sort:ignore
                    class="flex text-left flex-1 min-w-0 cursor-pointer items-center group gap-2.5"
                    x-on:click="$dispatch('change-song', { song: 
                        @js([
                            'id' => $song->id,
                            'title' => $song->title,
                            'artist' => $song->display_artist,
                            'path' => Storage::disk('s3')->url($song->path),
                            'playtime' => $song->playtime,
                            'album' => $song->album->name,
                            'artwork' => Storage::disk('s3')->url($song->album->artwork_url)
                        ])
                    })"
                >
                    <div class="size-10 bg-neutral-100 dark:bg-neutral-800 rounded-sm border border-neutral-300 dark:border-neutral-700 shadow-xs shadow-black/10 dark:shadow-black/20 flex items-center justify-center">
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
        @empty
            <div class="text-center text-sm font-medium italic">
                No songs found...
            </div>
        @endforelse
    </div>

    @if ($songs->count()) 
        <flux:pagination :paginator="$songs" class="border-neutral-300! dark:border-neutral-700! -mt-1" />
    @endif
</div>
