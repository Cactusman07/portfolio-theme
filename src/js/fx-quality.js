/**
 * Adaptive FX quality controller based on recent frame times.
 */
export function createFxQualityController() {
	const frameTimes = [];
	const MAX_SAMPLES = 45;
	let scale = 1;

	function reportFrame(dt) {
		frameTimes.push(dt);
		if (frameTimes.length > MAX_SAMPLES) frameTimes.shift();
		const avg = frameTimes.reduce((sum, v) => sum + v, 0) / frameTimes.length;

		if (avg > 30) scale = 0.55;
		else if (avg > 22) scale = 0.72;
		else if (avg > 18) scale = 0.86;
		else scale = 1;
	}

	function scaledCount(base, min = 1) {
		return Math.max(min, Math.round(base * scale));
	}

	return {
		reportFrame,
		scaledCount,
		getScale: () => scale,
	};
}
