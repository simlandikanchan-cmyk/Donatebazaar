/* ═══════════════════════════════════════════════════════════════════
   shared/dom.js — DOM query + event delegation helpers
   ═══════════════════════════════════════════════════════════════════ */

/** Query a single element (shorthand for querySelector). */
export function $(selector, scope = document) {
    return scope.querySelector(selector);
}

/** Query all matching elements as an array. */
export function $$(selector, scope = document) {
    return Array.from(scope.querySelectorAll(selector));
}

/** Attach a delegated listener for a CSS selector. */
export function delegate(selector, eventType, handler, scope = document) {
    scope.addEventListener(eventType, (event) => {
        const target = event.target.closest(selector);
        if (target) handler(event, target);
    });
}

/** Attach a plain listener (shorthand). */
export function on(element, eventType, handler, options) {
    if (!element) return;
    element.addEventListener(eventType, handler, options);
}