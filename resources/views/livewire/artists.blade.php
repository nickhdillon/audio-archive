<div class="space-y-4 max-w-4xl mb-16 mx-auto">
    <flux:heading size="xl">
        Artists
    </flux:heading>

    <div class="grid grid-cols-12 gap-6">
        @foreach ($artists as $artist)
            <div class="col-span-6 sm:col-span-4 lg:col-span-3 space-y-1">
                <flux:button :href="route('artist', $artist)" wire:navigate variant="filled" class="size-40! border hover:border-neutral-300 border-neutral-200 dark:border-neutral-600 hover:dark:border-neutral-500 shadow-xs">
                    <flux:icon.user class="text-neutral-400 inset-0 size-10" />
                </flux:button>

                <div class="flex flex-col w-40">
                    <p class="text-sm truncate">
                        {{ $artist->name }}
                    </p>

                    <p class="text-xs space-x-0.5 text-neutral-600 dark:text-neutral-400">
                        <span>
                            {{ $artist->albums_count }} {{ Str::plural('album', $artist->albums_count) }}
                        </span>

                        <span>·</span>

                        <span>
                            {{ $artist->songs_count }} {{ Str::plural('song', $artist->songs_count) }}
                        </span>
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</div>
