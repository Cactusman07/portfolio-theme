/**
 * Intro — the Kia-ora bubble and walk-prompt that fire on first load.
 *
 * Two beats:
 *  1. 600ms in — speech bubble says "Kia ora! 👋" for 2s.
 *  2. 4000ms in — bubble updates with a hint about how to walk; copy
 *     branches based on whether the device is touch-primary.
 */

export function runIntro() {
	const cactuar = document.getElementById('cactuar');
	const bubble  = document.getElementById('cactuar-bubble');
	if (!cactuar || !bubble) return;

	const show = (html, ms = 3500) => {
		bubble.innerHTML = html;
		bubble.classList.add('show');
		if (ms) setTimeout(() => bubble.classList.remove('show'), ms);
	};

	window.addEventListener('load', () => {
		setTimeout(() => {
			show('Kia ora! <span style="color: var(--rust);">👋</span>', 3000);
		}, 600);

		setTimeout(() => {
			const isTouch = matchMedia('(hover: none) and (pointer: coarse)').matches;
			const hint = isTouch
				? 'Tap <span class="kbd">◀</span> <span class="kbd">▶</span> to walk →'
				: 'Use <span class="kbd">A</span> <span class="kbd">D</span> or <span class="kbd">←</span><span class="kbd">→</span> to explore →';
			show(hint, 4500);
		}, 4000);
	});
}
