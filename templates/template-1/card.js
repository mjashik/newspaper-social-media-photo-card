/**
 * Template 1 — Card Generation Hooks
 *
 * Registers preprocessing/postprocessing hooks for html2canvas capture.
 * Template 1 has no special preprocessing — just pass-through.
 */
(function () {
    window.npcTemplateHooks = window.npcTemplateHooks || {};

    /**
     * preProcess(container) → Promise
     * Called before html2canvas capture. Use for canvas manipulations, image swaps, etc.
     */
    window.npcTemplateHooks.preProcess = function (container) {
        // Template 1: nothing to do
        return Promise.resolve();
    };

    /**
     * postProcess(container) → void
     * Called after html2canvas capture to restore any mutations made in preProcess.
     */
    window.npcTemplateHooks.postProcess = function (container) {
        // Template 1: nothing to restore
    };
})();
