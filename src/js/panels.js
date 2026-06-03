/**
 * Panels — open/close the content overlay.
 *
 * Each section ("about", "portfolio", "blog", "contact") has a server-rendered
 * <template id="tpl-{section}"> in the DOM. Opening clones the template
 * content into #panel-content, animates in, and runs post-open hooks
 * (stat-bar widths animate in, count-up percentages).
 *
 * Public: initPanels(), openPanel(section), closePanel(), isPanelOpen()
 */

import { playOpen, playClose } from './audio.js';

let overlay;
let content;
let opened = false;

export function isPanelOpen() {
	return opened;
}

export function openPanel(section) {
	if (!overlay || !content) return;
	const tpl = document.getElementById(`tpl-${section}`);
	if (!tpl) return;

	playOpen();

	// Clone template content into the panel container.
	content.innerHTML = '';
	content.appendChild(tpl.content.cloneNode(true));

	overlay.classList.add('open');
	overlay.setAttribute('aria-hidden', 'false');
	opened = true;

	// Stat-bar fills animate from 0 → data-fill over 800ms.
	requestAnimationFrame(() => {
		content.querySelectorAll('.stat-bar-fill').forEach((el) => {
			const target = parseInt(el.dataset.fill, 10) || 0;
			el.style.width = '0%';
			requestAnimationFrame(() => { el.style.width = `${target}%`; });
		});
		animateCountUps();
	});

	// Bind close buttons + overlay click.
	content.querySelectorAll('[data-close]').forEach((btn) => {
		btn.addEventListener('click', closePanel);
	});

	// Focus management.
	content.focus();
}

function animateCountUps() {
	content.querySelectorAll('.stat-pct').forEach((el) => {
		const target = parseInt(el.dataset.pct, 10) || 0;
		const duration = 800;
		const start = performance.now();
		const step = (now) => {
			const t = Math.min(1, (now - start) / duration);
			const eased = 1 - Math.pow(1 - t, 3); // easeOutCubic
			el.textContent = Math.round(target * eased);
			if (t < 1) requestAnimationFrame(step);
		};
		requestAnimationFrame(step);
	});
}

export function closePanel() {
	if (!overlay || !opened) return;
	playClose();
	overlay.classList.remove('open');
	overlay.setAttribute('aria-hidden', 'true');
	opened = false;
}

export function initPanels() {
	overlay = document.getElementById('panel-overlay');
	content = document.getElementById('panel-content');
	if (!overlay || !content) return;

	// Click-outside-to-close (only when clicking the dim layer itself).
	overlay.addEventListener('click', (e) => {
		if (e.target === overlay) closePanel();
	});

	// ESC handled in world.js, but bind a fallback for non-front pages.
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape' && opened) closePanel();
	});
}
