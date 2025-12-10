<div class="space-y-4 max-w-4xl mb-16 mx-auto">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">
            Playlists
        </flux:heading>

        <div>
            <flux:modal.trigger name="add-playlist">
                <flux:button icon="plus" variant="primary" size="sm">
                    Add
                </flux:button>
            </flux:modal.trigger>

            <livewire:playlist-form />
        </div>
    </div>

    <div class="flex flex-col divide-y divide-neutral-200 dark:divide-neutral-600">
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
