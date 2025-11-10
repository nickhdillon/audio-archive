export default {
    audio: null,
    playing: false,
    progress: 0,

    currentTitle: null,
    currentArtist: null,
    currentUrl: null,

    queue: [],
    currentIndex: 0,

    restoreTime: true,

    keys: {
        time: 'audio-player-current-time',
        title: 'player-current-title',
        artist: 'player-current-artist',
        url: 'player-current-url',
    },

    init() {
        this.audio = document.getElementById('audio-player');
        if (!this.audio) return;

        this.restoreQueue();
        this.setupEventListeners();
    },

    restoreQueue() {
        const savedQueue = localStorage.getItem('audio-player-queue');
        const savedIndex = localStorage.getItem('audio-player-current-index');

        if (savedQueue) {
            this.queue = JSON.parse(savedQueue);
            this.currentIndex = savedIndex ? parseInt(savedIndex) : 0;

            const currentSong = this.queue[this.currentIndex];

            if (currentSong) {
                this.currentTitle = currentSong.title;
                this.currentArtist = currentSong.artist;
                this.currentUrl = currentSong.url;
                this.audio.src = currentSong.url;
            }
        }
    },

    setupEventListeners() {
        this.audio.addEventListener('play', () => this.playing = true);
        this.audio.addEventListener('pause', () => this.playing = false);

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

        if (this.currentUrl === song.url) {
            this.toggle();
            
            return;
        }

        this.queue = [song];
        this.currentIndex = 0;
        this.currentTitle = song.title;
        this.currentArtist = song.artist;
        this.currentUrl = song.url;

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
        this.queue.push(song);
        this.saveQueue();

        if (!this.currentUrl) {
            this.changeSongByIndex(0);
        }

        window.$flux.toast({
            text: 'Added to the queue',
            variant: 'success',
            duration: 3000
        });
    },

    changeSongByIndex(index) {
        if (!this.audio || !this.queue[index]) return;

        const song = this.queue[index];
        this.currentIndex = index;

        this.progress = 0;
        this.currentTitle = song.title;
        this.currentArtist = song.artist;
        this.currentUrl = song.url;

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
            this.changeSongByIndex(this.currentIndex + 1);
        }
    },

    playPrevious() {
        if (this.currentIndex > 0) {
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
    },

    saveQueue() {
        localStorage.setItem('audio-player-queue', JSON.stringify(this.queue));
        localStorage.setItem('audio-player-current-index', this.currentIndex);
    },

    clearTime() {
        localStorage.removeItem(this.keys.time);
    }
};
