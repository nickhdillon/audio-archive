<div class="space-y-4 max-w-4xl mb-16 mx-auto">
    <flux:heading size="xl">
        Songs
    </flux:heading>

    <div class="flex flex-col divide-y divide-neutral-200 dark:divide-neutral-600">
        @foreach ($songs as $song)
            <button class="flex text-left cursor-pointer items-center group py-3 first:pt-0 last:pb-0 gap-2.5"
                x-on:click.prevent="$store.player.changeSong(@js([
                    'title' => $song->title,
                    'artist' => $song->album->artist->name,
                    'url' => Storage::disk('s3')->url($song->path),
                ]))"
            >
                <div class="size-10 bg-neutral-100 dark:bg-neutral-700 rounded border border-neutral-200 dark:border-neutral-600 shadow-xs flex items-center justify-center">
                    <flux:icon.music-2 class="text-neutral-400 size-5" />
                </div>

                <div class="flex flex-col flex-1 min-w-0">
                    <p class="text-sm duration-200 ease-in-out group-hover:text-neutral-600 dark:group-hover:text-neutral-400">
                        {{ $song->title }}
                    </p>

                    <p class="text-xs space-x-0.5 text-neutral-600 dark:text-neutral-400 truncate">
                        <span>{{ $song->album->artist->name }}</span>

                        <span>·</span>

                        <span>{{ $song->album->name }}</span>
                    </p>
                </div>
            </button>
        @endforeach
    </div>

    <flux:pagination :paginator="$songs" class="border-neutral-200! dark:border-neutral-600! -mt-1" />
</div>
