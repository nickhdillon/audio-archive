<div class="space-y-4 mb-16">
    <flux:input
        icon="magnifying-glass"
        placeholder="Search..."
        wire:model.live.debounce.300ms='search'
        clearable
        class="sm:max-w-[300px]!"
    />

    <div class="grid grid-cols-12 gap-6">
        @foreach ($albums as $album)
            <div class="col-span-6 sm:col-span-4 lg:col-span-3 space-y-1">
                <flux:button :href="route('album', $album)" wire:navigate variant="filled"
                    @class([
                        'p-0!' => $album->artwork_url,
                        'size-40! border hover:border-neutral-300 border-neutral-200 dark:border-neutral-600 hover:dark:border-neutral-500 shadow-xs'
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

                    <p class="text-xs truncate text-neutral-600 dark:text-neutral-400">
                        {{ $album->artist->name }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</div>
