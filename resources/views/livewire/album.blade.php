<div class="space-y-4 max-w-4xl mb-16 mx-auto">
    <flux:heading size="xl">
        {{ $album->name }}
    </flux:heading>

    <div class="flex flex-col divide-y divide-neutral-200 dark:divide-neutral-600">
        @foreach ($album->songs as $song)
            <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                <button class="flex text-left cursor-pointer items-center group gap-2.5"
                    x-on:click="$dispatch('change-song', { song: 
                        @js([
                            'id' => $song->id,
                            'title' => $song->title,
                            'artist' => $album->artist->name,
                            'path' => Storage::disk('s3')->url($song->path),
                            'playtime' => $song->playtime
                        ])
                    })"
                >
                    <div class="size-10 bg-neutral-100 dark:bg-neutral-700 rounded border border-neutral-200 dark:border-neutral-600 shadow-xs flex items-center justify-center">
                        <flux:icon.music-2 class="text-neutral-400 size-5" />
                    </div>

                    <div class="flex flex-col flex-1 min-w-0">
                        <p class="text-sm duration-200 ease-in-out group-hover:text-neutral-600 dark:group-hover:text-neutral-400">
                            {{ $song->track_number }}. {{ $song->title }}
                        </p>

                        <p class="text-xs text-neutral-600 dark:text-neutral-400 truncate">
                            {{ $album->artist->name }}
                        </p>
                    </div>
                </button>

                <button x-on:click="$dispatch('add-to-queue', { song_id: {{ $song->id }} })" class="cursor-pointer group">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4 stroke-current group-hover:stroke-neutral-500 duration-100 ease-in-out"><path d="M16 5H3"/><path d="M11 12H3"/><path d="M16 19H3"/><path d="M18 9v6"/><path d="M21 12h-6"/></svg>
                </button>
            </div>
        @endforeach
    </div>
</div>
