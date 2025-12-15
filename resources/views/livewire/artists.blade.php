<div class="space-y-4 max-w-4xl mb-16 mx-auto">
    <div class="flex items-center justify-between gap-6">
        <flux:heading size="xl">
            Artists
        </flux:heading>

        <flux:input
            icon="magnifying-glass"
            placeholder="Search..."
            wire:model.live.debounce.300ms='search'
            clearable
            class="max-w-[250px]!"
        />
    </div>

    <div class="flex flex-col divide-y divide-neutral-200 dark:divide-neutral-600">
        @foreach ($artists as $artist)
            <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                <a href="{{ route('artist', $artist) }}" wire:navigate class="flex text-left cursor-pointer items-center group gap-2.5"
                >
                    <div class="flex flex-col">
                        <p class="text-sm duration-200 ease-in-out group-hover:text-neutral-600 dark:group-hover:text-neutral-400">
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
                </a>
            </div>
        @endforeach
    </div>

    <flux:pagination :paginator="$artists" class="border-neutral-200! dark:border-neutral-600! -mt-1" />
</div>
