/**
 * World — the game loop and player state.
 *
 * Owns:
 *  - Player position, facing, keyboard input, click-to-walk
 *  - The 4-frame walk cycle (frame counter + leg-pose swap)
 *  - Parallax + camera transform application
 *  - Eye tracking
 *  - Landmark proximity (fires playNear + opens panel via [E])
 *  - Day/night progression based on x-position
 *  - Menu quick-jump (numeric keys + click) — opens panels immediately and
 *    repositions the character in the background.
 */

import { playExplosion, playFootstep, playLaserShot, playNear } from './audio.js';
import { initCritterSystem } from './critter.js';
import { createEventBus } from './events.js';
import { createFxQualityController } from './fx-quality.js';
import { openPanel, closePanel, isPanelOpen } from './panels.js';

// --- Constants -----------------------------------------------------------

const CACTUAR_SPEED = 6;
const CACTUAR_MIN_X = 60;
const CACTUAR_MAX_X = 2800;
const CRITTER_EDGE_TRIGGER_X = CACTUAR_MAX_X - 20;
const WORLD_WIDTH = 4000;
const INTERACTION_DIST = 80;
const WALK_FRAME_MS = 120;
const WALK_FRAMES = 4; // A → B → A → C

const KEY_LEFT = new Set(['a', 'A', 'ArrowLeft']);
const KEY_RIGHT = new Set(['d', 'D', 'ArrowRight']);
const KEY_INTERACT = new Set(['e', 'E', ' ', 'Enter']);
const KEY_ESC = new Set(['Escape']);

// --- State (exported for other modules) ---------------------------------

export const state = {
	cactuarX: 280,
	facing: 'right',
	keysDown: new Set(),
	clickTarget: null,
	currentSection: null,
};
export const touchState = { left: false, right: false };

// --- Internals ----------------------------------------------------------

let cactuarEl;
let cactuarSpriteEl;
let cactuarLegsA;
let cactuarLegsB;
let cactuarLegsC;
let cactuarEyesEl;
let statusTextEl;
let gameEl;
let worldEl;
let landmarks = [];
let parallaxLayers = [];
let menuItems = [];
let lastTickTime = performance.now();
let walkFrameAccum = 0;
let walkFrameIndex = 0;
let lastNearSection = null;
let currentSprite = '';
let currentSpriteState = '';
let spritesPreloaded = false;
let skyFxEl;
let isNight = false;
let nextBirdSpawnAt = 0;
let nextFlyoverSpawnAt = 0;
let nextUfoSpawnAt = 0;
let ufoEl;
let ufoHasArrived = false;
let ufoSequenceStarted = false;
let ufoDestroyed = false;
let critterSystem;
let liveRegionEl;
let wasAtRightEdge = false;
let score = 0;
let scoreValueEl;
let wasPanelOpen = false;
let panelRewardArmed = null;
let lastLegPose = '';
let lastNearLandmarkEl = null;
let lastActiveMenuSection = null;
let lastNightOpacity = -1;
let lastStarsOpacity = -1;
let lastIsNight = null;
let lastCamX = NaN;
let pointerX = 0;
let pointerY = 0;
let pointerDirty = false;

const eventBus = createEventBus();
const fxQuality = createFxQualityController();

function setPanelRewardArm(section) {
	if (!section) return;
	if (['about', 'portfolio', 'blog', 'contact'].includes(section)) {
		panelRewardArmed = section;
	}
}

function updateScoreHud() {
	if (!scoreValueEl) return;
	scoreValueEl.textContent = String(score).padStart(4, '0');
}

function addScore(points, reason = '') {
	if (!Number.isFinite(points) || points <= 0) return;
	score += points;
	updateScoreHud();
	if (liveRegionEl && reason) {
		liveRegionEl.textContent = `${reason}. Score ${score}.`;
	}
}

function initScoreHud() {
	if (!gameEl || gameEl.querySelector('.score-hud')) return;
	const hud = document.createElement('aside');
	hud.className = 'score-hud';
	hud.setAttribute('aria-label', 'Score');
	hud.innerHTML = '<span class="lbl">score</span><span class="val">0000</span>';
	scoreValueEl = hud.querySelector('.val');
	gameEl.appendChild(hud);
	updateScoreHud();
}

/**
 * Build the landmarks array from the rendered DOM.
 */
function indexLandmarks() {
	landmarks = Array.from(document.querySelectorAll('.landmark')).map((el) => ({
		el,
		section: el.dataset.section,
		x: parseInt(el.style.left, 10),
	}));
}

function indexParallax() {
	parallaxLayers = Array.from(document.querySelectorAll('.parallax .layer'));
}

function indexMenu() {
	menuItems = Array.from(document.querySelectorAll('.menu-item'));
}

function findNearLandmark() {
	for (const lm of landmarks) {
		if (Math.abs(state.cactuarX - lm.x) < INTERACTION_DIST) return lm;
	}
	return null;
}

function updateLegs(walking) {
	if (!cactuarLegsA) return;
	const pose = walking ? `walk-${walkFrameIndex}` : 'idle';
	if (pose === lastLegPose) return;
	lastLegPose = pose;

	if (!walking) {
		cactuarLegsA.style.display = '';
		cactuarLegsB.style.display = 'none';
		cactuarLegsC.style.display = 'none';
		return;
	}
	cactuarLegsA.style.display = (walkFrameIndex === 0 || walkFrameIndex === 2) ? '' : 'none';
	cactuarLegsB.style.display = (walkFrameIndex === 1) ? '' : 'none';
	cactuarLegsC.style.display = (walkFrameIndex === 3) ? '' : 'none';
}

function getSpriteSources() {
	if (!cactuarEl) return null;
	const {
		idleSouth,
		idleEast,
		idleWest,
		walkEast,
		walkWest,
	} = cactuarEl.dataset;
	if (!idleSouth || !idleEast || !idleWest || !walkEast || !walkWest) return null;
	return { idleSouth, idleEast, idleWest, walkEast, walkWest };
}

function preloadSprites() {
	if (spritesPreloaded) return;
	const sprites = getSpriteSources();
	if (!sprites) return;

	const urls = Array.from(new Set(Object.values(sprites)));
	urls.forEach((src) => {
		const img = new Image();
		img.src = src;
	});
	spritesPreloaded = true;
}

function setSprite(src, forceReload = false) {
	if (!cactuarSpriteEl || !src) return;
	if (!forceReload && src === currentSprite) return;

	if (forceReload) {
		// Force GIF playback restart on walk transitions (helps with browsers
		// that keep GIF decoder state across repeated assignments).
		cactuarSpriteEl.src = '';
	}

	cactuarSpriteEl.src = src;
	currentSprite = src;
}

function applySpriteState(spriteState, sprites) {
	if (spriteState === currentSpriteState) return;

	let src = sprites.idleSouth;
	if (spriteState === 'walk-east') src = sprites.walkEast;
	if (spriteState === 'walk-west') src = sprites.walkWest;
	if (spriteState === 'idle-east') src = sprites.idleEast;
	if (spriteState === 'idle-west') src = sprites.idleWest;

	// Always force-reload on transitions so GIF decoder state can't keep
	// showing a stale animation stream from the previous state.
	setSprite(src, true);
	currentSpriteState = spriteState;
}

function updateSprite(deltaX) {
	const sprites = getSpriteSources();
	if (!sprites) return;

	if (deltaX > 0) {
		applySpriteState('walk-east', sprites);
		return;
	}
	if (deltaX < 0) {
		applySpriteState('walk-west', sprites);
		return;
	}

	if (state.facing === 'right') {
		applySpriteState('idle-east', sprites);
		return;
	}
	if (state.facing === 'left') {
		applySpriteState('idle-west', sprites);
		return;
	}

	applySpriteState('idle-south', sprites);
}

function updateEyes() {
	if (!cactuarEyesEl) return;
	// Cursor tracking — translate the eyes a fraction of a pixel toward the
	// pointer. With facing-left, scaleX(-1) inverts the SVG so we flip x too.
	const cactuarRect = cactuarEl.getBoundingClientRect();
	const cx = cactuarRect.left + cactuarRect.width / 2;
	const cy = cactuarRect.top + 28;
	const dx = Math.max(-1, Math.min(1, (pointerX - cx) / 400));
	const dy = Math.max(-1, Math.min(1, (pointerY - cy) / 400));
	const flipMul = state.facing === 'left' ? -1 : 1;
	const tx = dx * flipMul * 0.6;
	const ty = dy * 0.5;
	cactuarEyesEl.style.transform = `translate(${tx}px, ${ty}px)`;
}

function updateLandmarkProximity(near = findNearLandmark()) {
	const nearSection = near ? near.section : null;
	if (nearSection === lastNearSection) {
		return;
	}

	if (nearSection && nearSection !== lastNearSection) {
		playNear();
	}

	if (lastNearLandmarkEl) {
		lastNearLandmarkEl.classList.remove('near');
	}
	if (near?.el) {
		near.el.classList.add('near');
	}
	lastNearLandmarkEl = near?.el || null;
	lastNearSection = nearSection;

	// Update the menu status text to reflect current section.
	if (statusTextEl && nearSection) {
		const labels = {
			about: 'reading character sheet…',
			portfolio: 'browsing portfolio…',
			blog: 'flipping through journal…',
			contact: 'addressing letter…',
			home: 'standing by the sign…',
		};
		if (labels[nearSection]) statusTextEl.textContent = labels[nearSection];
	} else if (statusTextEl && !nearSection) {
		statusTextEl.textContent = statusTextEl.dataset.defaultLabel || statusTextEl.textContent;
	}
}

function updateMenuActive() {
	const near = findNearLandmark();
	const active = near ? near.section : null;
	menuItems.forEach((item) => {
		item.classList.toggle('active', item.dataset.section === active);
	});
}

function updateMenuActiveWithNear(near) {
	const active = near ? near.section : null;
	if (active === lastActiveMenuSection) return;
	lastActiveMenuSection = active;
	menuItems.forEach((item) => {
		item.classList.toggle('active', item.dataset.section === active);
	});
}

function rand(min, max) {
	return min + Math.random() * (max - min);
}

function scheduleNextBird(now) {
	nextBirdSpawnAt = now + rand(4500, 12000);
}

function scheduleNextFlyover(now) {
	nextFlyoverSpawnAt = now + rand(28000, 60000);
}

function scheduleUfoSpawn(now) {
	// Spawn once at a random time in the first few minutes.
	nextUfoSpawnAt = now + rand(30000, 180000);
}

function scaledCount(base, min = 1) {
	return fxQuality.scaledCount(base, min);
}

function spawnBurst(x, y) {
	if (!skyFxEl) return;
	const burst = document.createElement('div');
	burst.className = 'sky-burst';
	burst.style.left = `${x}px`;
	burst.style.top = `${y}px`;

	for (let i = 0; i < scaledCount(28, 12); i += 1) {
		const sparkle = document.createElement('span');
		sparkle.className = 'spark';
		const angle = (Math.PI * 2 * i) / 28 + rand(-0.18, 0.18);
		const distance = rand(55, 130);
		sparkle.style.setProperty('--dx', `${Math.cos(angle) * distance}px`);
		sparkle.style.setProperty('--dy', `${Math.sin(angle) * distance}px`);
		sparkle.style.setProperty('--delay', `${rand(0, 160)}ms`);
		sparkle.style.setProperty('--hue', `${Math.floor(rand(0, 360))}`);
		burst.appendChild(sparkle);
	}

	skyFxEl.appendChild(burst);
	setTimeout(() => burst.remove(), 1400);
}

function spawnBirdFlock() {
	if (!skyFxEl) return;
	const flockSize = Math.floor(rand(2, 5));
	const fromLeft = Math.random() > 0.5;
	const width = gameEl ? gameEl.clientWidth : window.innerWidth;
	const startX = fromLeft ? -40 : width + 40;
	const travelX = fromLeft ? width + 120 : -(width + 120);
	const yBase = rand(34, 170);

	for (let i = 0; i < flockSize; i += 1) {
		const bird = document.createElement('div');
		bird.className = 'sky-bird';
		bird.style.left = `${startX}px`;
		bird.style.top = `${yBase + i * rand(10, 24)}px`;
		bird.style.setProperty('--travel-x', `${travelX}px`);
		bird.style.setProperty('--travel-y', `${rand(-16, 16)}px`);
		bird.style.setProperty('--dur', `${rand(7, 13)}s`);
		bird.style.setProperty('--delay', `${i * 130}ms`);
		skyFxEl.appendChild(bird);
		bird.addEventListener('animationend', () => bird.remove(), { once: true });
	}
}

function spawnFlyover() {
	if (!skyFxEl) return;
	const flyover = document.createElement('button');
	flyover.type = 'button';
	flyover.className = 'sky-flyover';
	flyover.setAttribute('aria-label', 'Sky flyover');

	const width = gameEl ? gameEl.clientWidth : window.innerWidth;
	const fromLeft = Math.random() > 0.5;
	let startX = fromLeft ? -80 : width + 80;
	let travelX = fromLeft ? width + 180 : -(width + 180);
	const top = rand(34, 160);
	let duration = `${rand(16, 24)}s`;

	if (isNight) {
		if (Math.random() > 0.45) {
			flyover.classList.add('satellite');
			duration = `${rand(18, 28)}s`;
		} else {
			flyover.classList.add('shooting-star');
			startX = width + 120;
			travelX = -(width + 260);
			flyover.style.setProperty('--travel-y', `${rand(80, 160)}px`);
			duration = `${rand(7.5, 11)}s`;
		}
	} else {
		flyover.classList.add('plane');
		// Nose points left by design — flip when travelling left→right.
		if (fromLeft) flyover.style.setProperty('--scale-x', '-1');
	}

	flyover.style.left = `${startX}px`;
	flyover.style.top = `${top}px`;
	flyover.style.setProperty('--travel-x', `${travelX}px`);
	flyover.style.setProperty('--dur', duration);

	flyover.addEventListener('click', (e) => {
		e.preventDefault();
		e.stopPropagation();
		if (flyover.classList.contains('shooting-star')) {
			addScore(20, 'Shooting star destroyed');
		} else if (flyover.classList.contains('plane') || flyover.classList.contains('satellite')) {
			addScore(5, 'Flyover destroyed');
		}
		const rect = flyover.getBoundingClientRect();
		const parentRect = skyFxEl.getBoundingClientRect();
		const x = rect.left + rect.width / 2 - parentRect.left;
		const y = rect.top + rect.height / 2 - parentRect.top;
		spawnBurst(x, y);
		flyover.classList.add('hit');
		setTimeout(() => flyover.remove(), 120);
	});

	skyFxEl.appendChild(flyover);
	flyover.addEventListener('animationend', () => flyover.remove(), { once: true });
}

function getElementCenterInSky(el) {
	if (!el || !skyFxEl) return null;
	const rect = el.getBoundingClientRect();
	const parentRect = skyFxEl.getBoundingClientRect();
	return {
		x: rect.left + rect.width / 2 - parentRect.left,
		y: rect.top + rect.height / 2 - parentRect.top,
	};
}

function spawnUfoDebris(cx, cy) {
	if (!skyFxEl) return;
	const debrisWrap = document.createElement('div');
	debrisWrap.className = 'ufo-debris-wrap';
	debrisWrap.style.left = `${cx}px`;
	debrisWrap.style.top = `${cy}px`;

	for (let i = 0; i < scaledCount(80, 30); i += 1) {
		const piece = document.createElement('span');
		piece.className = 'ufo-debris';
		const angle = rand(-Math.PI, Math.PI);
		const speed = rand(80, 340);
		piece.style.setProperty('--dx', `${Math.cos(angle) * speed}px`);
		piece.style.setProperty('--dy', `${Math.sin(angle) * speed}px`);
		piece.style.setProperty('--rot', `${rand(-1080, 1080)}deg`);
		piece.style.setProperty('--delay', `${rand(0, 220)}ms`);
		piece.style.setProperty('--dur', `${rand(700, 1400)}ms`);
		piece.style.setProperty('--size', `${rand(4, 11)}px`);
		piece.style.setProperty('--hue', `${Math.floor(rand(0, 360))}`);
		debrisWrap.appendChild(piece);
	}

	skyFxEl.appendChild(debrisWrap);
	setTimeout(() => debrisWrap.remove(), 2200);
}

function spawnFirework(cx, cy, delay = 0, options = {}) {
	if (!skyFxEl) return;
	setTimeout(() => {
		const {
			spreadX = 180,
			spreadY = 120,
			particleCount = 42,
			minDistance = 70,
			maxDistance = 230,
			sparkSize = 10,
		} = options;
		const count = scaledCount(particleCount, 12);

		const burst = document.createElement('div');
		burst.className = 'ufo-firework';
		burst.style.left = `${cx + rand(-spreadX, spreadX)}px`;
		burst.style.top = `${cy + rand(-spreadY, spreadY * 0.45)}px`;

		for (let i = 0; i < count; i += 1) {
			const spark = document.createElement('span');
			spark.className = 'spark';
			const angle = (Math.PI * 2 * i) / count + rand(-0.2, 0.2);
			const distance = rand(minDistance, maxDistance);
			spark.style.setProperty('--dx', `${Math.cos(angle) * distance}px`);
			spark.style.setProperty('--dy', `${Math.sin(angle) * distance}px`);
			spark.style.setProperty('--delay', `${rand(0, 130)}ms`);
			spark.style.setProperty('--hue', `${Math.floor(rand(0, 360))}`);
			spark.style.setProperty('--spark-size', `${sparkSize}px`);
			burst.appendChild(spark);
		}

		skyFxEl.appendChild(burst);
		setTimeout(() => burst.remove(), 1600);
	}, delay);
}

function spawnAtomicPlume(cx, cy) {
	if (!skyFxEl) return;
	const plume = document.createElement('div');
	plume.className = 'ufo-plume';
	plume.style.left = `${cx}px`;
	plume.style.top = `${cy}px`;

	for (let i = 0; i < scaledCount(80, 28); i += 1) {
		const particle = document.createElement('span');
		particle.className = 'particle';
		particle.style.setProperty('--dx', `${rand(-220, 220)}px`);
		particle.style.setProperty('--dy', `${rand(-360, -120)}px`);
		particle.style.setProperty('--size', `${rand(7, 16)}px`);
		particle.style.setProperty('--delay', `${rand(0, 400)}ms`);
		particle.style.setProperty('--dur', `${rand(1300, 2400)}ms`);
		particle.style.setProperty('--hue', `${Math.floor(rand(20, 64))}`);
		plume.appendChild(particle);
	}

	skyFxEl.appendChild(plume);
	setTimeout(() => plume.remove(), 3400);
}

function spawnUfoBlast(cx, cy, { distant = false, huge = false } = {}) {
	if (!skyFxEl) return;

	const shockwave = document.createElement('div');
	shockwave.className = 'ufo-shockwave';
	if (distant) shockwave.classList.add('distant');
	if (huge) shockwave.classList.add('huge');
	shockwave.style.left = `${cx}px`;
	shockwave.style.top = `${cy}px`;
	skyFxEl.appendChild(shockwave);
	setTimeout(() => shockwave.remove(), 1200);

	const flash = document.createElement('div');
	flash.className = 'ufo-flash';
	if (distant) flash.classList.add('distant');
	if (huge) flash.classList.add('huge');
	skyFxEl.appendChild(flash);
	setTimeout(() => flash.remove(), 420);

	spawnBurst(cx, cy);
	spawnBurst(cx + rand(-70, 70), cy + rand(-45, 45));
	if (!distant) spawnUfoDebris(cx, cy);

	for (let i = 0; i < scaledCount((huge ? 24 : 14), 8); i += 1) {
		spawnFirework(cx, cy, i * 120);
	}
}

function spawnLaserBeam(launchX, launchY, targetX, targetY, {
	delay = 0,
	className = 'ufo-laser',
	removeAfter = 900,
} = {}) {
	if (!skyFxEl) return;
	const dx = targetX - launchX;
	const dy = targetY - launchY;
	const length = Math.hypot(dx, dy);
	const angle = (Math.atan2(dy, dx) * 180) / Math.PI;

	const beam = document.createElement('div');
	beam.className = className;
	beam.style.left = `${launchX}px`;
	beam.style.top = `${launchY}px`;
	beam.style.setProperty('--beam-len', `${Math.max(80, length)}px`);
	beam.style.setProperty('--beam-angle', `${angle}deg`);
	beam.style.animationDelay = `${delay}ms`;
	skyFxEl.appendChild(beam);

	setTimeout(() => beam.remove(), delay + removeAfter);
}

function runUfoAftermathShow(impactX, impactY) {
	if (!skyFxEl) return;
	const width = gameEl ? gameEl.clientWidth : window.innerWidth;
	const height = gameEl ? gameEl.clientHeight : window.innerHeight;
	const showX = Math.max(120, Math.min(width - 120, impactX));
	const showY = Math.max(height * 0.74, Math.min(height * 0.92, impactY));

	setTimeout(() => {
		playExplosion();
		eventBus.emit('ufo:explosion');
		spawnUfoBlast(showX, showY, { distant: true, huge: true });
		spawnUfoDebris(showX, showY);
		spawnAtomicPlume(showX, showY - 10);
	}, 120);

	setTimeout(() => spawnUfoBlast(showX + rand(-70, 70), showY + rand(-20, 20), { distant: true, huge: true }), 860);
	setTimeout(() => spawnUfoBlast(showX + rand(-110, 110), showY + rand(-35, 30), { distant: true }), 1500);

	for (let i = 0; i < scaledCount(28, 10); i += 1) {
		spawnFirework(showX, showY - rand(40, 160), 260 + i * 95, {
			spreadX: 240,
			spreadY: 170,
			particleCount: 56,
			minDistance: 120,
			maxDistance: 320,
			sparkSize: 12,
		});
	}
}

function fireLaserAtUfo(onComplete) {
	if (!ufoEl || !skyFxEl) return;
	const center = getElementCenterInSky(ufoEl);
	if (!center) return;

	const width = gameEl ? gameEl.clientWidth : window.innerWidth;
	const launchX = rand(width * 0.18, width * 0.82);
	const launchY = (gameEl ? gameEl.clientHeight : window.innerHeight) * 0.74;

	spawnLaserBeam(launchX, launchY, center.x, center.y, {
		className: 'ufo-laser',
		removeAfter: 700,
	});
	playLaserShot();
	eventBus.emit('ufo:laser');

	setTimeout(() => {
		onComplete?.();
	}, 560);
}

function startUfoCrash() {
	if (!ufoEl || !skyFxEl) return;
	const center = getElementCenterInSky(ufoEl);
	if (!center) return;
	const crashDx = rand(-38, 38);
	const height = gameEl ? gameEl.clientHeight : window.innerHeight;
	const crashDy = Math.max(260, (height - center.y) + rand(110, 180));
	const impactX = center.x + crashDx;
	const impactY = Math.min(height - 4, center.y + crashDy);

	ufoEl.classList.remove('arriving', 'hovering', 'targeted');
	ufoEl.classList.add('crashing');
	ufoEl.style.setProperty('--crash-x', `${crashDx}px`);
	ufoEl.style.setProperty('--crash-y', `${crashDy}px`);
	ufoEl.style.setProperty('--crash-x-soft', `${crashDx * 0.3}px`);
	ufoEl.style.setProperty('--crash-y-soft', `${crashDy * 0.22}px`);
	ufoEl.style.setProperty('--crash-spin', `${Math.floor(rand(780, 1120))}deg`);
	ufoEl.style.setProperty('--crash-spin-soft', `${Math.floor(rand(240, 360))}deg`);

	const smoke = document.createElement('div');
	smoke.className = 'ufo-smoke';
	smoke.style.left = `${center.x}px`;
	smoke.style.top = `${center.y}px`;
	skyFxEl.appendChild(smoke);
	setTimeout(() => smoke.remove(), 2200);

	ufoEl.addEventListener('animationend', () => {
		setTimeout(() => {
			ufoEl?.remove();
			ufoEl = null;
			ufoDestroyed = true;
			runUfoAftermathShow(impactX, impactY);
		}, 250);
	}, { once: true });
}

function handleUfoClick(e) {
	e.preventDefault();
	e.stopPropagation();
	if (!ufoEl || ufoSequenceStarted || ufoDestroyed || !ufoHasArrived) return;
	ufoSequenceStarted = true;
	ufoEl.classList.add('targeted');
	fireLaserAtUfo(() => {
		startUfoCrash();
	});
}

function spawnUfo() {
	if (!skyFxEl || ufoEl) return;
	const existingUfo = skyFxEl.querySelector('.sky-ufo');
	if (existingUfo) {
		ufoEl = existingUfo;
		if (ufoEl.dataset.bound !== '1') {
			ufoEl.addEventListener('click', handleUfoClick);
			ufoEl.dataset.bound = '1';
		}
		if (!ufoEl.querySelector('.ufo-marking')) {
			const badge = document.createElement('span');
			badge.className = 'ufo-marking';
			badge.textContent = 'P1';
			ufoEl.appendChild(badge);
		}
		ufoHasArrived = !existingUfo.classList.contains('arriving');
		return;
	}
	ufoEl = document.createElement('button');
	ufoEl.type = 'button';
	ufoEl.className = 'sky-ufo arriving';
	ufoEl.setAttribute('aria-label', 'Mysterious UFO');

	const width = gameEl ? gameEl.clientWidth : window.innerWidth;
	ufoEl.style.left = `${rand(width * 0.2, width * 0.82)}px`;
	ufoEl.style.top = `${rand(84, 160)}px`;
	ufoEl.addEventListener('click', handleUfoClick);
	ufoEl.dataset.bound = '1';
	const badge = document.createElement('span');
	badge.className = 'ufo-marking';
	badge.textContent = 'P1';
	ufoEl.appendChild(badge);

	skyFxEl.appendChild(ufoEl);
	ufoEl.addEventListener('animationend', (evt) => {
		if (evt.animationName !== 'cmp-ufo-arrive') return;
		ufoHasArrived = true;
		ufoEl?.classList.remove('arriving');
		ufoEl?.classList.add('hovering');
	}, { once: true });
}

function updateSkyFx(now) {
	if (!skyFxEl) return;
	if (now >= nextBirdSpawnAt) {
		spawnBirdFlock();
		scheduleNextBird(now);
	}
	if (now >= nextFlyoverSpawnAt) {
		spawnFlyover();
		scheduleNextFlyover(now);
	}
	if (nextUfoSpawnAt && now >= nextUfoSpawnAt && !ufoEl && !ufoDestroyed) {
		nextUfoSpawnAt = 0;
		spawnUfo();
	}
	critterSystem?.update(now);
}

function initSkyFx() {
	skyFxEl = document.getElementById('sky-fx');
	if (!skyFxEl || skyFxEl.dataset.ready === '1') return;

	const sun = document.createElement('div');
	sun.className = 'sky-body sun';
	const moon = document.createElement('div');
	moon.className = 'sky-body moon';
	skyFxEl.appendChild(sun);
	skyFxEl.appendChild(moon);

	skyFxEl.dataset.ready = '1';
	critterSystem = initCritterSystem({ gameEl, eventBus, groundOffsetPercent: 11 });
	const now = performance.now();
	scheduleNextBird(now + rand(800, 1800));
	scheduleNextFlyover(now + rand(12000, 22000));
	scheduleUfoSpawn(now);
}

function updateDayNight() {
	const range = CACTUAR_MAX_X - CACTUAR_MIN_X;
	const progress = Math.max(0, Math.min(1, (state.cactuarX - CACTUAR_MIN_X) / range));
	const overlay = document.getElementById('night-overlay');
	const starsNight = document.getElementById('stars-night');
	const nightStrength = Math.pow(progress, 1.6) * 0.65;
	const starsStrength = Math.pow(progress, 1.4);
	if (overlay && Math.abs(nightStrength - lastNightOpacity) > 0.0025) {
		overlay.style.opacity = nightStrength;
		lastNightOpacity = nightStrength;
	}
	if (starsNight && Math.abs(starsStrength - lastStarsOpacity) > 0.0025) {
		starsNight.style.opacity = starsStrength;
		lastStarsOpacity = starsStrength;
	}
	isNight = nightStrength > 0.3;
	if (skyFxEl && isNight !== lastIsNight) {
		skyFxEl.classList.toggle('is-night', isNight);
		lastIsNight = isNight;
	}
}

function applyTransforms() {
	// Camera: keep Cactuar around the left-third of the viewport.
	const viewportW = gameEl.clientWidth;
	const camX = Math.max(0, Math.min(
		WORLD_WIDTH - viewportW,
		state.cactuarX - viewportW * 0.3
	));
	if (camX === lastCamX) {
		return;
	}
	lastCamX = camX;
	worldEl.style.transform = `translateX(${-camX}px)`;

	// Parallax — each layer translates by depth * camX.
	parallaxLayers.forEach((layer) => {
		const depth = parseFloat(layer.dataset.depth) || 0;
		layer.style.transform = `translateX(${-camX * depth}px)`;
	});
}

// --- Main tick ----------------------------------------------------------

function tick() {
	const now = performance.now();
	const dt = Math.min(50, now - lastTickTime);
	lastTickTime = now;
	fxQuality.reportFrame(dt);

	let vx = 0;

	// Keyboard
	for (const k of state.keysDown) {
		if (KEY_LEFT.has(k)) vx -= CACTUAR_SPEED;
		if (KEY_RIGHT.has(k)) vx += CACTUAR_SPEED;
	}
	// Touch d-pad
	if (vx === 0) {
		if (touchState.left) vx -= CACTUAR_SPEED;
		if (touchState.right) vx += CACTUAR_SPEED;
	}
	// Click-to-walk / menu fast-travel
	if (vx === 0 && state.clickTarget !== null) {
		const diff = state.clickTarget - state.cactuarX;
		if (Math.abs(diff) < CACTUAR_SPEED) {
			state.cactuarX = state.clickTarget;
			state.clickTarget = null;
		} else {
			vx = Math.sign(diff) * CACTUAR_SPEED;
		}
	}

	const prevX = state.cactuarX;
	const prevFacing = state.facing;
	if (vx !== 0) {
		state.cactuarX = Math.max(CACTUAR_MIN_X, Math.min(CACTUAR_MAX_X, state.cactuarX + vx));
		if (vx > 0) state.facing = 'right';
		if (vx < 0) state.facing = 'left';
	}
	const deltaX = state.cactuarX - prevX;
	const willMove = deltaX !== 0;
	const facingChanged = prevFacing !== state.facing;
	const isAtRightEdge = state.cactuarX >= CRITTER_EDGE_TRIGGER_X;
	const nearLandmark = findNearLandmark();

	// Walk-cycle frame counter.
	if (willMove) {
		walkFrameAccum += dt;
		if (walkFrameAccum >= WALK_FRAME_MS) {
			walkFrameAccum = 0;
			walkFrameIndex = (walkFrameIndex + 1) % WALK_FRAMES;
			if (walkFrameIndex === 0 || walkFrameIndex === 2) playFootstep();
		}
	} else {
		walkFrameAccum = 0;
		walkFrameIndex = 0;
	}

	cactuarEl.style.left = `${state.cactuarX}px`;
	cactuarEl.classList.toggle('walking', willMove);
	cactuarEl.classList.toggle('facing-left', state.facing === 'left');
	cactuarEl.classList.toggle('bob-down', willMove && (walkFrameIndex === 0 || walkFrameIndex === 2));
	updateLegs(willMove);
	updateSprite(deltaX);

	applyTransforms();
	if (pointerDirty || willMove || facingChanged) {
		updateEyes();
		pointerDirty = false;
	}
	updateLandmarkProximity(nearLandmark);
	updateMenuActiveWithNear(nearLandmark);
	if (willMove) {
		updateDayNight();
	}
	updateSkyFx(now);
	const panelOpenNow = isPanelOpen();
	if (wasPanelOpen && !panelOpenNow && panelRewardArmed) {
		addScore(10, 'Section explored');
		panelRewardArmed = null;
	}
	wasPanelOpen = panelOpenNow;

	// If the player reaches the far-right boundary, force a sheep popup once
	// per edge-entry (unless one is already active).
	if (isAtRightEdge && !wasAtRightEdge) {
		critterSystem?.spawnNow();
	}
	wasAtRightEdge = isAtRightEdge;

	requestAnimationFrame(tick);
}

// --- Input handlers -----------------------------------------------------

function bindKeyboard() {
	window.addEventListener('keydown', (e) => {
		// Numeric fast-travel.
		const num = parseInt(e.key, 10);
		if (num >= 1 && num <= 5 && menuItems[num - 1]) {
			e.preventDefault();
			activateMenuItem(menuItems[num - 1]);
			return;
		}
		if (KEY_ESC.has(e.key)) {
			if (isPanelOpen()) closePanel();
			return;
		}
		if (KEY_INTERACT.has(e.key)) {
			const near = findNearLandmark();
			if (near && !isPanelOpen()) {
				e.preventDefault();
				setPanelRewardArm(near.section);
				openPanel(near.section);
			}
			return;
		}
		if (KEY_LEFT.has(e.key) || KEY_RIGHT.has(e.key)) {
			state.keysDown.add(e.key);
			state.clickTarget = null;
		}
	});
	window.addEventListener('keyup', (e) => {
		state.keysDown.delete(e.key);
	});

	// Prevent stuck movement keys when window/tab focus changes and keyup is lost.
	const clearInputState = () => {
		state.keysDown.clear();
		state.clickTarget = null;
	};
	window.addEventListener('blur', clearInputState);
	document.addEventListener('visibilitychange', () => {
		if (document.hidden) clearInputState();
	});
}

function bindClickToWalk() {
	gameEl.addEventListener('click', (e) => {
		// Ignore clicks on interactive UI.
		if (e.target.closest('.menu-bar, .panel-overlay, .controls-hint, .touch-controls, #critter-fx, .joke-critter, .joke-bubble, .joke-action')) return;
		const rect = gameEl.getBoundingClientRect();
		const cx = e.clientX - rect.left;
		const camX = Math.max(0, state.cactuarX - rect.width * 0.3);
		state.clickTarget = Math.max(CACTUAR_MIN_X, Math.min(CACTUAR_MAX_X, cx + camX));
	});
}

function activateMenuItem(item) {
	const targetX = parseInt(item.dataset.x, 10);
	const section = item.dataset.section;
	const prevX = state.cactuarX;
	const clampedTargetX = Math.max(CACTUAR_MIN_X, Math.min(CACTUAR_MAX_X, targetX));
	state.clickTarget = null;
	state.currentSection = section;

	const teleport = () => {
		state.cactuarX = clampedTargetX;
		state.facing = clampedTargetX >= prevX ? 'right' : 'left';
		cactuarEl.style.left = `${state.cactuarX}px`;
		cactuarEl.classList.remove('walking', 'bob-down');
		cactuarEl.classList.toggle('facing-left', state.facing === 'left');
		updateSprite(0);
		applyTransforms();
		updateLandmarkProximity();
		updateMenuActive();
		updateDayNight();
	};

	if (section !== 'home') {
		setPanelRewardArm(section);
		openPanel(section);
		requestAnimationFrame(teleport);
		return;
	}

	teleport();
}

function bindMenu() {
	menuItems.forEach((item) => {
		item.addEventListener('click', () => activateMenuItem(item));
	});
}

function bindHintClose() {
	const close = document.getElementById('hint-close');
	const hint = document.getElementById('controls-hint');
	if (close && hint) {
		close.addEventListener('click', () => hint.classList.add('hidden'));
		setTimeout(() => hint.classList.add('hidden'), 18000);
	}
}

// --- Public entrypoint --------------------------------------------------

export function initWorld(world) {
	gameEl = world;
	worldEl = document.getElementById('world');
	cactuarEl = document.getElementById('cactuar');
	cactuarSpriteEl = document.getElementById('cactuar-sprite');
	if (!worldEl || !cactuarEl) return;
	cactuarLegsA = cactuarEl.querySelector('.legs-a');
	cactuarLegsB = cactuarEl.querySelector('.legs-b');
	cactuarLegsC = cactuarEl.querySelector('.legs-c');
	cactuarEyesEl = document.getElementById('cactuar-eyes');
	pointerX = window.innerWidth / 2;
	pointerY = window.innerHeight / 2;
	window.addEventListener('pointermove', (e) => {
		pointerX = e.clientX;
		pointerY = e.clientY;
		pointerDirty = true;
	}, { passive: true });
	currentSprite = cactuarSpriteEl ? (cactuarSpriteEl.currentSrc || cactuarSpriteEl.src || '') : '';
	currentSpriteState = state.facing === 'left' ? 'idle-west' : 'idle-east';
	if (cactuarSpriteEl) {
		cactuarSpriteEl.addEventListener('error', () => {
			currentSprite = '';
			currentSpriteState = '';
		});
	}

	if (!liveRegionEl) {
		liveRegionEl = document.createElement('div');
		liveRegionEl.className = 'screen-reader-text cmp-live';
		liveRegionEl.setAttribute('aria-live', 'polite');
		liveRegionEl.setAttribute('aria-atomic', 'true');
		document.body.appendChild(liveRegionEl);
	}
	eventBus.on('critter:setupStart', (joke) => {
		if (!liveRegionEl) return;
		liveRegionEl.textContent = `Joke setup: ${joke.setup}`;
	});
	eventBus.on('critter:setupStart', () => {
		addScore(1, 'Sheep interaction started');
	});
	eventBus.on('critter:punchlineComplete', (joke) => {
		if (!liveRegionEl) return;
		liveRegionEl.textContent = `Punchline: ${joke.punchline}`;
	});
	eventBus.on('ufo:explosion', () => {
		addScore(50, 'UFO exploded');
	});
	eventBus.on('critter:punchlineComplete', () => {
		addScore(5, 'Joke completed');
	});
	preloadSprites();

	// Record the default status-text label so we can restore it.
	statusTextEl = document.getElementById('status-text');
	if (statusTextEl) statusTextEl.dataset.defaultLabel = statusTextEl.textContent;

	indexLandmarks();
	indexParallax();
	indexMenu();
	initScoreHud();
	initSkyFx();

	bindKeyboard();
	bindClickToWalk();
	bindMenu();
	bindHintClose();
	updateDayNight();

	requestAnimationFrame(tick);
}

// Helper export for touch.js
export function getNearLandmark() {
	return findNearLandmark();
}
