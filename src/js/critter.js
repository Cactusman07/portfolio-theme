import { createTimerRegistry } from './utils/timers.js';

const FALLBACK_JOKES = [
	{ setup: 'Why did the scarecrow win an award?', punchline: 'Because he was outstanding in his field.' },
	{ setup: 'What do you call a fish wearing a bowtie?', punchline: 'Sofishticated.' },
	{ setup: 'Why did the computer get cold?', punchline: 'It left its Windows open.' },
	{ setup: 'What did one wall say to the other?', punchline: 'I will meet you at the corner.' },
];

function rand(min, max) {
	return min + Math.random() * (max - min);
}

function createCritterSvg(kind, variant = 'fluffy') {
	if (kind === 'sheep') {
		if (variant === 'storybook') {
			return `
			<svg viewBox="0 0 120 76" aria-hidden="true" focusable="false">
				<ellipse cx="43" cy="38" rx="31" ry="20" fill="#e9e8ef"/>
				<ellipse cx="26" cy="32" rx="12" ry="10" fill="#f7f7fc"/>
				<ellipse cx="38" cy="27" rx="13" ry="11" fill="#f7f7fc"/>
				<ellipse cx="51" cy="27" rx="13" ry="11" fill="#f7f7fc"/>
				<ellipse cx="62" cy="33" rx="11" ry="9" fill="#f7f7fc"/>
				<ellipse cx="76" cy="40" rx="16" ry="13" fill="#32344d"/>
				<polygon points="67,34 72,26 77,34" fill="#32344d"/>
				<polygon points="85,34 90,26 95,34" fill="#32344d"/>
				<circle cx="71" cy="40" r="2.3" fill="#fef8ea"/>
				<circle cx="81" cy="40" r="2.3" fill="#fef8ea"/>
				<ellipse cx="76" cy="46" rx="3.1" ry="2.2" fill="#e7b3b8"/>
				<rect x="23" y="56" width="6" height="14" fill="#2a2b40"/>
				<rect x="38" y="56" width="6" height="14" fill="#2a2b40"/>
				<rect x="53" y="56" width="6" height="14" fill="#2a2b40"/>
				<rect x="67" y="56" width="6" height="14" fill="#2a2b40"/>
			</svg>`;
		}

		if (variant === 'realistic') {
			return `
			<svg viewBox="0 0 120 76" aria-hidden="true" focusable="false">
				<ellipse cx="40" cy="40" rx="28" ry="19" fill="#e7e4ef"/>
				<ellipse cx="22" cy="37" rx="9" ry="8" fill="#f6f4fb"/>
				<ellipse cx="33" cy="30" rx="10" ry="8" fill="#f6f4fb"/>
				<ellipse cx="46" cy="29" rx="11" ry="9" fill="#f8f6fc"/>
				<ellipse cx="57" cy="35" rx="10" ry="8" fill="#f4f1f9"/>
				<ellipse cx="76" cy="42" rx="15" ry="12" fill="#3a3b4f"/>
				<polygon points="69,34 72,26 78,33" fill="#3a3b4f"/>
				<polygon points="85,34 90,26 95,35" fill="#3a3b4f"/>
				<ellipse cx="71" cy="42" rx="2.2" ry="2" fill="#fdf9ec"/>
				<ellipse cx="80" cy="42" rx="2.2" ry="2" fill="#fdf9ec"/>
				<ellipse cx="76" cy="47" rx="2.8" ry="2.1" fill="#e7b4b7"/>
				<rect x="23" y="56" width="6" height="14" fill="#2d2d3f"/>
				<rect x="38" y="56" width="6" height="14" fill="#2d2d3f"/>
				<rect x="53" y="56" width="6" height="14" fill="#2d2d3f"/>
				<rect x="68" y="56" width="6" height="14" fill="#2d2d3f"/>
			</svg>`;
		}

		if (variant === 'retro') {
			return `
			<svg viewBox="0 0 120 76" aria-hidden="true" focusable="false">
				<rect x="14" y="28" width="54" height="26" fill="#ece9f4"/>
				<rect x="10" y="34" width="8" height="12" fill="#f8f5ff"/>
				<rect x="20" y="24" width="12" height="8" fill="#f8f5ff"/>
				<rect x="34" y="22" width="12" height="8" fill="#f8f5ff"/>
				<rect x="48" y="24" width="12" height="8" fill="#f8f5ff"/>
				<rect x="62" y="33" width="22" height="18" fill="#32344d"/>
				<rect x="64" y="28" width="6" height="6" fill="#32344d"/>
				<rect x="78" y="28" width="6" height="6" fill="#32344d"/>
				<rect x="67" y="38" width="3" height="3" fill="#fdf8ea"/>
				<rect x="76" y="38" width="3" height="3" fill="#fdf8ea"/>
				<rect x="72" y="44" width="3" height="2" fill="#e7afb3"/>
				<rect x="22" y="54" width="6" height="14" fill="#2b2c40"/>
				<rect x="36" y="54" width="6" height="14" fill="#2b2c40"/>
				<rect x="50" y="54" width="6" height="14" fill="#2b2c40"/>
				<rect x="64" y="54" width="6" height="14" fill="#2b2c40"/>
			</svg>`;
		}

		// Default: fluffy / cute
		return `
		<svg viewBox="0 0 120 76" aria-hidden="true" focusable="false">
			<ellipse cx="41" cy="39" rx="30" ry="20" fill="#ece9f6"/>
			<circle cx="19" cy="36" r="10" fill="#fbf9ff"/>
			<circle cx="28" cy="28" r="10" fill="#fbf9ff"/>
			<circle cx="40" cy="25" r="11" fill="#fbf9ff"/>
			<circle cx="53" cy="28" r="11" fill="#fbf9ff"/>
			<circle cx="62" cy="36" r="10" fill="#fbf9ff"/>

			<ellipse cx="78" cy="40" rx="16" ry="13" fill="#31334d"/>
			<polygon points="68,33 72,24 77,33" fill="#31334d"/>
			<polygon points="86,33 92,24 96,34" fill="#31334d"/>
			<ellipse cx="73" cy="40" rx="2.2" ry="2.2" fill="#fdf8ea"/>
			<ellipse cx="82" cy="40" rx="2.2" ry="2.2" fill="#fdf8ea"/>
			<ellipse cx="78" cy="45" rx="3" ry="2.2" fill="#f2b9bc"/>

			<rect x="24" y="56" width="6" height="14" fill="#2a2b40"/>
			<rect x="38" y="56" width="6" height="14" fill="#2a2b40"/>
			<rect x="52" y="56" width="6" height="14" fill="#2a2b40"/>
			<rect x="66" y="56" width="6" height="14" fill="#2a2b40"/>
		</svg>`;
	}

	return `
	<svg viewBox="0 0 74 52" aria-hidden="true" focusable="false">
		<polygon points="13,30 26,18 46,18 58,26 56,37 41,40 21,40" fill="#d97237"/>
		<polygon points="24,18 31,10 37,18" fill="#f49b56"/>
		<polygon points="42,18 48,11 52,18" fill="#f49b56"/>
		<polygon points="53,29 66,26 60,33" fill="#f8f3ea"/>
		<polygon points="12,33 7,29 11,27" fill="#f8f3ea"/>
		<rect x="20" y="40" width="4" height="9" fill="#2a1f2c"/>
		<rect x="31" y="40" width="4" height="9" fill="#2a1f2c"/>
		<rect x="43" y="39" width="4" height="10" fill="#2a1f2c"/>
		<circle cx="56" cy="29" r="1.2" fill="#1f2030"/>
	</svg>`;
}

export function initCritterSystem({ gameEl, eventBus, groundOffsetPercent = 11 }) {
	let fxEl = gameEl.querySelector('#critter-fx');
	if (!fxEl) {
		fxEl = document.createElement('div');
		fxEl.id = 'critter-fx';
		fxEl.className = 'critter-fx';
		gameEl.appendChild(fxEl);
	}

	let nextSpawnAt = performance.now() + rand(8000, 26000);
	let critterEl = null;
	let bubbleEl = null;
	let bubbleTextEl = null;
	let revealBound = false;
	let revealedPunchline = false;
	let activeFetch = null;
	let sessionId = 0;
	let jokeActionEl = null;
	let spaceRevealHandler = null;
	let lastJokeKey = '';
	let lastSheepVariant = 'storybook';
	let idleDismissTimerId = 0;
	const timers = createTimerRegistry();

	function scheduleNext(now) {
		nextSpawnAt = now + rand(45000, 130000);
	}

	function jokeKey(joke) {
		if (!joke) return '';
		if (typeof joke.id === 'number') return `id:${joke.id}`;
		return `${joke.setup}::${joke.punchline}`;
	}

	function pickFallbackDifferentFrom(lastKey) {
		if (FALLBACK_JOKES.length <= 1) return FALLBACK_JOKES[0];
		const pool = FALLBACK_JOKES.filter((j) => jokeKey(j) !== lastKey);
		const list = pool.length ? pool : FALLBACK_JOKES;
		return list[Math.floor(Math.random() * list.length)];
	}

	async function fetchRandomJoke(signal) {
		let attempts = 0;
		while (attempts < 4) {
			attempts += 1;
			try {
				const res = await fetch('https://official-joke-api.appspot.com/random_joke', { cache: 'no-store', signal });
				if (!res.ok) throw new Error('Joke request failed');
				const data = await res.json();
				if (typeof data?.setup === 'string' && typeof data?.punchline === 'string') {
					const joke = { setup: data.setup, punchline: data.punchline, id: data.id };
					if (jokeKey(joke) !== lastJokeKey || attempts >= 4) return joke;
				}
			} catch (e) {
				break;
			}
		}
		return pickFallbackDifferentFrom(lastJokeKey);
	}

	function typeText(text, done) {
		if (!bubbleTextEl) return;
		bubbleTextEl.textContent = '';
		let i = 0;
		const localSession = sessionId;

		const step = () => {
			if (!bubbleTextEl || localSession !== sessionId) return;
			bubbleTextEl.textContent = text.slice(0, i);
			i += 1;
			if (i <= text.length) {
				timers.after(rand(14, 30), step);
				return;
			}
			done?.();
		};
		step();
	}

	function clearBubble() {
		if (!bubbleEl) return;
		if (spaceRevealHandler) {
			window.removeEventListener('keydown', spaceRevealHandler);
			spaceRevealHandler = null;
		}
		bubbleEl.remove();
		bubbleEl = null;
		bubbleTextEl = null;
		jokeActionEl = null;
		revealBound = false;
		idleDismissTimerId = 0;
	}

	function teardownCritter() {
		timers.clearAll();
		sessionId += 1;
		if (activeFetch) {
			activeFetch.abort();
			activeFetch = null;
		}
		clearBubble();
		if (critterEl) {
			critterEl.classList.add('exiting');
			timers.after(700, () => {
				critterEl?.remove();
				critterEl = null;
				scheduleNext(performance.now());
			});
			return;
		}
		scheduleNext(performance.now());
	}

	function bindPunchlineReveal(joke) {
		if (!bubbleEl || !jokeActionEl || revealBound) return;
		revealBound = true;
		const reveal = () => {
			if (revealedPunchline) return;
			revealedPunchline = true;
			if (idleDismissTimerId) {
				timers.clear(idleDismissTimerId);
				idleDismissTimerId = 0;
			}
			const clickedAt = performance.now();
			jokeActionEl.disabled = true;
			jokeActionEl.textContent = '...';
			eventBus.emit('critter:punchlineStart', joke);
			typeText(joke.punchline, () => {
				if (!jokeActionEl) return;
				jokeActionEl.textContent = 'done';
				eventBus.emit('critter:punchlineComplete', joke);
				const elapsedSinceClick = performance.now() - clickedAt;
				const remaining = Math.max(0, 4000 - elapsedSinceClick);
				timers.after(remaining, teardownCritter);
			});
		};

		jokeActionEl.addEventListener('click', reveal);
		spaceRevealHandler = (evt) => {
			if (evt.code !== 'Space') return;
			const tag = evt.target?.tagName;
			if (tag && ['INPUT', 'TEXTAREA', 'SELECT', 'BUTTON'].includes(tag)) return;
			evt.preventDefault();
			reveal();
		};
		window.addEventListener('keydown', spaceRevealHandler);
	}

	async function tellJoke() {
		if (!critterEl) return;
		critterEl.classList.add('active');
		revealedPunchline = false;
		sessionId += 1;
		const localSession = sessionId;

		if (activeFetch) activeFetch.abort();
		activeFetch = new AbortController();
		const joke = await fetchRandomJoke(activeFetch.signal);
		if (!critterEl || localSession !== sessionId) return;
		lastJokeKey = jokeKey(joke);

		clearBubble();
		bubbleEl = document.createElement('div');
		bubbleEl.className = 'joke-bubble';
		bubbleEl.setAttribute('role', 'dialog');
		bubbleEl.setAttribute('aria-label', 'Sheep joke bubble');
		bubbleEl.innerHTML = '<span class="joke-line" aria-live="polite"></span><div class="joke-actions"><button type="button" class="joke-action" disabled>hear punchline</button><span class="joke-space">or hit spacebar</span></div>';
		bubbleTextEl = bubbleEl.querySelector('.joke-line');
		jokeActionEl = bubbleEl.querySelector('.joke-action');
		fxEl.appendChild(bubbleEl);

		eventBus.emit('critter:setupStart', joke);
		typeText(joke.setup, () => {
			if (!bubbleEl || !jokeActionEl) return;
			jokeActionEl.disabled = false;
			bindPunchlineReveal(joke);
			jokeActionEl.focus({ preventScroll: true });
			eventBus.emit('critter:setupComplete', joke);
		});

		idleDismissTimerId = timers.after(24000, teardownCritter);
	}

	function spawn() {
		if (critterEl) return;
		const kind = 'sheep';
		const variant = 'storybook';
		lastSheepVariant = variant;
		critterEl = document.createElement('button');
		critterEl.type = 'button';
		critterEl.className = `joke-critter ${kind} sheep-${variant} entering`;
		critterEl.setAttribute('aria-label', kind === 'fox' ? 'Fox with a joke' : 'Sheep with a joke');
		critterEl.style.bottom = `calc(${groundOffsetPercent}% + 3px)`;
		critterEl.innerHTML = createCritterSvg(kind, variant);
		fxEl.appendChild(critterEl);
		critterEl.addEventListener('animationend', (evt) => {
			if (evt.animationName !== 'cmp-critter-walk-in') return;
			critterEl?.classList.remove('entering');
			critterEl?.classList.add('active');
			tellJoke();
		}, { once: true });
		eventBus.emit('critter:spawn', { kind, variant });
	}

	function update(now) {
		if (!nextSpawnAt || now < nextSpawnAt) return;
		if (critterEl || bubbleEl) return;
		nextSpawnAt = 0;
		spawn();
	}

	function destroy() {
		timers.clearAll();
		sessionId += 1;
		if (activeFetch) activeFetch.abort();
		clearBubble();
		critterEl?.remove();
		critterEl = null;
		fxEl?.remove();
		fxEl = null;
	}

	return { update, destroy, spawnNow: spawn };
}
