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
                        <div class="col-span-6 sm:col-span-4 lg:col-span-3 space-y-1">
                            <flux:button :href="route('album', $album)" wire:navigate variant="filled" class="size-40! border hover:border-neutral-300 border-neutral-200 dark:border-neutral-600 hover:dark:border-neutral-500 shadow-xs">
                                <flux:icon.disc-2 class="text-neutral-400 inset-0 size-10" />
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
                        <button x-on:click.prevent="$store.player.changeSong(@js([
                            'title' => $song->title,
                            'artist' => $song->album->artist->name,
                            'url' => Storage::disk('s3')->url($song->path),
                        ]))" class="flex items-center text-left cursor-pointer group py-3 first:pt-0 last:pb-0 gap-2.5">
                            <div class="size-10 bg-neutral-100 dark:bg-neutral-700 rounded border border-neutral-200 dark:border-neutral-600 shadow-xs flex items-center justify-center">
                                <flux:icon.music-2 class="text-neutral-400 size-5" />
                            </div>
            
                            <div class="flex flex-col flex-1 min-w-0">
                                <p class="text-sm duration-200 ease-in-out group-hover:text-neutral-600 dark:group-hover:text-neutral-400">
                                    {{ $song->title }}
                                </p>
            
                                <p class="flex items-center space-x-1 text-xs text-neutral-600 dark:text-neutral-400 truncate">
                                    <span>{{ $song->album->artist->name }}</span>
            
                                    <span>·</span>
            
                                    <span class="truncate">{{ Str::headline($song->album->name) }}</span>
                                </p>
                            </div>
                        </button>
                    @endforeach
                </div>
            </flux:tab.panel>
        </flux:tab.group>
    </div>
</div>
