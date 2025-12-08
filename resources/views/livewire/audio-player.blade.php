<div x-data="player" x-cloak class="fixed bottom-0 left-0 lg:left-64 right-0 z-2">
    <div class="sm:hidden mx-6 mb-4 relative backdrop-blur-sm border border-neutral-400/30 dark:border-neutral-700/30 bg-neutral-400/70 dark:bg-neutral-700/60 flex justify-between items-center rounded-md shadow-lg p-1.5">
        <div class="flex items-center gap-2">
            <div class="size-8 shrink-0 border border-neutral-100 dark:border-neutral-700 rounded shadow-md shadow-black/10 dark:shadow-black/20 flex items-center justify-center bg-neutral-100 dark:bg-neutral-800">
                <flux:icon.music-2 class="text-neutral-400 size-4" />
            </div>

            <div class="flex flex-col min-w-0 -space-y-[2px] text-neutral-100 text-[11px] truncate">
                <p class="truncate" x-text="currentTitle"></p>
                <p class="text-neutral-200 dark:text-neutral-300 truncate"
                    x-text="currentArtist">
                </p>
            </div>
        </div>

        <div class="flex items-center gap-6 pr-2">
            <flux:icon.skip-back x-on:click="playPrevious()" class="text-neutral-100 fill-neutral-100 size-3.5" />

			<button x-on:click="toggle()" class="focus:outline-none">
                <template x-if="!playing">
                    <flux:icon.play class="text-neutral-100 fill-neutral-100 size-3.5 cursor-pointer" />
                </template>

                <template x-if="playing">
                    <flux:icon.pause class="text-neutral-100 fill-neutral-100 size-3.5 cursor-pointer" />
                </template>
            </button>

            <flux:icon.skip-forward x-on:click="playNext()" class="text-neutral-100 fill-neutral-100 size-3.5" />
        </div>

        <div x-cloak class="absolute bottom-0 mx-[6.5px] left-0 right-0 h-[1.7px] bg-neutral-800/20 dark:bg-neutral-300/20 rounded overflow-hidden pointer-events-none">
            <div class="h-full bg-accent dark:bg-accent-content"
                :style="`width: ${progress}%;`">
			</div>
        </div>
    </div>

    <div class="hidden relative backdrop-blur-sm border-t border-neutral-400/30 dark:border-neutral-500/30 bg-neutral-400/70 dark:bg-neutral-700/60 sm:grid grid-cols-12 gap-4 items-center shadow-lg p-2.5">
        <div class="flex items-center col-span-3 gap-3">
            <div class="size-13 shrink-0 border border-neutral-100 dark:border-neutral-700 rounded shadow-md shadow-black/10 dark:shadow-black/20 flex items-center justify-center bg-neutral-100 dark:bg-neutral-800">
                <img x-cloak x-show="currentArtwork" :src="currentArtwork"
                    class="object-cover inset-0 rounded-[3px] w-full" />
            
                <flux:icon.music-2 x-cloak x-show="!currentArtwork" class="text-neutral-400 size-6" />
            </div>

            <div class="flex flex-col -space-y-[2px] text-[12px] truncate">
                <p class="truncate text-neutral-100"
                    x-text="currentTitle">
                </p>
                
                <p class="text-neutral-200 dark:text-neutral-300 truncate"
                    x-text="currentArtist">
                </p>
            </div>
        </div>

        <div class="flex col-span-6 items-center flex-col space-y-1.5">
            <div class="flex items-center gap-6 pr-2">
                <flux:icon.shuffle class="cursor-pointer size-4 text-neutral-100" />

                <flux:icon.skip-back x-on:click="playPrevious()" class="cursor-pointer text-neutral-100 fill-neutral-100 size-4" />

                <button x-on:click="toggle()" class="focus:outline-none cursor-pointer">
                    <div class="bg-neutral-100 flex items-center justify-center rounded-full size-7">
                        <template x-if="!playing">
                            <flux:icon.play class="size-[15px] stroke-neutral-800! fill-neutral-800!" />
                        </template>

                        <template x-if="playing">
                            <flux:icon.pause class="size-[15px] stroke-neutral-800! fill-neutral-800!" />
                        </template>
                    </div>
                </button>

                <flux:icon.skip-forward x-on:click="playNext()" class="cursor-pointer text-neutral-100 fill-neutral-100 size-4" />

                <flux:icon.repeat class="cursor-pointer size-4 text-neutral-100" />
            </div>

            <div class="flex text-neutral-100 w-full text-[11px] items-center gap-[10px]">
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

        <div class="col-span-3 items-center -space-x-2 flex mx-auto">
            <div>
                <flux:modal.trigger name="queue">
                    <flux:button variant="ghost" size="sm" class="hover:bg-transparent! cursor-pointer">
                        <flux:icon.queue-list class="size-[18px] text-neutral-100" />
                    </flux:button>
                </flux:modal.trigger>

                <flux:modal name="queue" flyout variant="floating" class="max-w-[400px]">
                    <div class="space-y-6 text-xs">
                        <div x-show="!currentSong()">
                            <p class="text-neutral-800 dark:text-neutral-100 text-sm">
                                Queue is empty
                            </p>
                        </div>

                        <div x-show="queue.length > 0 && currentPath" class="space-y-6">
                            <div class="space-y-3" x-sort="$wire.handleSort($item, $position)">
                                <div x-show="currentSong()">
                                    <flux:heading class="mb-2 text-sm">Now Playing</flux:heading>
    
                                    <div class="flex items-center gap-2.5">
                                        <div class="size-9 bg-neutral-100 dark:bg-neutral-800 rounded border border-neutral-200 dark:border-neutral-600 shadow-xs flex items-center justify-center">
                                            <img x-cloak x-show="currentArtwork" :src="currentArtwork"
                                                class="object-cover inset-0 rounded-[3px] w-full" />
                                        
                                            <flux:icon.music-2 x-cloak x-show="!currentArtwork" class="text-neutral-400 size-4" />
                                        </div>
                    
                                        <div class="flex flex-col flex-1 min-w-0">
                                            <p class="font-medium text-accent truncate" x-text="currentSong()?.title"></p>
                                            <p class="text-xs font-normal text-neutral-600 dark:text-neutral-400 truncate" x-text="currentSong()?.artist"></p>
                                        </div>
                                    </div>
                                </div>

                                <flux:heading class="mb-2 text-sm">Up Next</flux:heading>

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
                                                <flux:menu.item
                                                    variant="danger"
                                                    icon="trash"
                                                    icon:variant="micro"
                                                    class="text-xs"
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
                        <flux:icon.speaker-x-mark class="size-[18px] text-neutral-100" />
                    </template>
                
                    <template x-if="!muted">
                        <flux:icon.speaker-wave class="size-[18px] text-neutral-100" />
                    </template>
                </flux:button>
            </div>

            <flux:button variant="ghost" size="sm" class="hover:bg-transparent! cursor-pointer">
                <flux:icon.list-plus class="size-[18px] text-neutral-100" />
            </flux:button>

            <flux:button variant="ghost" size="sm" class="hover:bg-transparent! cursor-pointer">
                <flux:icon.heart class="size-[18px] text-neutral-100" />
            </flux:button>
        </div>
    </div>

	<audio id="audio-player" :src="currentPath" preload="metadata" class="hidden" />
</div>

@script
    <script>
        Alpine.data('player', () => {
            return {
                audio: null,
                playing: false,
                progress: 0,
                muted: false,
                dragging: false,
                dragProgress: null,

                queue: @js($this->queue),

                currentIndex: 0,
                currentTitle: null,
                currentArtist: null,
                currentPath: null,
                currentPlaytime: null,
                currentArtwork: null,
                currentTimeDisplay: '0:00',

                restoreTime: true,

                keys: {
                    index: 'player-current-index',
                    time: 'audio-player-current-time',
                    title: 'player-current-title',
                    artist: 'player-current-artist',
                    path: 'player-current-path',
                    playtime: 'player-current-playtime',
                    artwork: 'player-current-artwork',
                    muted: 'player-muted',
                },

                init() {
                    this.audio = document.getElementById('audio-player');
                    if (!this.audio) return;

                    this.restoreCurrentSong();
                    this.setupEventListeners();
                    this.restoreMuted();
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

                    document.addEventListener('change-song', (e) => {
                        const song = e.detail?.song;
                        if (!song) return;

                        this.queue.push(song);
                        this.$wire.addToQueue(song.id);

                        this.changeSongByIndex(this.queue.length - 1);
                    });
                },

                toggle() {
                    if (!this.audio) return;

                    if (this.playing) this.audio.pause();
                    else this.audio.play();

                    this.playing = !this.playing;
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
                    this.currentArtwork = song.artwork;

                    this.audio.src = song.path;
                    this.restoreTime = false;
                    this.audio.currentTime = 0;
                    this.audio.play();
                    this.playing = true;
                    this.clearTime();

                    this.saveCurrentSong();

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
                    this.currentArtwork = song.artwork;

                    this.audio.src = song.path;
                    this.restoreTime = false;
                    this.audio.currentTime = 0;
                    this.audio.play();
                    this.playing = true;

                    this.clearTime();
                    this.saveCurrentSong();
                },

                playNext() {
                    if (this.currentIndex + 1 >= this.queue.length) return;

                    this.progress = 0;
                    this.changeSongByIndex(this.currentIndex + 1);
                },

                playPrevious() {
                    if (this.currentIndex === 0 || this.audio.currentTime > 5) {
                        this.progress = 0;
                        this.currentTimeDisplay = '0:00';
                        this.audio.currentTime = 0;
                        this.audio.play();
                        this.playing = true;

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
                    localStorage.setItem(this.keys.artwork, this.currentArtwork);
                },

                clearTime() {
                    localStorage.removeItem(this.keys.time);
                },

                toggleMute() {
                    this.muted = !this.muted;
                    this.audio.muted = this.muted;
                    localStorage.setItem(this.keys.muted, this.muted);
                },

                seek(event) {
                    if (!this.audio?.duration) return;

                    const rect = this.$refs.progressBar.getBoundingClientRect();
                    const clickX = event.clientX - rect.left;
                    const width = rect.width;

                    let percent = clickX / width;
                    percent = Math.max(0, Math.min(1, percent));

                    if (this.dragging) {
                        this.dragProgress = percent * 100;
                    } else {
                        this.progress = percent * 100;
                        this.audio.currentTime = this.audio.duration * percent;
                        this.currentTimeDisplay = this.formatTime(this.audio.currentTime);

                        localStorage.setItem(this.keys.time, this.audio.currentTime);
                    }
                },

                get displayProgress() {
                    return this.dragging && this.dragProgress !== null ? this.dragProgress : this.progress;
                },

                startDrag(event) {
                    this.dragging = true;

                    const rect = this.$refs.progressBar.getBoundingClientRect();

                    const move = e => {
                        if (!this.dragging) return;

                        const dragX = e.clientX - rect.left;
                        const width = rect.width;

                        let percent = Math.max(0, Math.min(1, dragX / width));

                        this.dragProgress = percent * 100;
                        this.currentTimeDisplay = this.formatTime(percent * this.audio.duration);
                    };

                    const stop = e => {
                        this.dragging = false;
                        document.removeEventListener('mousemove', move);
                        document.removeEventListener('mouseup', stop);

                        this.audio.currentTime = (this.dragProgress / 100) * this.audio.duration;
                        localStorage.setItem(this.keys.time, this.audio.currentTime);

                        this.dragProgress = null;
                    };

                    document.addEventListener('mousemove', move);
                    document.addEventListener('mouseup', stop);
                },

                hasCurrent() {
                    return this.currentIndex >= 0 && this.currentIndex < this.queue.length;
                },

                currentSong() {
                    return this.hasCurrent() ? this.queue[this.currentIndex] : null;
                },

                upNext() {
                    return this.hasCurrent() ? this.queue.slice(this.currentIndex + 1) : [];
                }
            };
        });
    </script>
@endscript
