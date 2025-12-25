@use('App\Enums\Repeat', 'Repeat')

<div x-data="audioPlayer" x-cloak class="fixed bottom-0 left-0 right-0 z-2">
    <template x-if="isMobile && currentPath">
        <div class="mx-6 mb-4 relative backdrop-blur-sm border border-neutral-200/70 dark:border-neutral-500/30 bg-neutral-200/70 dark:bg-neutral-700/60 flex justify-between items-center rounded-md shadow-lg p-1.5"
            x-on:click="expanded = true"
        >
            <div class="flex items-center truncate gap-2">
                <div class="size-9 shrink-0 border border-neutral-300 dark:border-neutral-700 rounded shadow-md shadow-black/10 dark:shadow-black/20 flex items-center justify-center bg-neutral-100 dark:bg-neutral-800">
                    <img
                        x-cloak
                        x-show="currentArtwork"
                        :src="currentArtwork"
                        class="object-cover inset-0 rounded-[3px] w-full"
                        loading='lazy'
                    />
                
                    <flux:icon.music-2 x-cloak x-show="!currentArtwork" class="text-neutral-400 size-4" />
                </div>

                <div class="flex flex-col -space-y-[2px] text-neutral-100 text-[11px] truncate">
                    <p class="truncate text-neutral-800 dark:text-neutral-100" x-text="currentTitle"></p>

                    <p class="text-neutral-500 dark:text-neutral-300 truncate"
                        x-text="currentArtist">
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-6 p-2">
                <flux:icon.skip-back x-on:click.stop="playPrevious()" class="cursor-pointer text-neutral-800 fill-neutral-800 dark:text-neutral-100 dark:fill-neutral-100 size-4" />

                <button x-on:click.stop="toggle()" class="focus:outline-none cursor-pointer">
                    <template x-if="!playing">
                        <flux:icon.play class="stroke-neutral-800 fill-neutral-800 dark:stroke-neutral-100 dark:fill-neutral-100 size-4 cursor-pointer" />
                    </template>

                    <template x-if="playing">
                        <flux:icon.pause class="stroke-neutral-800 fill-neutral-800 dark:stroke-neutral-100 dark:fill-neutral-100 size-4 cursor-pointer" />
                    </template>
                </button>

                <flux:icon.skip-forward x-on:click.stop="playNext()" class="text-neutral-800 fill-neutral-800 dark:text-neutral-100 dark:fill-neutral-100 size-4" />
            </div>

            <div x-cloak class="absolute bottom-0 mx-[6.5px] left-0 right-0 h-[1.7px] bg-neutral-800/20 dark:bg-neutral-300/20 rounded overflow-hidden pointer-events-none">
                <div class="h-full bg-accent dark:bg-accent-content"
                    :style="`width: ${progress}%;`">
                </div>
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div
            x-show="expanded"
            x-trap.inert.noscroll="expanded"
            x-transition:enter="transition transform duration-300 ease-out"
            x-transition:enter-start="translate-y-full"
            x-transition:enter-end="translate-y-0"
            x-transition:leave="transition transform duration-200 ease-in"
            x-transition:leave-start="translate-y-0"
            x-transition:leave-end="translate-y-full"
            class="fixed inset-0 z-[9999] overflow-y-scroll bg-neutral-100 dark:bg-neutral-900 sm:hidden flex flex-col"
        >
            <div class="p-4 flex items-center text-neutral-800 dark:text-neutral-100 gap-2 w-full">
                <div class="flex w-8 justify-center">
                    <flux:icon.chevron-down x-on:click="expanded = false" />
                </div>
            
                <div class="flex-1 text-center truncate">
                    <p x-text="currentAlbum" class="text-xs font-medium truncate"></p>
                </div>
            
                <div class="flex w-8 justify-center">
                    <flux:dropdown>
                        <flux:button variant="ghost" size="sm" class="hover:bg-transparent! -mr-1 w-2! cursor-pointer">
                            <flux:icon.ellipsis-horizontal class="text-neutral-800 dark:text-neutral-100" />
                        </flux:button>

                        <flux:menu>
                            <flux:menu.submenu icon="plus-circle" heading="Add to playlist">
                                <flux:modal.trigger name="add-playlist">
                                    <button
                                        class="flex w-full items-center gap-2 px-2.5 py-1.5 font-medium text-sm text-start rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600 group"
                                        type="button"
                                    >
                                        <flux:icon.plus class="text-neutral-400 group-hover:text-neutral-800 dark:text-neutral-400 dark:group-hover:text-neutral-100 size-4.5 stroke-2" />
                
                                        <p>New playlist</p>
                                    </button>
                                </flux:modal.trigger>

                                <flux:menu.radio.group class="flex flex-col">
                                    @foreach ($playlists as $playlist)
                                        <button
                                            class="px-2.5 py-1.5 font-medium text-sm text-start rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600"
                                            x-on:click='$wire.addToPlaylist({{ $playlist->id }}, queue[currentIndex].song_id)'
                                        >
                                            {{ $playlist->name }}
                                        </button>
                                    @endforeach
                                </flux:menu.radio.group>
                            </flux:menu.submenu>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>
    
            <div class="flex-1 flex items-start justify-center px-6 pt-4">
                <div class="w-full space-y-6 max-w-sm mx-auto">
                    <div
                        class="relative w-full aspect-square rounded-[12px] border border-neutral-300 dark:border-neutral-700 shadow-md shadow-black/10 dark:shadow-black/20 bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center"
                    >
                        <div
                            x-show="currentArtwork"
                            :style="`background-image: url(${currentArtwork});`"
                            class="absolute inset-0 rounded-[8px] bg-cover bg-center filter blur-3xl scale-105 opacity-30 dark:opacity-40"
                        ></div>

                        <img
                            x-cloak
                            x-show="currentArtwork"
                            :src="currentArtwork"
                            class="absolute inset-0 w-full h-full object-cover rounded-[10px]"
                            loading="lazy"
                        />
                
                        <flux:icon.music-2
                            x-cloak
                            x-show="!currentArtwork"
                            class="text-neutral-400 size-28"
                        />
                    </div>

                    <div class="flex flex-col text-center">
                        <p x-text="currentTitle" class="truncate"></p>
                        <p x-text="currentArtist" class="text-sm truncate text-neutral-600 dark:text-neutral-400"></p>
                    </div>

                    <div class="flex flex-col -space-y-1 text-neutral-800 dark:text-neutral-100 w-full text-[11px] items-center">    
                        <div class="relative w-full h-6 group cursor-pointer"
                            x-on:touchstart="startDrag($event)"
                            x-on:click="seek($event)"
                        >
                            <div x-ref="progressBar"
                                class="absolute top-1/2 bg-neutral-800/20 dark:bg-neutral-300/20 -translate-y-1/2 w-full h-[3px] rounded-full"
                            >
                                <div class="h-[3px] bg-accent absolute top-1/2 rounded-full -translate-y-1/2 dark:bg-accent-content"
                                    :style="`width: ${displayProgress}%;`">
                                </div>
                            </div>
        
                            <div class="absolute top-1/2 -translate-y-1/2 w-3 h-3 bg-white rounded-full shadow-lg pointer-events-none"
                                :style="`left: calc(${displayProgress}% - .4rem)`">
                            </div>
                        </div>

                        <div class="flex items-center w-full justify-between">
                            <p class="w-[30px] text-left" x-text="currentTimeDisplay"></p>
                            <p class="w-[30px] text-right" x-text="currentPlaytime"></p>
                        </div>
                    </div>

                    <div class="flex justify-center items-center gap-10">
                        <div class="relative flex items-center justify-center">
                            <flux:icon.shuffle
                                x-on:click="$wire.shuffle(queue[currentIndex].song_id)"
                                @class([
                                    'text-green-400!' => auth()->user()->shuffle,
                                    'cursor-pointer size-6 text-neutral-800 dark:text-neutral-100'
                                ])
                            />

                            @if (auth()->user()->shuffle) 
                                <div class="absolute -bottom-1.5 mr-0.5 h-1 w-1 rounded-full bg-green-400"></div>
                            @endif
                        </div>
        
                        <flux:icon.skip-back x-on:click="playPrevious()" class="cursor-pointer text-neutral-800 fill-neutral-800 dark:text-neutral-100 dark:fill-neutral-100 size-6" />
        
                        <button x-on:click="toggle()" class="focus:outline-none cursor-pointer">
                            <div class="bg-neutral-800 dark:bg-neutral-100 flex items-center justify-center rounded-full size-10">
                                <template x-if="!playing">
                                    <flux:icon.play class="size-[24px] stroke-neutral-100 fill-neutral-100 dark:stroke-neutral-800! dark:fill-neutral-800!" />
                                </template>
        
                                <template x-if="playing">
                                    <flux:icon.pause class="size-[24px] stroke-neutral-100 fill-neutral-100 dark:stroke-neutral-800! dark:fill-neutral-800!" />
                                </template>
                            </div>
                        </button>
        
                        <flux:icon.skip-forward x-on:click="playNext()" class="cursor-pointer text-neutral-800 fill-neutral-800 dark:text-neutral-100 dark:fill-neutral-100 size-6" />
        
                        <div class="relative flex items-center justify-center">
                            @if (auth()->user()->repeat !== Repeat::ONE) 
                                <flux:icon.repeat
                                    wire:click='repeat'
                                    @class([
                                        'text-green-400!' => auth()->user()->repeat === Repeat::ALL,
                                        'cursor-pointer size-6 text-neutral-800 dark:text-neutral-100'
                                    ])
                                />
                            @else
                                <flux:icon.repeat-1
                                    wire:click='repeat'
                                    class="cursor-pointer size-6 text-green-400"
                                />
                            @endif

                            @if (auth()->user()->repeat !== Repeat::OFF) 
                                <div class="absolute -bottom-1.5 h-1 w-1 rounded-full bg-green-400"></div>
                            @endif
                        </div>
                    </div>

                    <div class="flex -mx-2.5 justify-between items-center">
                        <div class="flex items-center w-full gap-2">
                            <flux:button variant="ghost" size="sm" class="hover:bg-transparent! cursor-pointer"
                                x-on:click="toggleMute()"
                            >
                                <template x-if="muted">
                                    <flux:icon.speaker-x-mark class="size-[20px] text-neutral-800 dark:text-neutral-100" />
                                </template>
                            
                                <template x-if="!muted">
                                    <flux:icon.speaker-wave class="size-[20px] text-neutral-800 dark:text-neutral-100" />
                                </template>
                            </flux:button>
                        </div>

                        <div>
                            <flux:modal.trigger name="queue-mobile">
                                <flux:button variant="ghost" size="sm" class="hover:bg-transparent! cursor-pointer">
                                    <flux:icon.queue-list class="size-[20px] text-neutral-800 dark:text-neutral-100" />
                                </flux:button>
                            </flux:modal.trigger>
        
                            <flux:modal name="queue-mobile" flyout position="bottom" class="h-[85%] w-full rounded-t-2xl">
                                <div class="space-y-6 text-xs">
                                    <flux:heading x-show="queue.length < 1" class="mb-2 text-sm">
                                        Queue is empty
                                    </flux:heading>
        
                                    <div x-show="queue.length > 0 && currentPath" class="space-y-6">
                                        <div class="space-y-3" x-sort="$wire.handleSort($item, $position)">
                                            <div x-show="currentPath">
                                                <flux:heading class="mb-2 text-sm">Now Playing</flux:heading>
        
                                                <div class="flex items-center justify-between gap-6">
                                                    <div class="flex items-center gap-2.5 truncate">
                                                        <div class="size-9 bg-neutral-100 dark:bg-neutral-800 rounded border border-neutral-300 dark:border-neutral-700 shadow-xs shadow-black/10 dark:shadow-black/20 flex items-center justify-center">
                                                            <img
                                                                x-cloak
                                                                x-show="currentArtwork"
                                                                :src="currentArtwork"
                                                                class="object-cover inset-0 rounded-[3px] w-full"
                                                                loading='lazy'
                                                            />
                                                    
                                                            <flux:icon.music-2 x-cloak x-show="!currentArtwork" class="text-neutral-400 size-4" />
                                                        </div>
        
                                                        <div class="flex flex-col flex-1 min-w-0">
                                                            <p class="font-medium text-accent truncate" x-text="currentTitle"></p>
        
                                                            <p class="text-xs font-normal text-neutral-600 dark:text-neutral-400 truncate" x-text="currentArtist"></p>
                                                        </div>
                                                    </div>
        
                                                    <div>
                                                        <flux:dropdown>
                                                            <flux:button variant="ghost" size="sm" class="hover:bg-transparent! -mr-1 w-2! cursor-pointer">
                                                                <flux:icon.ellipsis-horizontal class="text-neutral-800 dark:text-neutral-100" />
                                                            </flux:button>
        
                                                            <flux:menu>
                                                                <flux:menu.submenu icon="plus-circle" heading="Add to playlist">
                                                                    <flux:modal.trigger name="add-playlist">
                                                                        <button
                                                                            class="flex w-full items-center gap-2 px-2.5 py-1.5 font-medium text-sm text-start rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600 group"
                                                                            type="button"
                                                                        >
                                                                            <flux:icon.plus class="text-neutral-400 group-hover:text-neutral-800 dark:text-neutral-400 dark:group-hover:text-neutral-100 size-4.5 stroke-2" />
                                                    
                                                                            <p>New playlist</p>
                                                                        </button>
                                                                    </flux:modal.trigger>
        
                                                                    <flux:menu.radio.group class="flex flex-col">
                                                                        @foreach ($playlists as $playlist)
                                                                            <button
                                                                                class="px-2.5 py-1.5 font-medium text-sm text-start rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600"
                                                                                x-on:click='$wire.addToPlaylist({{ $playlist->id }}, queue[currentIndex].song_id)'
                                                                            >
                                                                                {{ $playlist->name }}
                                                                            </button>
                                                                        @endforeach
                                                                    </flux:menu.radio.group>
                                                                </flux:menu.submenu>
                                                            </flux:menu>
                                                        </flux:dropdown>
                                                    </div>
                                                </div>
                                            </div>
        
                                            <flux:heading
                                                x-cloak
                                                x-show="queue.length > 0 && currentIndex < queue.length - 1"
                                                class="mb-2 text-sm"
                                            >
                                                Up Next
                                            </flux:heading>
        
                                            @foreach ($this->queue() as $song)
                                                <div class="flex items-center gap-6"
                                                    x-show="{{ $loop->index }} > currentIndex"
                                                    x-sort:item="{{ $song['id'] }}"
                                                    wire:key='{{ $song['id'] }}'
                                                >
                                                    <span class="cursor-move" x-sort:handle>
                                                        <flux:icon.text-align-justify class="cursor-move size-4 -mr-3 dark:text-neutral-100" />
                                                    </span>
        
                                                    <button
                                                        x-on:click="changeSongByIndex({{ $loop->index }})"
                                                        x-sort:ignore
                                                        class="flex flex-1 min-w-0 text-left cursor-pointer items-center group gap-2.5"
                                                    >
                                                        <div class="size-9 bg-neutral-100 dark:bg-neutral-800 rounded border border-neutral-200 dark:border-neutral-600 shadow-xs flex items-center justify-center">
                                                            @if ($song['artwork'])
                                                                <img src="{{ $song['artwork'] }}" class="object-cover inset-0 rounded-[3px] w-full" />
                                                            @else
                                                                <flux:icon.music-2 class="text-neutral-400 size-4" />
                                                            @endif
                                                        </div>
                            
                                                        <div class="flex flex-col flex-1 min-w-0">
                                                            <p class="duration-200 ease-in-out truncate group-hover:text-neutral-600 dark:group-hover:text-neutral-400">
                                                                {{ $song['title'] }}
                                                            </p>
        
                                                            <p class="text-xs font-normal text-neutral-600 dark:text-neutral-400 truncate">
                                                                {{ $song['artist'] }}
                                                            </p>
                                                        </div>
                                                    </button>
        
                                                    <flux:dropdown>
                                                        <flux:button variant="ghost" size="sm" class="hover:bg-transparent! -mr-1 w-2! cursor-pointer">
                                                            <flux:icon.ellipsis-horizontal class="text-neutral-800 dark:text-neutral-100" />
                                                        </flux:button>
                            
                                                        <flux:menu>
                                                            <flux:menu.submenu icon="plus-circle" heading="Add to playlist">
                                                                <flux:modal.trigger name="add-playlist">
                                                                    <button
                                                                        class="flex w-full items-center gap-2 px-2.5 py-1.5 font-medium text-sm text-start rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600 group"
                                                                        type="button"
                                                                    >
                                                                        <flux:icon.plus class="text-neutral-400 group-hover:text-neutral-800 dark:text-neutral-400 dark:group-hover:text-neutral-100 size-4.5 stroke-2" />
                                                
                                                                        <p>New playlist</p>
                                                                    </button>
                                                                </flux:modal.trigger>
        
                                                                <flux:menu.radio.group class="flex flex-col">
                                                                    @foreach ($playlists as $playlist)
                                                                        <button
                                                                            class="px-2.5 py-1.5 font-medium text-sm text-start rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600"
                                                                            x-on:click='$wire.addToPlaylist({{ $playlist->id }}, queue[currentIndex].song_id)'
                                                                        >
                                                                            {{ $playlist->name }}
                                                                        </button>
                                                                    @endforeach
                                                                </flux:menu.radio.group>
                                                            </flux:menu.submenu>
        
                                                            <flux:menu.item
                                                                variant="danger"
                                                                icon="trash"
                                                                x-on:click="
                                                                    $flux.modal('queue').close();
                                                                    $dispatch('remove-from-queue', { id: {{ $song['id'] }} })
                                                                "
                                                            >
                                                                Remove from queue
                                                            </flux:menu.item>
                                                        </flux:menu>
                                                    </flux:dropdown>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </flux:modal>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template x-if="!isMobile && currentPath">
        <div class="max-w-6xl w-full mx-auto justify-center">
            <div class="relative rounded-md mb-4 mx-4 backdrop-blur-sm border border-neutral-200/70 dark:border-neutral-500/30 bg-neutral-200/70 dark:bg-neutral-700/60 grid grid-cols-12 gap-4 items-center shadow-lg p-2.5">
                <div class="flex items-center col-span-3 gap-3">
                    <div class="size-13 shrink-0 border border-neutral-300 dark:border-neutral-700 rounded shadow-md shadow-black/10 dark:shadow-black/20 flex items-center justify-center bg-neutral-100 dark:bg-neutral-800">
                        <img
                            x-cloak
                            x-show="currentArtwork"
                            :src="currentArtwork"
                            class="object-cover inset-0 rounded-[3px] w-full"
                            loading='lazy'
                        />
                    
                        <flux:icon.music-2 x-cloak x-show="!currentArtwork" class="text-neutral-400 size-6" />
                    </div>

                    <div class="flex flex-col -space-y-[2px] text-[12px] truncate">
                        <p class="truncate text-neutral-800 dark:text-neutral-100"
                            x-text="currentTitle">
                        </p>
                        
                        <p class="text-neutral-500 dark:text-neutral-300 truncate"
                            x-text="currentArtist">
                        </p>
                    </div>
                </div>

                <div class="flex col-span-6 items-center flex-col space-y-1.5">
                    <div class="flex items-center gap-6 pr-2">
                        <div class="relative flex items-center justify-center">
                            <flux:icon.shuffle
                                x-on:click="$wire.shuffle(queue[currentIndex].song_id)"
                                @class([
                                    'text-green-400!' => auth()->user()->shuffle,
                                    'cursor-pointer size-4 text-neutral-800 dark:text-neutral-100'
                                ])
                            />

                            @if (auth()->user()->shuffle) 
                                <div class="absolute -bottom-1 size-[2.5px] mr-0.5 rounded-full bg-green-400"></div>
                            @endif
                        </div>

                        <flux:icon.skip-back x-on:click="playPrevious()" class="cursor-pointer text-neutral-800 fill-neutral-800 dark:text-neutral-100 dark:fill-neutral-100 size-4" />

                        <button x-on:click="toggle()" class="focus:outline-none cursor-pointer">
                            <div class="bg-neutral-800 dark:bg-neutral-100 flex items-center justify-center rounded-full size-7">
                                <template x-if="!playing">
                                    <flux:icon.play class="size-[15px] stroke-neutral-100 fill-neutral-100 dark:stroke-neutral-800! dark:fill-neutral-800!" />
                                </template>

                                <template x-if="playing">
                                    <flux:icon.pause class="size-[15px] stroke-neutral-100 fill-neutral-100 dark:stroke-neutral-800! dark:fill-neutral-800!" />
                                </template>
                            </div>
                        </button>

                        <flux:icon.skip-forward x-on:click="playNext()" class="cursor-pointer text-neutral-800 fill-neutral-800 dark:text-neutral-100 dark:fill-neutral-100 size-4" />

                        <div class="relative flex items-center justify-center">
                            @if (auth()->user()->repeat !== Repeat::ONE) 
                                <flux:icon.repeat
                                    wire:click='repeat'
                                    @class([
                                        'text-green-400!' => auth()->user()->repeat === Repeat::ALL,
                                        'cursor-pointer size-4 text-neutral-800 dark:text-neutral-100'
                                    ])
                                />
                            @else
                                <flux:icon.repeat-1
                                    wire:click='repeat'
                                    class="cursor-pointer size-4 text-green-400"
                                />
                            @endif

                            @if (auth()->user()->repeat !== Repeat::OFF) 
                                <div class="absolute -bottom-1 size-[2.5px] rounded-full bg-green-400"></div>
                            @endif
                        </div>
                    </div>

                    <div class="flex text-neutral-800 dark:text-neutral-100 w-full text-[11px] items-center gap-[10px]">
                        <p class="w-[30px] text-right" x-text="currentTimeDisplay"></p>
                        
                        <div class="relative w-full h-6 group cursor-pointer"
                            x-on:mousedown="startDrag($event)"
                            x-on:click="seek($event)"
                        >
                            <div x-ref="progressBar"
                                class="absolute top-1/2 bg-neutral-800/20 dark:bg-neutral-300/20 -translate-y-1/2 w-full h-[3px] rounded-full"
                            >
                                <div class="h-[3px] bg-accent absolute top-1/2 rounded-full -translate-y-1/2 dark:bg-accent-content"
                                    :style="`width: ${displayProgress}%;`">
                                </div>
                            </div>

                            <div class="absolute top-1/2 opacity-0 group-hover:opacity-100 -translate-y-1/2 w-3 h-3 bg-white rounded-full shadow-lg pointer-events-none"
                                :style="`left: calc(${displayProgress}% - .4rem)`">
                            </div>
                        </div>

                        <p class="w-[30px] text-left" x-text="currentPlaytime"></p>
                    </div>
                </div>

                <div class="col-span-3 items-center -space-x-2 flex ml-auto">
                    <div>
                        <flux:modal.trigger name="queue-desktop">
                            <flux:button variant="ghost" size="sm" class="hover:bg-transparent! cursor-pointer">
                                <flux:icon.queue-list class="size-[18px] text-neutral-800 dark:text-neutral-100" />
                            </flux:button>
                        </flux:modal.trigger>

                        <flux:modal name="queue-desktop" flyout variant="floating" class="max-w-[400px]">
                            <div class="space-y-6 text-xs">
                                <flux:heading x-show="queue.length < 1" class="mb-2 text-sm">
                                    Queue is empty
                                </flux:heading>

                                <div x-show="queue.length > 0 && currentPath" class="space-y-6">
                                    <div class="space-y-3" x-sort="$wire.handleSort($item, $position)">
                                        <div x-show="currentPath">
                                            <flux:heading class="mb-2 text-sm">Now Playing</flux:heading>

                                            <div class="flex items-center justify-between gap-6">
                                                <div class="flex items-center gap-2.5 truncate">
                                                    <div class="size-9 bg-neutral-100 dark:bg-neutral-800 rounded border border-neutral-300 dark:border-neutral-700 shadow-xs shadow-black/10 dark:shadow-black/20 flex items-center justify-center">
                                                        <img
                                                            x-cloak
                                                            x-show="currentArtwork"
                                                            :src="currentArtwork"
                                                            class="object-cover inset-0 rounded-[3px] w-full"
                                                            loading='lazy'
                                                        />
                                                
                                                        <flux:icon.music-2 x-cloak x-show="!currentArtwork" class="text-neutral-400 size-4" />
                                                    </div>

                                                    <div class="flex flex-col flex-1 min-w-0">
                                                        <p class="font-medium text-accent truncate" x-text="currentTitle"></p>

                                                        <p class="text-xs font-normal text-neutral-600 dark:text-neutral-400 truncate" x-text="currentArtist"></p>
                                                    </div>
                                                </div>

                                                <div>
                                                    <flux:dropdown>
                                                        <flux:button variant="ghost" size="sm" class="hover:bg-transparent! -mr-1 w-2! cursor-pointer">
                                                            <flux:icon.ellipsis-horizontal class="text-neutral-800 dark:text-neutral-100" />
                                                        </flux:button>

                                                        <flux:menu>
                                                            <flux:menu.submenu icon="plus-circle" heading="Add to playlist">
                                                                <flux:modal.trigger name="add-playlist">
                                                                    <button
                                                                        class="flex w-full items-center gap-2 px-2.5 py-1.5 font-medium text-sm text-start rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600 group"
                                                                        type="button"
                                                                    >
                                                                        <flux:icon.plus class="text-neutral-400 group-hover:text-neutral-800 dark:text-neutral-400 dark:group-hover:text-neutral-100 size-4.5 stroke-2" />
                                                
                                                                        <p>New playlist</p>
                                                                    </button>
                                                                </flux:modal.trigger>

                                                                <flux:menu.radio.group class="flex flex-col">
                                                                    @foreach ($playlists as $playlist)
                                                                        <button
                                                                            class="px-2.5 py-1.5 font-medium text-sm text-start rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600"
                                                                            x-on:click='$wire.addToPlaylist({{ $playlist->id }}, queue[currentIndex].song_id)'
                                                                        >
                                                                            {{ $playlist->name }}
                                                                        </button>
                                                                    @endforeach
                                                                </flux:menu.radio.group>
                                                            </flux:menu.submenu>
                                                        </flux:menu>
                                                    </flux:dropdown>
                                                </div>
                                            </div>
                                        </div>

                                        <flux:heading
                                            x-cloak
                                            x-show="queue.length > 0 && currentIndex < queue.length - 1"
                                            class="mb-2 text-sm"
                                        >
                                            Up Next
                                        </flux:heading>

                                        @foreach ($this->queue() as $song)
                                            <div class="flex items-center gap-6"
                                                x-show="{{ $loop->index }} > currentIndex"
                                                x-sort:item="{{ $song['id'] }}"
                                                wire:key='{{ $song['id'] }}'
                                            >
                                                <span class="cursor-move" x-sort:handle>
                                                    <flux:icon.text-align-justify class="cursor-move size-4 -mr-3 dark:text-neutral-100" />
                                                </span>

                                                <button
                                                    x-on:click="changeSongByIndex({{ $loop->index }})"
                                                    x-sort:ignore
                                                    class="flex flex-1 min-w-0 text-left cursor-pointer items-center group gap-2.5"
                                                >
                                                    <div class="size-9 bg-neutral-100 dark:bg-neutral-800 rounded border border-neutral-300 dark:border-neutral-700 shadow-xs shadow-black/10 dark:shadow-black/20 flex items-center justify-center">
                                                        @if ($song['artwork'])
                                                            <img src="{{ $song['artwork'] }}" class="object-cover inset-0 rounded-[3px] w-full" />
                                                        @else
                                                            <flux:icon.music-2 class="text-neutral-400 size-4" />
                                                        @endif
                                                    </div>
                        
                                                    <div class="flex flex-col flex-1 min-w-0">
                                                        <p class="duration-200 ease-in-out truncate group-hover:text-neutral-600 dark:group-hover:text-neutral-400">
                                                            {{ $song['title'] }}
                                                        </p>

                                                        <p class="text-xs font-normal text-neutral-600 dark:text-neutral-400 truncate">
                                                            {{ $song['artist'] }}
                                                        </p>
                                                    </div>
                                                </button>

                                                <flux:dropdown>
                                                    <flux:button variant="ghost" size="sm" class="hover:bg-transparent! -mr-1 w-2! cursor-pointer">
                                                        <flux:icon.ellipsis-horizontal class="text-neutral-800 dark:text-neutral-100" />
                                                    </flux:button>
                        
                                                    <flux:menu>
                                                        <flux:menu.submenu icon="plus-circle" heading="Add to playlist">
                                                            <flux:modal.trigger name="add-playlist">
                                                                <button
                                                                    class="flex w-full items-center gap-2 px-2.5 py-1.5 font-medium text-sm text-start rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600 group"
                                                                    type="button"
                                                                >
                                                                    <flux:icon.plus class="text-neutral-400 group-hover:text-neutral-800 dark:text-neutral-400 dark:group-hover:text-neutral-100 size-4.5 stroke-2" />
                                            
                                                                    <p>New playlist</p>
                                                                </button>
                                                            </flux:modal.trigger>

                                                            <flux:menu.radio.group class="flex flex-col">
                                                                @foreach ($playlists as $playlist)
                                                                    <button
                                                                        class="px-2.5 py-1.5 font-medium text-sm text-start rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600"
                                                                        x-on:click='$wire.addToPlaylist({{ $playlist->id }}, queue[currentIndex].song_id)'
                                                                    >
                                                                        {{ $playlist->name }}
                                                                    </button>
                                                                @endforeach
                                                            </flux:menu.radio.group>
                                                        </flux:menu.submenu>

                                                        <flux:menu.item
                                                            variant="danger"
                                                            icon="trash"
                                                            x-on:click="
                                                                $flux.modal('queue').close();
                                                                $dispatch('remove-from-queue', { id: {{ $song['id'] }} })
                                                            "
                                                        >
                                                            Remove from queue
                                                        </flux:menu.item>
                                                    </flux:menu>
                                                </flux:dropdown>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </flux:modal>
                    </div>

                    <div class="flex items-center w-full gap-2">
                        <flux:button variant="ghost" size="sm" class="hover:bg-transparent! cursor-pointer"
                            x-on:click="toggleMute()"
                        >
                            <template x-if="muted">
                                <flux:icon.speaker-x-mark class="size-[18px] text-neutral-800 dark:text-neutral-100" />
                            </template>
                        
                            <template x-if="!muted">
                                <flux:icon.speaker-wave class="size-[18px] text-neutral-800 dark:text-neutral-100" />
                            </template>
                        </flux:button>
                    </div>

                    <flux:dropdown>
                        <flux:button variant="ghost" size="sm" class="hover:bg-transparent! cursor-pointer">
                            <flux:icon.list-plus class="size-[18px] text-neutral-800 dark:text-neutral-100" />
                        </flux:button>

                        <flux:menu>
                            <flux:menu.group heading="Add to playlist">
                                <flux:modal.trigger name="add-playlist">
                                    <button
                                        class="flex w-full items-center gap-2 px-2.5 py-1.5 font-medium text-sm text-start rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600 group"
                                        type="button"
                                    >
                                        <flux:icon.plus class="text-neutral-400 group-hover:text-neutral-800 dark:text-neutral-400 dark:group-hover:text-neutral-100 size-4.5 stroke-2" />
                                
                                        <p>New playlist</p>
                                    </button>
                                </flux:modal.trigger>
                            </flux:menu.group>

                            <flux:menu.group>
                                <flux:menu.radio.group class="flex flex-col">
                                    @foreach ($playlists as $playlist) 
                                        <button
                                            class="px-2.5 py-1.5 font-medium text-sm text-start rounded-md hover:bg-neutral-50 dark:hover:bg-neutral-600"
                                            x-on:click='$wire.addToPlaylist({{ $playlist->id }}, queue[currentIndex].song_id)'
                                        >
                                            {{ $playlist->name }}
                                        </button>
                                    @endforeach
                                </flux:menu.radio.group>
                            </flux:menu.group>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>
        </div>
    </template>

	<audio id="audio-player" crossorigin="anonymous" :src="currentPath" preload="metadata" class="hidden" />
</div>

@script
    <script>
        Alpine.data('audioPlayer', () => {
            return {
                userId: {{ auth()->id() }},
                expanded: false,
                audio: null,
                playing: false,
                progress: 0,
                muted: false,
                dragging: false,
                dragProgress: null,
                isMobile: window.innerWidth < 640,
                repeat: @js(auth()->user()->repeat),

                queue: @js($this->queue),

                currentIndex: 0,
                currentTitle: null,
                currentArtist: null,
                currentPath: null,
                currentPlaytime: null,
                currentAlbum: null,
                currentArtwork: null,
                currentTimeDisplay: '0:00',

                restoreTime: true,

                init() {
                    this.audio = document.getElementById('audio-player');
                    if (!this.audio) return;

                    if (!this.queue.length) this.resetKeysAndLocalStorage();

                    this.restoreCurrentSong();
                    this.setupEventListeners();
                    this.restoreMuted();
                },

                get keys() {
                    return {
                        index: `player-current-index:${this.userId}`,
                        time: `player-current-time:${this.userId}`,
                        title: `player-current-title:${this.userId}`,
                        artist: `player-current-artist:${this.userId}`,
                        path: `player-current-path:${this.userId}`,
                        playtime: `player-current-playtime:${this.userId}`,
                        album: `player-current-album:${this.userId}`,
                        artwork: `player-current-artwork:${this.userId}`,
                        muted: `player-muted:${this.userId}`,
                    };
                },

                restoreCurrentSong() {
                    const storedIndex = localStorage.getItem(this.keys.index);
                    const storedPath = localStorage.getItem(this.keys.path);

                    if (storedPath) {
                        this.currentIndex = storedIndex !== null ? Number(storedIndex) : 0;
                        this.currentTitle = localStorage.getItem(this.keys.title);
                        this.currentArtist = localStorage.getItem(this.keys.artist);
                        this.currentPath = storedPath;
                        this.currentPlaytime = localStorage.getItem(this.keys.playtime);
                        this.currentAlbum = localStorage.getItem(this.keys.album);
                        this.currentArtwork = localStorage.getItem(this.keys.artwork);
                        this.audio.src = storedPath;
                    }
                },

                restoreMuted() {
                    const storedMuted = localStorage.getItem(this.keys.muted);

                    if (storedMuted !== null) {
                        this.muted = storedMuted === 'true';
                        this.audio.muted = this.muted;
                    }
                },

                resetKeysAndLocalStorage() {
                    localStorage.removeItem(this.keys.index);
                    localStorage.removeItem(this.keys.title);
                    localStorage.removeItem(this.keys.artist);
                    localStorage.removeItem(this.keys.path);
                    localStorage.removeItem(this.keys.playtime);
                    localStorage.removeItem(this.keys.album);
                    localStorage.removeItem(this.keys.artwork);
                    localStorage.removeItem(this.keys.time);
                    localStorage.removeItem(this.keys.muted);

                    this.currentIndex = 0;
                    this.currentTitle = null;
                    this.currentArtist = null;
                    this.currentPath = null;
                    this.currentPlaytime = null;
                    this.currentAlbum = null;
                    this.currentArtwork = null;
                    this.progress = 0;
                    this.currentTimeDisplay = '0:00';
                    this.restoreTime = true;
                    this.playing = false;
                    this.dragging = false;
                    this.dragProgress = null;
                },

                formatTime(seconds) {
                    if (!seconds || isNaN(seconds)) return '0:00';
                    const m = Math.floor(seconds / 60);
                    const s = Math.floor(seconds % 60).toString().padStart(2, '0');
                    return `${m}:${s}`;
                },

                setupEventListeners() {
                    this.audio.addEventListener('play', () => this.playing = true);
                    this.audio.addEventListener('pause', () => this.playing = false);

                    this.audio.addEventListener('volumechange', () => {
                        this.muted = this.audio.muted;
                    });

                    this.audio.addEventListener('loadedmetadata', () => {
                        if (!this.restoreTime) return;

                        const stored = localStorage.getItem(this.keys.time);

                        if (stored) {
                            const seconds = parseFloat(stored);

                            if (!isNaN(seconds) && seconds < this.audio.duration) {
                                this.audio.currentTime = seconds;
                            }
                        }
                    });

                    this.audio.ontimeupdate = () => {
                        if (!this.audio?.duration) return;

                        this.progress = (this.audio.currentTime / this.audio.duration) * 100;
                        this.currentTimeDisplay = this.formatTime(this.audio.currentTime);

                        localStorage.setItem(this.keys.time, this.audio.currentTime);
                    };

                    this.audio.onended = () => {
                        this.playing = false;
                        this.clearTime();
                        this.playNext();
                    };

                    document.addEventListener('queue-updated', (e) => {
                        const previousLength = this.queue.length;
                        const currentSongId = this.queue[this.currentIndex]?.id;

                        this.queue = e.detail.queue;

                        const newIndex = this.queue.findIndex(s => s.id === currentSongId);

                        if (newIndex !== -1) this.currentIndex = newIndex;

                        if (previousLength === 0 && this.queue.length === 1) {
                            this.currentIndex = 0;
                            this.changeSongByIndex(0);

                            return;
                        }
                    });

                    document.addEventListener('replace-queue', (e) => {
                        this.queue = e.detail.queue;

                        this.resetKeysAndLocalStorage();

                        if (this.queue.length > 0) {
                            this.changeSongByIndex(0);
                        }
                    });

                    document.addEventListener('change-song', (e) => {
                        const song = e.detail?.song;
                        if (!song) return;

                        this.queue.push(song);
                        this.$wire.addToQueue(song.id);

                        this.changeSongByIndex(this.queue.length - 1);
                    });

                    document.addEventListener('repeat-changed', (e) => {
                        this.repeat = e.detail.value;
                    });

                    window.addEventListener('resize', () => this.isMobile = window.innerWidth < 640);
                },

                play({ src = null, resetTime = false } = {}) {
                    if (!this.audio) return;

                    if (src && this.audio.src !== src) {
                        this.audio.src = src;
                    }

                    if (resetTime) {
                        this.audio.currentTime = 0;
                    }

                    this.audio.play().catch(() => {});
                    this.playing = true;
                },

                toggle() {
                    if (!this.audio) return;

                    if (this.audio.paused) {
                        this.audio.play();
                        this.playing = true;
                    } else {
                        this.audio.pause();
                        this.playing = false;
                    }
                },

                changeSong(song) {
                    if (!this.audio) return;
                    this.progress = 0;

                    if (this.currentPath === song.path) {
                        this.toggle();

                        return;
                    }

                    this.currentTitle = song.title;
                    this.currentArtist = song.artist;
                    this.currentPath = song.path;
                    this.currentPlaytime = song.playtime;
                    this.currentAlbum = song.album;
                    this.currentArtwork = song.artwork;

                    this.clearTime();
                    this.saveCurrentSong();

                    this.play({ src: song.path, resetTime: true });

                    this.$wire.addToQueue(song.id);
                },

                changeSongByIndex(index) {
                    const song = this.queue[index];
                    if (!song) return;

                    this.progress = 0;
                    this.currentTimeDisplay = '0:00';

                    this.currentIndex = index;
                    this.currentTitle = song.title;
                    this.currentArtist = song.artist;
                    this.currentPath = song.path;
                    this.currentPlaytime = song.playtime;
                    this.currentAlbum = song.album;
                    this.currentArtwork = song.artwork;

                    this.clearTime();
                    this.saveCurrentSong();

                    this.play({ src: song.path, resetTime: true });
                },

                playNext() {
                    if (this.repeat === '{{ Repeat::ONE }}') {
                        this.play();
                        this.progress = 0;

                        return;
                    }

                    if (this.currentIndex + 1 >= this.queue.length) {
                        if (this.repeat === '{{ Repeat::ALL }}') {
                            this.progress = 0;
                            this.changeSongByIndex(0);
                        }

                        return;
                    }

                    this.progress = 0;
                    this.changeSongByIndex(this.currentIndex + 1);
                },

                playPrevious() {
                    if (this.currentIndex === 0 || this.audio.currentTime > 5) {
                        this.progress = 0;
                        this.currentTimeDisplay = '0:00';
                        this.play({ resetTime: true });

                        return;
                    }

                    this.changeSongByIndex(this.currentIndex - 1);
                },

                saveCurrentSong() {
                    localStorage.setItem(this.keys.index, this.currentIndex);
                    localStorage.setItem(this.keys.title, this.currentTitle);
                    localStorage.setItem(this.keys.artist, this.currentArtist);
                    localStorage.setItem(this.keys.path, this.currentPath);
                    localStorage.setItem(this.keys.playtime, this.currentPlaytime);
                    localStorage.setItem(this.keys.album, this.currentAlbum);
                    localStorage.setItem(this.keys.artwork, this.currentArtwork);
                },

                clearTime() {
                    localStorage.removeItem(this.keys.time);
                },

                toggleMute() {
                    if (!this.audio) return;

                    this.muted = !this.muted;

                    if (this.audio.readyState >= 2) {
                        this.audio.muted = this.muted;
                    } else {
                        this.audio.addEventListener('loadedmetadata', () => {
                            this.audio.muted = this.muted;
                        }, { once: true });
                    }

                    localStorage.setItem(this.keys.muted, this.muted);
                },
                
                seek(event) {
                    if (!this.audio?.duration) return;

                    const rect = this.$refs.progressBar.getBoundingClientRect();
                    const clickX = event.clientX - rect.left;
                    const width = rect.width;

                    let percent = clickX / width;
                    percent = Math.max(0, Math.min(1, percent));

                    this.audio.currentTime = this.audio.duration * percent;
                    this.progress = percent * 100;

                    if (!this.isMobile) {
                        this.currentTimeDisplay = this.formatTime(this.audio.currentTime);
                    }

                    localStorage.setItem(this.keys.time, this.audio.currentTime);
                },

                get displayProgress() {
                    return this.dragging && this.dragProgress !== null ? this.dragProgress : this.progress;
                },

                startDrag(event) {
                    this.dragging = true;

                    const rect = this.$refs.progressBar.getBoundingClientRect();

                    const getClientX = e => e.touches ? e.touches[0].clientX : e.clientX;

                    const move = e => {
                        if (!this.dragging) return;

                        const dragX = getClientX(e) - rect.left;
                        const width = rect.width;

                        let percent = Math.max(0, Math.min(1, dragX / width));

                        this.dragProgress = percent * 100;
                        this.currentTimeDisplay = this.formatTime(percent * this.audio.duration);
                    };

                    const stop = e => {
                        if (!this.dragging) return;

                        const finalTime = (this.dragProgress / 100) * this.audio.duration;
                        this.audio.currentTime = finalTime;
                        this.progress = this.dragProgress;
                        this.currentTimeDisplay = this.formatTime(finalTime);
                        localStorage.setItem(this.keys.time, finalTime);

                        this.dragging = false;
                        this.dragProgress = null;

                        document.removeEventListener('mousemove', move);
                        document.removeEventListener('mouseup', stop);
                        document.removeEventListener('touchmove', move);
                        document.removeEventListener('touchend', stop);
                    };

                    document.addEventListener('mousemove', move);
                    document.addEventListener('mouseup', stop);
                    document.addEventListener('touchmove', move, { passive: false });
                    document.addEventListener('touchend', stop);
                },

                hasCurrent() {
                    return this.currentIndex >= 0 && this.currentIndex < this.queue.length;
                },

                currentSong() {
                    return this.hasCurrent() ? this.queue[this.currentIndex] : null;
                },

                upNext() {
                    return this.hasCurrent() ? this.queue.slice(this.currentIndex + 1) : [];
                },

                checkScreen() {
                    this.isMobile = window.innerWidth < 640;
                }
            };
        });
    </script>
@endscript