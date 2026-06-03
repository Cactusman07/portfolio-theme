/**
 * Timer registry utility to avoid leaked setTimeout calls.
 */
export function createTimerRegistry() {
	const ids = new Set();

	return {
		after(ms, cb) {
			const id = window.setTimeout(() => {
				ids.delete(id);
				cb();
			}, ms);
			ids.add(id);
			return id;
		},
		clear(id) {
			if (!id) return;
			window.clearTimeout(id);
			ids.delete(id);
		},
		clearAll() {
			for (const id of ids) window.clearTimeout(id);
			ids.clear();
		},
	};
}
