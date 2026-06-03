/**
 * Cactusman Portfolio — JS entry.
 *
 * Vite uses this as the entry chunk; everything is statically imported so
 * dependencies are inlined into a single bundle. Initialised in order:
 *
 *  1. audio — primed on first gesture
 *  2. world — game state, tick loop, parallax
 *  3. mesh — cursor particle mesh
 *  4. panels — open/close + stat-bar animation
 *  5. touch — touch d-pad handlers
 *  6. intro — Kia-ora wave + bubble (only on front page)
 */

import '../scss/main.scss';

import { initWorld }   from './world.js';
import { initMesh }    from './mesh.js';
import { initAudio }   from './audio.js';
import { initPanels }  from './panels.js';
import { initTouch }   from './touch.js';
import { runIntro }    from './intro.js';

const onReady = (fn) => {
	if (document.readyState !== 'loading') {
		fn();
	} else {
		document.addEventListener('DOMContentLoaded', fn);
	}
};

onReady(() => {
	const world = document.querySelector('[data-cmp-world]');

	// Audio always available so the sound-toggle works site-wide.
	initAudio();

	// Panels exist on every template (the overlay is rendered on the front
	// page only, but the close-button bindings are harmless if missing).
	initPanels();

	// Front-page-only systems.
	if (world) {
		initWorld(world);
		initMesh();
		initTouch();
		runIntro();
	}

	// Custom cursor — front page only, follows the mouse.
	const cursor = document.getElementById('cursor');
	if (cursor) {
		let cursorX = 0;
		let cursorY = 0;
		let cursorFrameQueued = false;
		const paintCursor = () => {
			cursorFrameQueued = false;
			cursor.style.transform = `translate3d(${cursorX}px, ${cursorY}px, 0) translate(-50%, -50%)`;
		};

		document.addEventListener('mousemove', (e) => {
			cursorX = e.clientX;
			cursorY = e.clientY;
			if (!cursorFrameQueued) {
				cursorFrameQueued = true;
				requestAnimationFrame(paintCursor);
			}
		}, { passive: true });
		document.addEventListener('mouseover', (e) => {
			const tag = e.target.tagName;
			if (['A', 'BUTTON'].includes(tag) || e.target.closest('.landmark, .menu-item, .quest, .entry, .contact-card')) {
				cursor.classList.add('hover');
			}
		});
		document.addEventListener('mouseout', () => cursor.classList.remove('hover'));
	}
});
