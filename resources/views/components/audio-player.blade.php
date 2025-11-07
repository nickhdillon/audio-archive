<div x-data x-init="$store.player.init()" class="fixed bottom-4 left-0 lg:left-64 right-0 z-2 px-6">
    <div class="relative backdrop-blur-sm border border-neutral-400/30 dark:border-neutral-700/30 bg-neutral-400/70 dark:bg-neutral-700/60 flex justify-between items-center rounded-md shadow-lg p-1.5">
        <div class="flex items-center gap-2">
            <div class="size-8 border border-neutral-100 dark:border-neutral-700 rounded shadow-md shadow-black/10 dark:shadow-black/20 flex items-center justify-center bg-neutral-100 dark:bg-neutral-800">
                <flux:icon.music-2 class="text-neutral-400 size-4" />
            </div>

            <div class="flex flex-col -space-y-[2px] text-neutral-100 text-[11px] truncate">
                <p class="truncate" x-text="$store.player.currentTitle"></p>
                <p class="text-neutral-200 dark:text-neutral-300 truncate"
                    x-text="$store.player.currentArtist">
                </p>
            </div>
        </div>

        <div class="flex items-center gap-6 pr-2">
            <flux:icon.skip-back class="text-neutral-100 fill-neutral-100 size-3.5" />

			<button x-on:click="$store.player.toggle()" class="focus:outline-none">
                <template x-if="!$store.player.playing">
                    <flux:icon.play class="text-neutral-100 fill-neutral-100 size-3.5 cursor-pointer" />
                </template>

                <template x-if="$store.player.playing">
                    <flux:icon.pause class="text-neutral-100 fill-neutral-100 size-3.5 cursor-pointer" />
                </template>
            </button>

            <flux:icon.skip-forward class="text-neutral-100 fill-neutral-100 size-3.5" />
        </div>

        <div x-cloak class="absolute bottom-0 mx-[6.5px] left-0 right-0 h-[1.7px] bg-neutral-800/20 dark:bg-neutral-300/20 rounded overflow-hidden pointer-events-none">
            <div class="h-full bg-accent dark:bg-accent-content transition-all duration-200"
                :style="`width: ${$store.player.progress}%;`">
			</div>
        </div>
    </div>

	<audio id="audio-player" :src="$store.player.currentUrl" preload="metadata" class="hidden" />
</div>
