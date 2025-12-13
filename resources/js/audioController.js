let _audioSource = null;
let _filtersConnected = false;

export default function AudioController() {
    const audio = document.getElementById('audio-player');
    if (!audio) return null;

    const audioCtx = new AudioContext();

    if (!_audioSource) {
        _audioSource = audioCtx.createMediaElementSource(audio);
    }

    const track = _audioSource;

    const frequencies = [60, 150, 400, 1000, 2400, 15000];

    const filters = frequencies.map(freq => {
        const biQuadFilter = audioCtx.createBiquadFilter();

        biQuadFilter.type = 'peaking';
        biQuadFilter.frequency.value = freq;
        biQuadFilter.Q.value = 1;
        biQuadFilter.gain.value = 0;

        return biQuadFilter;
    });

    if (!_filtersConnected) {
        track.connect(filters[0]);

        for (let i = 0; i < filters.length - 1; i++) {
            filters[i].connect(filters[i + 1]);
        }

        filters[filters.length - 1].connect(audioCtx.destination);
		
        _filtersConnected = true;
    }

    return { audioCtx, track, filters, frequencies };
}
