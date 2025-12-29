import AudioController from './audioController.js';

export default (gains, presets) => ({
	gains: gains ?? [0, 0, 0, 0, 0, 0],
	frequencies: [60, 150, 400, 1000, 2400, 15000],
	presets: presets,
	controller: window.AudioController || AudioController(),
	isApplyingPreset: false,

	init() {
		if (!this.controller) return;

		window.AudioController = this.controller;

		document.addEventListener('applyPreset', (e) => {
			const presetId = Number(e.target.value);
			const preset = this.presets.find(preset => preset.id === presetId);

			if (!preset) return;

			this.applyPreset(preset.gains);
		});

		document.addEventListener('preset-reset', () => {
			this.gains = [0, 0, 0, 0, 0, 0];
		});
	},

	updateFilter(index) {
		if (this.controller?.filters[index]) {
			this.controller.filters[index].gain.value = this.gains[index];
		}

		if (!this.isApplyingPreset) {
			window.dispatchEvent(new CustomEvent('eq-manual-change'));
		}
	},

	saveEQ() {
		window.dispatchEvent(new CustomEvent('eq-changed', { detail: { eq_values: this.gains } }));
	},

	applyPreset(gains) {
		this.isApplyingPreset = true;

		this.gains = gains;
		this.gains.forEach((_, index) => this.updateFilter(index));

		this.isApplyingPreset = false;

		this.saveEQ();
	},

	barHeight(index) {
		const gain = this.gains[index];

		if (index === 0) {
			return (gain + 6) / 12 * 100;
		}

		return (gain + 12) / 24 * 100;
	},

	zeroLine() {
		const thumbOffset = -11;
		const trackHeight = 160;

		return ((trackHeight / 2 - thumbOffset) / trackHeight) * 100;
	},

	formatFrequency(freq) {
		if (freq >= 1000) {
			return (freq / 1000) + ' kHz';
		}

		return freq + ' Hz';
	}
});