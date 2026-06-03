/**
 * Touch — D-pad and action-button bindings for touch / hybrid devices.
 *
 * Press-and-hold semantics: touchstart sets a flag, touchend clears it.
 * The game loop in world.js reads the touchState flags each tick.
 *
 * Hybrid devices (e.g. laptops with touch screens) get the d-pad shown the
 * first time a `touchstart` event fires, regardless of CSS media query.
 */

import { touchState, state, getNearLandmark } from './world.js';
import { openPanel, isPanelOpen } from './panels.js';

function bindHold(el, onDown, onUp) {
	if (!el) return;
	const down = (e) => { e.preventDefault(); el.classList.add('pressed'); onDown(); };
	const up   = (e) => { e.preventDefault(); el.classList.remove('pressed'); onUp(); };
	el.addEventListener('touchstart',  down, { passive: false });
	el.addEventListener('touchend',    up);
	el.addEventListener('touchcancel', up);
	el.addEventListener('mousedown',   down);
	el.addEventListener('mouseup',     up);
	el.addEventListener('mouseleave',  up);
}

export function initTouch() {
	const btnLeft   = document.getElementById('btn-left');
	const btnRight  = document.getElementById('btn-right');
	const btnAction = document.getElementById('btn-action');

	bindHold(btnLeft,  () => { touchState.left = true;  state.clickTarget = null; }, () => { touchState.left = false; });
	bindHold(btnRight, () => { touchState.right = true; state.clickTarget = null; }, () => { touchState.right = false; });

	if (btnAction) {
		btnAction.addEventListener('click', (e) => {
			e.preventDefault();
			const near = getNearLandmark();
			if (near && !isPanelOpen()) openPanel(near.section);
		});
	}

	// Hybrid device — first touch ever, show the d-pad and hide the kbd hint.
	window.addEventListener('touchstart', () => {
		const tc = document.getElementById('touch-controls');
		const hint = document.getElementById('controls-hint');
		if (tc) tc.classList.add('visible');
		if (hint) hint.classList.add('hidden');
	}, { once: true });
}
