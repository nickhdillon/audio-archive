<div class="space-y-4 mb-22">
    <div class="flex sm:justify-between gap-2">
        <flux:input
            icon="magnifying-glass"
            placeholder="Search..."
            wire:model.live.debounce.300ms='search'
            clearable
            class="sm:max-w-[300px]!"
        />

        <flux:modal.trigger name="add-playlist" class="sm:hidden">
            <flux:button icon="plus" variant="primary" class="rounded-full! h-9.5! w-11" />
        </flux:modal.trigger>
        
        <flux:modal.trigger name="add-playlist" class="hidden sm:block">
            <flux:button icon="plus" variant="primary" class="h-9.5!">
                Add
            </flux:button>
        </flux:modal.trigger>
    </div>

    <div class="flex flex-col divide-y divide-neutral-300 dark:divide-neutral-700">
        @foreach ($playlists as $playlist)
            <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                <a href="{{ route('playlist', $playlist) }}" wire:navigate class="flex text-left cursor-pointer items-center group gap-2.5"
                >
                    <div class="flex flex-col">
                        <p class="text-sm duration-200 ease-in-out group-hover:text-neutral-600 dark:group-hover:text-neutral-400">
                            {{ $playlist->name }}
                        </p>

                        <p class="text-xs text-neutral-600 dark:text-neutral-400">
                            {{ $playlist->songs_count }} {{ Str::plural('song', $playlist->songs_count) }}
                        </p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
