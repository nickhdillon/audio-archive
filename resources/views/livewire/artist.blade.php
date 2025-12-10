<div class="space-y-4 max-w-4xl mb-16 mx-auto" x-data="{ tab: 'albums' }">
    <flux:heading size="xl">
        {{ $artist->name }}
    </flux:heading>

    <div>
        <flux:tab.group>
            <flux:tabs x-model="tab">
                <flux:tab name="albums">Albums</flux:tab>
                <flux:tab name="songs">Songs</flux:tab>
            </flux:tabs>

            <flux:tab.panel name="albums">
                <div class="grid grid-cols-12 -mt-5 gap-6">
                    @foreach ($artist->albums as $album)
                        <div class="col-span-6 sm:col-span-4 lg:col-span-3 space-y-1"
                            wire:key='{{ $album->id }}'
                        >
                            <flux:button :href="route('album', $album)" wire:navigate variant="filled"
                                @class([
                                    'p-0!' => $album->artwork_url,
                                    'size-40! border hover:border-neutral-300 border-neutral-200 dark:border-neutral-600 hover:dark:border-neutral-500 shadow-xs'
                                ])
                            >
                                @if ($album->artwork_url)
                                    <img
                                        src="{{ config('filesystems.disks.s3.url') . $album->artwork_url }}"
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
                <div class="flex flex-col divide-y -mt-5 divide-neutral-200 dark:divide-neutral-600">
                    @foreach ($artist->songs as $song)
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
                                    <p class="text-sm duration-200 ease-in-out group-hover:text-neutral-600 dark:group-hover:text-neutral-400">
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
                                        <flux:menu.submenu icon="plus" heading="Add to playlist">
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

    <livewire:playlist-form />
</div>
