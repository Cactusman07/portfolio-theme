/**
 * Audio — procedural Web Audio synth.
 *
 * No audio assets — every effect is a 1–2 note square/sine osc. Initialisation
 * is deferred until the first user gesture (browser autoplay policy). The
 * mute preference is persisted to localStorage so it survives navigation.
 *
 * Public:
 *   initAudio()       — set up the toggle + gesture handlers.
 *   playFootstep()    — random 180–240 Hz square tick.
 *   playOpen()        — ascending two-note blip.
 *   playClose()       — descending two-note blip.
 *   playNear()        — single 880 Hz sine ping.
 *   playLaserShot()   — quick sci-fi "pew" sweep.
 *   playExplosion()   — short layered boom burst.
 */

const STORAGE_KEY = 'sm_muted';

const audio = {
	ctx:    null,
	master: null,
	muted:  false,
};

function ensureCtx() {
	if (audio.ctx) return;
	try {
		const AC = window.AudioContext || window.webkitAudioContext;
		if (!AC) return;
		audio.ctx = new AC();
		audio.master = audio.ctx.createGain();
		audio.master.gain.value = 0.5;
		audio.master.connect(audio.ctx.destination);
	} catch (e) { /* no-op */ }
}

function tone(freq, dur = 0.05, type = 'square', vol = 0.06) {
	if (!audio.ctx || audio.muted) return;
	try {
		const osc  = audio.ctx.createOscillator();
		const gain = audio.ctx.createGain();
		osc.type           = type;
		osc.frequency.value = freq;
		gain.gain.setValueAtTime(vol, audio.ctx.currentTime);
		gain.gain.exponentialRampToValueAtTime(0.0001, audio.ctx.currentTime + dur);
		osc.connect(gain);
		gain.connect(audio.master);
		osc.start();
		osc.stop(audio.ctx.currentTime + dur + 0.02);
	} catch (e) { /* no-op */ }
}

function sweep(startFreq, endFreq, dur = 0.1, type = 'sawtooth', vol = 0.05) {
	if (!audio.ctx || audio.muted) return;
	try {
		const now  = audio.ctx.currentTime;
		const osc  = audio.ctx.createOscillator();
		const gain = audio.ctx.createGain();
		osc.type = type;
		osc.frequency.setValueAtTime(startFreq, now);
		osc.frequency.exponentialRampToValueAtTime(Math.max(20, endFreq), now + dur);
		gain.gain.setValueAtTime(vol, now);
		gain.gain.exponentialRampToValueAtTime(0.0001, now + dur);
		osc.connect(gain);
		gain.connect(audio.master);
		osc.start(now);
		osc.stop(now + dur + 0.03);
	} catch (e) { /* no-op */ }
}

export function playFootstep() {
	tone(180 + Math.random() * 60, 0.04, 'square', 0.025);
}
export function playOpen() {
	tone(440, 0.08, 'square', 0.05);
	setTimeout(() => tone(660, 0.10, 'square', 0.05), 60);
}
export function playClose() {
	tone(440, 0.06, 'square', 0.04);
	setTimeout(() => tone(330, 0.08, 'square', 0.04), 50);
}
export function playNear() {
	tone(880, 0.04, 'sine', 0.03);
}

export function playLaserShot() {
	// Arcade-style "pew": bright square lead with stepped downward blips.
	tone(1760, 0.02, 'square', 0.04);
	setTimeout(() => tone(1320, 0.03, 'square', 0.035), 14);
	setTimeout(() => tone(980, 0.038, 'square', 0.032), 32);
	setTimeout(() => tone(740, 0.045, 'square', 0.026), 58);
}

export function playExplosion() {
	// Retro boom: low thump + crunchy square burst + falling tone.
	tone(104, 0.09, 'triangle', 0.07);
	setTimeout(() => tone(78, 0.12, 'triangle', 0.062), 22);
	setTimeout(() => tone(62, 0.16, 'triangle', 0.052), 54);

	setTimeout(() => tone(420, 0.028, 'square', 0.04), 8);
	setTimeout(() => tone(320, 0.03, 'square', 0.036), 26);
	setTimeout(() => tone(240, 0.034, 'square', 0.03), 46);

	setTimeout(() => sweep(220, 54, 0.18, 'square', 0.03), 40);
}

function setMuted(value) {
	audio.muted = value;
	const toggle = document.getElementById('sound-toggle');
	if (toggle) toggle.classList.toggle('muted', value);
	try { localStorage.setItem(STORAGE_KEY, value ? '1' : '0'); } catch (e) { /* no-op */ }
}

export function initAudio() {
	// Restore mute preference.
	try {
		if (localStorage.getItem(STORAGE_KEY) === '1') setMuted(true);
	} catch (e) { /* no-op */ }

	// Initialise context on first gesture.
	const prime = () => {
		ensureCtx();
		if (audio.ctx && audio.ctx.state === 'suspended') audio.ctx.resume();
	};
	window.addEventListener('keydown',    prime, { once: true });
	window.addEventListener('mousedown',  prime, { once: true });
	window.addEventListener('touchstart', prime, { once: true });

	// Sound toggle binding.
	const toggle = document.getElementById('sound-toggle');
	if (toggle) {
		toggle.addEventListener('click', (e) => {
			e.preventDefault();
			setMuted(!audio.muted);
		});
	}
}
