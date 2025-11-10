<div x-data x-cloak x-show="$store.player.queue.length" x-transition class="fixed bottom-0 left-0 lg:left-64 right-0 z-2">
    <div class="sm:hidden mx-6 mb-4 relative backdrop-blur-sm border border-neutral-400/30 dark:border-neutral-700/30 bg-neutral-400/70 dark:bg-neutral-700/60 flex justify-between items-center rounded-md shadow-lg p-1.5">
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
            <flux:icon.skip-back x-on:click="$store.player.playPrevious()" class="text-neutral-100 fill-neutral-100 size-3.5" />

			<button x-on:click="$store.player.toggle()" class="focus:outline-none">
                <template x-if="!$store.player.playing">
                    <flux:icon.play class="text-neutral-100 fill-neutral-100 size-3.5 cursor-pointer" />
                </template>

                <template x-if="$store.player.playing">
                    <flux:icon.pause class="text-neutral-100 fill-neutral-100 size-3.5 cursor-pointer" />
                </template>
            </button>

            <flux:icon.skip-forward x-on:click="$store.player.playNext()" class="text-neutral-100 fill-neutral-100 size-3.5" />
        </div>

        <div x-cloak class="absolute bottom-0 mx-[6.5px] left-0 right-0 h-[1.7px] bg-neutral-800/20 dark:bg-neutral-300/20 rounded overflow-hidden pointer-events-none">
            <div class="h-full bg-accent dark:bg-accent-content transition-all duration-200"
                :style="`width: ${$store.player.progress}%;`">
			</div>
        </div>
    </div>

    <div class="hidden relative backdrop-blur-sm border-t border-neutral-400/30 dark:border-neutral-500/30 bg-neutral-400/70 dark:bg-neutral-700/60 sm:grid grid-cols-12 gap-4 items-center shadow-lg py-2.5 px-3">
        <div class="flex items-center col-span-3 gap-3">
            <div class="size-13 border border-neutral-100 dark:border-neutral-700 rounded shadow-md shadow-black/10 dark:shadow-black/20 flex items-center justify-center bg-neutral-100 dark:bg-neutral-800">
                <flux:icon.music-2 class="text-neutral-400 size-6" />
            </div>

            <div class="flex flex-col -space-y-[2px] text-[12px] truncate">
                <p class="truncate text-neutral-100"
                    x-text="$store.player.currentTitle">
                </p>
                
                <p class="text-neutral-200 dark:text-neutral-300 truncate"
                    x-text="$store.player.currentArtist">
                </p>
            </div>
        </div>

        <div class="flex col-span-6 items-center flex-col space-y-1.5">
            <div class="flex items-center gap-6 pr-2">
                <flux:icon.shuffle class="cursor-pointer size-4 text-neutral-100" />

                <flux:icon.skip-back x-on:click="$store.player.playPrevious()" class="cursor-pointer text-neutral-100 fill-neutral-100 size-4" />

                <button x-on:click="$store.player.toggle()" class="focus:outline-none cursor-pointer">
                    <div class="bg-neutral-100 flex items-center justify-center rounded-full size-7">
                        <template x-if="!$store.player.playing">
                            <flux:icon.play class="size-[15px] stroke-neutral-800! fill-neutral-800!" />
                        </template>

                        <template x-if="$store.player.playing">
                            <flux:icon.pause class="size-[15px] stroke-neutral-800! fill-neutral-800!" />
                        </template>
                    </div>
                </button>

                <flux:icon.skip-forward x-on:click="$store.player.playNext()" class="cursor-pointer text-neutral-100 fill-neutral-100 size-4" />

                <flux:icon.repeat class="cursor-pointer size-4 text-neutral-100" />
            </div>

            <div class="flex text-neutral-100 w-full text-[11px] items-center gap-2">
                <p x-text="$store.player.currentTimeDisplay"></p>

                <div class="h-[3px] w-full bg-neutral-800/20 dark:bg-neutral-300/20 rounded overflow-hidden pointer-events-none">
                    <div class="h-full bg-accent dark:bg-accent-content transition-all duration-200"
                        :style="`width: ${$store.player.progress}%;`">
                    </div>
                </div>

                <p x-text="$store.player.currentLength"></p>
            </div>
        </div>

        <div class="col-span-3 items-center -space-x-2 flex mx-auto">
            <flux:dropdown position="top" align="start">
                <flux:button variant="ghost" size="sm" class="hover:bg-transparent! cursor-pointer">
                    <flux:icon.queue-list class="size-[18px] text-neutral-100" />
                </flux:button>

                <flux:popover class="w-80! max-h-[500px]">
                    <ul class="text-xs space-y-3">
                        <button x-on:click="$store.player.clearQueue()" class="-mt-1 text-sm cursor-pointer duration-200 ease-in-out hover:text-neutral-600 dark:hover:text-neutral-400">
                            Clear Queue
                        </button>
                        
                        <div class="space-y-3" x-sort="$store.player.updateQueueOrder.bind($store.player)">
                            <template x-for="(song, index) in $store.player.queue" :key="song.title">
                                <div x-sort:item="song.title" x-show="index >= $store.player.currentIndex"
                                    class="flex items-center gap-6"
                                >
                                    <span x-text="song.position"></span>
                                    <flux:icon.text-align-justify x-sort:handle class="cursor-move size-4 -mr-3 text-neutral-100" />

                                    <button x-on:click="$store.player.changeSongByIndex(index)"
                                        class="flex flex-1 min-w-0 text-left cursor-pointer items-center group gap-2.5"
                                        :class="index === $store.player.currentIndex ? 'text-accent font-medium' : ''"
                                    >
                                        <div class="size-9 bg-neutral-100 dark:bg-neutral-800 rounded border border-neutral-200 dark:border-neutral-600 shadow-xs flex items-center justify-center">
                                            <flux:icon.music-2 class="text-neutral-400 size-4" />
                                        </div>
                        
                                        <div class="flex flex-col flex-1 min-w-0">
                                            <p class="duration-200 ease-in-out truncate"
                                                :class="index !== $store.player.currentIndex ? 'group-hover:text-neutral-600 dark:group-hover:text-neutral-400' : ''"
                                                x-text="song.title">
                                            </p>
                        
                                            <p class="text-xs font-normal text-neutral-600 dark:text-neutral-400 truncate"
                                                x-text="song.artist">
                                            </p>
                                        </div>
                                    </button>
                        
                                    <flux:icon.ellipsis-horizontal class="cursor-pointer size-4 text-neutral-800 dark:text-neutral-100" />
                                </div>
                            </template>
                        </div>
                    </ul>
                </flux:popover>
            </flux:dropdown>

            <div class="flex items-center w-full gap-2">
                <flux:button variant="ghost" size="sm" class="hover:bg-transparent! cursor-pointer"
                    x-on:click="$store.player.toggleMute()"    
                >
                    <template x-if="$store.player.muted">
                        <flux:icon.speaker-x-mark class="size-[18px] text-neutral-100" />
                    </template>
                
                    <template x-if="!$store.player.muted">
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

	<audio id="audio-player" :src="$store.player.currentUrl" preload="metadata" class="hidden" />
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('player', {
            audio: null,
            playing: false,
            progress: 0,
            muted: false,

            currentTitle: null,
            currentArtist: null,
            currentUrl: null,
            currentLength: null,

            currentTimeDisplay: '0:00',

            queue: [],
            currentIndex: 0,

            restoreTime: true,

            keys: {
                time: 'audio-player-current-time',
                title: 'player-current-title',
                artist: 'player-current-artist',
                url: 'player-current-url',
                length: 'player-current-length',
                muted: 'player-muted',
            },

            init() {
                this.audio = document.getElementById('audio-player');
                if (!this.audio) return;

                this.restoreQueue();
                this.setupEventListeners();
                this.restoreMuted();
            },

            restoreQueue() {
                const savedQueue = localStorage.getItem('audio-player-queue');
                const savedTitle = localStorage.getItem(this.keys.title);

                if (savedQueue) {
                    this.queue = JSON.parse(savedQueue);

                    this.queue.forEach((song, index) => {
                        if (typeof song.position !== 'number') song.position = index;
                    });

                    this.queue.sort((a, b) => a.position - b.position);

                    this.currentIndex = savedTitle
                        ? this.queue.findIndex(s => s.title === savedTitle)
                        : 0;

                    const currentSong = this.queue[this.currentIndex];

                    if (currentSong) {
                        this.currentTitle = currentSong.title;
                        this.currentArtist = currentSong.artist;
                        this.currentUrl = currentSong.url;
                        this.currentLength = currentSong.length;
                        this.audio.src = currentSong.url;
                    }
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

                if (this.currentUrl === song.url) {
                    this.toggle();

                    return;
                }

                this.queue = [song];
                this.currentIndex = 0;
                this.currentTitle = song.title;
                this.currentArtist = song.artist;
                this.currentUrl = song.url;
                this.currentLength = song.length;

                this.audio.src = song.url;
                this.restoreTime = false;
                this.audio.currentTime = 0;
                this.audio.play();
                this.playing = true;
                this.clearTime();

                this.saveCurrentSong();
                this.saveQueue();
            },

            addToQueue(song) {
                if (this.queue.some(s => s.url === song.url)) {
                    document.dispatchEvent(new CustomEvent('toast-show', {
                        detail: {
                            slots: { text: 'Song is already in the queue' },
                            dataset: { variant: 'warning' },
                            duration: 3000
                        }
                    }));

                    return;
                }

                const max = Math.max(0, ...this.queue.map(s => s.position));
                song.position = max + 1;
                this.queue.push(song);
                this.queue.sort((a, b) => a.position - b.position);

                this.saveQueue();

                if (!this.currentUrl) {
                    this.progress = 0;
                    this.changeSongByIndex(0);
                }

                document.dispatchEvent(new CustomEvent('toast-show', {
                    detail: {
                        slots: { text: 'Added to the queue' },
                        dataset: { variant: 'success' },
                        duration: 3000
                    }
                }));
            },

            clearQueue() {
                this.queue = [];
                this.currentIndex = 0;

                this.currentTitle = null;
                this.currentArtist = null;
                this.currentUrl = null;
                this.currentLength = null;
                this.progress = 0;
                this.playing = false;

                if (this.audio) {
                    this.audio.pause();
                    this.audio.src = '';
                    this.audio.currentTime = 0;
                }

                localStorage.removeItem('audio-player-queue');
                localStorage.removeItem('audio-player-current-index');
                localStorage.removeItem(this.keys.time);
                localStorage.removeItem(this.keys.title);
                localStorage.removeItem(this.keys.artist);
                localStorage.removeItem(this.keys.url);
                localStorage.removeItem(this.keys.length);
            },

            updateQueueOrder(itemTitle, newPositionIndex) {
                const item = this.queue.find(s => s.title === itemTitle);
                const oldIndex = this.queue.indexOf(item);

                this.queue.splice(oldIndex, 1);
                this.queue.splice(newPositionIndex, 0, item);

                this.queue.forEach((s, i) => s.position = i);

                this.currentIndex = this.queue.findIndex(s => s.title === this.currentTitle);

                this.saveQueue();
            },

            changeSongByIndex(index) {
                const song = this.queue[index];
                if (!song) return;

                this.currentIndex = index;
                this.currentTitle = song.title;
                this.currentArtist = song.artist;
                this.currentUrl = song.url;
                this.currentLength = song.length;

                this.audio.src = song.url;
                this.restoreTime = false;
                this.audio.currentTime = 0;
                this.audio.play();
                this.playing = true;

                this.clearTime();
                this.saveCurrentSong();
                this.saveQueue();
            },

            playNext() {
                if (this.currentIndex + 1 < this.queue.length) {
                    this.progress = 0;
                    this.changeSongByIndex(this.currentIndex + 1);
                }
            },

            playPrevious() {
                if (this.currentIndex > 0 && !this.playing) {
                    this.changeSongByIndex(this.currentIndex - 1);
                } else if (this.audio) {
                    this.progress = 0;
                    this.audio.currentTime = 0;
                    this.audio.play();
                    this.playing = true;
                }
            },

            saveCurrentSong() {
                localStorage.setItem(this.keys.title, this.currentTitle);
                localStorage.setItem(this.keys.artist, this.currentArtist);
                localStorage.setItem(this.keys.url, this.currentUrl);
                localStorage.setItem(this.keys.length, this.currentLength);
            },

            saveQueue() {
                localStorage.setItem('audio-player-queue', JSON.stringify(this.queue));
                localStorage.setItem('audio-player-current-index', this.currentIndex);
            },

            clearTime() {
                localStorage.removeItem(this.keys.time);
            },

            toggleMute() {
                this.muted = !this.muted;
                this.audio.muted = this.muted;
                localStorage.setItem(this.keys.muted, this.muted);
            },
        });
    });
</script>
