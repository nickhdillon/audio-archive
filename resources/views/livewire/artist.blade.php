<div class="space-y-4 mb-22" x-data="{ tab: $wire.entangle('tab') }">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <flux:heading size="xl">
            {{ $artist->name }}
        </flux:heading>

        <div x-cloak x-show="tab ==='songs'" class="flex -my-1 items-center gap-6">
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

    <div>
        <flux:tab.group>
            <flux:tabs x-model="tab">
                <flux:tab name="albums">Albums</flux:tab>
                <flux:tab name="songs">Songs</flux:tab>
            </flux:tabs>

            <flux:tab.panel name="albums">
                <div class="grid grid-cols-12 -mt-5 gap-6">
                    @foreach ($albums as $album)
                        <div class="col-span-6 sm:col-span-4 lg:col-span-3 space-y-1"
                            wire:key='{{ $album->id }}'
                        >
                            <flux:button :href="route('album', $album)" wire:navigate variant="filled"
                                @class([
                                    'p-0!' => $album->artwork_url,
                                    'size-40! border hover:border-neutral-200 border-neutral-300 dark:border-neutral-700 hover:dark:border-neutral-600 shadow-xs shadow-black/10 dark:shadow-black/20'
                                ])
                            >
                                @if ($album->artwork_url)
                                    <img
                                        src="{{ Storage::disk('s3')->url($album->artwork_url) }}"
                                        class="object-cover inset-0 rounded-[7px] w-full"
                                        loading='lazy'
                                    />
                                @else
                                    <flux:icon.disc-2 class="text-neutral-400 inset-0 size-10" />
                                @endif
                            </flux:button>
            
                            <div class="flex flex-col w-40 truncate">
                                <p class="text-sm truncate">
                                    {{ Str::headline($album->name) }}
                                </p>
            
                                <p class="text-xs text-neutral-600 dark:text-neutral-400">
                                    {{ $album->songs_count }} {{ Str::plural('song', $album->songs_count) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </flux:tab.panel>

            <flux:tab.panel name="songs">
                <div class="flex flex-col divide-y -mt-5 divide-neutral-300 dark:divide-neutral-700">
                    @foreach ($songs as $song)
                        <div class="flex items-center gap-4 justify-between py-3 first:pt-0 last:pb-0"
                            wire:key='{{ $song->id }}'
                        >
                            <button class="flex items-center text-left cursor-pointer flex-1 min-w-0 group gap-2.5"
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
                
                                <div class="flex flex-col truncate flex-1 min-w-0">
                                    <p class="text-sm duration-200 truncate ease-in-out group-hover:text-neutral-600 dark:group-hover:text-neutral-400">
                                        {{ $song->title }}
                                    </p>
                
                                    <p class="flex items-center space-x-1 text-xs text-neutral-600 dark:text-neutral-400 truncate">
                                        <span>{{ $song->display_artist }}</span>
                
                                        <span>·</span>
                
                                        <span class="truncate">{{ Str::headline($song->album->name) }}</span>
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
                                            <flux:menu.radio.group class="flex flex-col">
                                                <flux:modal.trigger name="add-playlist">
                                                    <button
                                                        class="flex w-full items-center gap-2 px-2.5 py-1.5 font-medium text-sm text-start rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600 group"
                                                        type="button"
                                                    >
                                                        <flux:icon.plus class="text-neutral-400 group-hover:text-neutral-800 dark:text-neutral-400 dark:group-hover:text-neutral-100 size-4.5 stroke-2" />
                                                    
                                                        <p>New playlist</p>
                                                    </button>
                                                </flux:modal.trigger>
                                                
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
            </flux:tab.panel>
        </flux:tab.group>
    </div>
</div>
