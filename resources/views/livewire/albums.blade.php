<div class="space-y-4 max-w-4xl mx-auto">
    <flux:heading size="xl">
        Albums
    </flux:heading>

    <div class="grid grid-cols-12 gap-6">
        @foreach ($albums as $album)
            <div class="col-span-6 sm:col-span-4 lg:col-span-3 space-y-1">
                <flux:button :href="route('album', $album->album)" variant="filled" class="size-40! border hover:border-neutral-300 border-neutral-200 dark:border-neutral-600 hover:dark:border-neutral-500 shadow-xs">
                    <flux:icon.disc-2 class="text-neutral-400 inset-0 size-10" />
                </flux:button>

                <div class="flex flex-col w-40 truncate">
                    <p class="text-sm truncate">
                        {{ $album->album }}
                    </p>

                    <p class="text-xs truncate text-neutral-600 dark:text-neutral-400">
                        {{ $album->artist }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</div>
