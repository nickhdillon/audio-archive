export default {
    audio: null,
    playing: false,
    progress: 0,

    currentTitle: null,
    currentArtist: null,
    currentUrl: null,

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

        this.restoreSavedSong();
        this.setupEventListeners();
    },

    restoreSavedSong() {
        const url = localStorage.getItem(this.keys.url);
        if (!url) return;

        this.currentUrl = url;
        this.currentTitle = localStorage.getItem(this.keys.title);
        this.currentArtist = localStorage.getItem(this.keys.artist);
        this.audio.src = url;
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
        };
    },

    toggle() {
        if (!this.audio) return;

        if (this.playing) this.audio.pause();
        else this.audio.play();

        this.playing = !this.playing;
    },

    changeSong({ title, artist, url }) {
        if (!this.audio) return;

        if (this.currentUrl !== url) {
            this.progress = 0;
            this.currentTitle = title;
            this.currentArtist = artist;
            this.currentUrl = url;

            this.saveCurrentSong();
            this.audio.src = url;
            this.restoreTime = false;
            this.audio.currentTime = 0;
            this.audio.play();
            this.playing = true;
            this.clearTime();
        } else {
            this.toggle();
        }
    },

    saveCurrentSong() {
        localStorage.setItem(this.keys.title, this.currentTitle);
        localStorage.setItem(this.keys.artist, this.currentArtist);
        localStorage.setItem(this.keys.url, this.currentUrl);
    },

    clearTime() {
        localStorage.removeItem(this.keys.time);
    },
};