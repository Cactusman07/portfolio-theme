/**
 * Mesh — cursor-following particle constellation.
 *
 * 160 ambient stars drift slowly across the upper half of the game window.
 * When the cursor enters MESH_RADIUS, nearby particles "connect" with the
 * cursor and with each other, drawing thin gold lines. Re-creates the
 * signature interaction from the live sammuir.co.nz site.
 *
 * Devicemark pixel density scales internally so the canvas stays sharp on
 * retina screens without bloating the particle count.
 */

const PARTICLE_COUNT = 160;
const MESH_RADIUS    = 160;
const CONNECT_DIST   = 110;
const STAR_COLOR     = 'rgba(244, 184, 112, 0.5)';
const LINE_COLOR     = (alpha) => `rgba(244, 184, 112, ${alpha})`;

let canvas;
let ctx;
let dpr = 1;
let particles = [];
let mouse = { x: -9999, y: -9999, active: false };

function resize() {
	if (!canvas || !ctx) return;
	const width = canvas.clientWidth;
	const height = canvas.clientHeight;
	if (!width || !height) return;
	dpr = Math.min(2, window.devicePixelRatio || 1);
	canvas.width = Math.floor(width * dpr);
	canvas.height = Math.floor(height * dpr);
	ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
}

function makeParticles() {
	const width = canvas.clientWidth;
	const height = canvas.clientHeight;
	if (!width || !height) {
		particles = [];
		return;
	}
	particles = [];
	for (let i = 0; i < PARTICLE_COUNT; i++) {
		particles.push({
			x:  Math.random() * width,
			y:  Math.random() * height * 0.7,
			vx: (Math.random() - 0.5) * 0.15,
			vy: (Math.random() - 0.5) * 0.1,
			size: Math.random() < 0.3 ? 2 : 1,
		});
	}
}

function loop() {
	if (!ctx || !canvas) return;
	const width = canvas.clientWidth;
	const height = canvas.clientHeight;
	if (!width || !height) {
		requestAnimationFrame(loop);
		return;
	}
	ctx.clearRect(0, 0, width, height);

	// Move and draw particles.
	ctx.fillStyle = STAR_COLOR;
	for (const p of particles) {
		p.x += p.vx;
		p.y += p.vy;
		if (p.x < 0) p.x = width;
		if (p.x > width) p.x = 0;
		if (p.y < 0) p.y = height * 0.7;
		if (p.y > height * 0.7) p.y = 0;
		ctx.fillRect(Math.floor(p.x), Math.floor(p.y), p.size, p.size);
	}

	// Connections (mouse → particles, particles → particles).
	if (mouse.active) {
		const nearby = [];
		for (const p of particles) {
			const dx = mouse.x - p.x;
			const dy = mouse.y - p.y;
			const d  = Math.sqrt(dx * dx + dy * dy);
			if (d < MESH_RADIUS) nearby.push({ p, d });
		}

		ctx.lineWidth = 1;
		// Cursor → particle.
		for (const { p, d } of nearby) {
			const alpha = (1 - d / MESH_RADIUS) * 0.55;
			ctx.strokeStyle = LINE_COLOR(alpha);
			ctx.beginPath();
			ctx.moveTo(mouse.x, mouse.y);
			ctx.lineTo(p.x, p.y);
			ctx.stroke();
		}
		// Particle ↔ particle.
		for (let i = 0; i < nearby.length; i++) {
			for (let j = i + 1; j < nearby.length; j++) {
				const a = nearby[i].p;
				const b = nearby[j].p;
				const dx = a.x - b.x;
				const dy = a.y - b.y;
				const d  = Math.sqrt(dx * dx + dy * dy);
				if (d < CONNECT_DIST) {
					const alpha = (1 - d / CONNECT_DIST) * 0.25;
					ctx.strokeStyle = LINE_COLOR(alpha);
					ctx.beginPath();
					ctx.moveTo(a.x, a.y);
					ctx.lineTo(b.x, b.y);
					ctx.stroke();
				}
			}
		}
	}

	requestAnimationFrame(loop);
}

export function initMesh() {
	canvas = document.getElementById('mesh-canvas');
	if (!canvas) return;
	ctx = canvas.getContext('2d');

	resize();
	makeParticles();

	window.addEventListener('resize', () => {
		resize();
		makeParticles();
	});

	document.addEventListener('mousemove', (e) => {
		const rect = canvas.getBoundingClientRect();
		mouse.x = e.clientX - rect.left;
		mouse.y = e.clientY - rect.top;
		mouse.active = true;
	});
	document.addEventListener('mouseleave', () => { mouse.active = false; });

	requestAnimationFrame(loop);
}
