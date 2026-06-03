/**
 * Tiny event bus for decoupled feature communication.
 */
export function createEventBus() {
	const listeners = new Map();

	return {
		on(event, handler) {
			if (!listeners.has(event)) listeners.set(event, new Set());
			listeners.get(event).add(handler);
			return () => listeners.get(event)?.delete(handler);
		},
		emit(event, payload) {
			const set = listeners.get(event);
			if (!set) return;
			for (const fn of set) {
				try {
					fn(payload);
				} catch (e) {
					// Keep event bus resilient to listener failures.
				}
			}
		},
		clear() {
			listeners.clear();
		},
	};
}
