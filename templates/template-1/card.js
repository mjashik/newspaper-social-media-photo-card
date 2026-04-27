/**
 * Template 1 — Card Generation Hooks
 *
 * Registers preprocessing/postprocessing hooks for html2canvas capture.
 * Template 1 has no special preprocessing — just pass-through.
 */
(function () {
    window.npcTemplateHooks = window.npcTemplateHooks || {};

    /* ──────────────────────────────────────────────
     * Logo shadow baking
     * html2canvas ignores CSS filter: drop-shadow().
     * We draw the logo onto a canvas with canvas
     * shadowBlur, create a new data URL, and swap
     * the <img src> before capture.
     * ────────────────────────────────────────────── */
    function bakeLogoShadow(container) {
        var logoImg = container.querySelector('#npc-logo-img');
        if (!logoImg || !logoImg.src) return Promise.resolve();
        if (!logoImg.complete || logoImg.naturalWidth === 0) return Promise.resolve();

        return new Promise(function (resolve) {
            var shadowColor = logoImg.getAttribute('data-shadow');
            if (!shadowColor || shadowColor === 'none' || shadowColor === '') return resolve();

            // Reliable dimensions: use offsetHeight (the CSS height:auto)
            // and compute width proportionally from naturalWidth/naturalHeight.
            var renderH = logoImg.offsetHeight;
            var renderW = logoImg.offsetWidth;
            if (!renderW && logoImg.naturalWidth && logoImg.naturalHeight) {
                renderW = Math.round(logoImg.naturalWidth * (renderH / logoImg.naturalHeight));
            }
            if (!renderW) renderW = 200;

            var scale    = 4;
            var dropBlur = 100; // strong glow on all 4 sides
            var pad      = 60;  // padding so shadow isn't clipped

            var canvas = document.createElement('canvas');
            canvas.width  = (renderW + pad * 2) * scale;
            canvas.height = (renderH + pad * 2) * scale;
            var ctx = canvas.getContext('2d');

            ctx.scale(scale, scale);
            ctx.shadowColor   = shadowColor;
            ctx.shadowBlur    = dropBlur;
            ctx.shadowOffsetX = 0; // symmetric — equal on all 4 sides
            ctx.shadowOffsetY = 0;

            try {
                // Draw 3× to stack/intensify the glow
                ctx.drawImage(logoImg, pad, pad, renderW, renderH);
                ctx.drawImage(logoImg, pad, pad, renderW, renderH);
                ctx.drawImage(logoImg, pad, pad, renderW, renderH);

                var pngDataUrl = canvas.toDataURL('image/png');

                // Save originals
                logoImg._shadowOriginalSrc      = logoImg.src;
                logoImg._shadowOriginalW        = logoImg.style.width;
                logoImg._shadowOriginalH        = logoImg.style.height;
                logoImg._shadowOriginalMargin   = logoImg.style.margin;
                logoImg._shadowOriginalMaxWidth = logoImg.style.maxWidth;

                // Swap: expand image to include shadow padding on all 4 sides
                logoImg.style.maxWidth = 'none';
                logoImg.style.width    = (renderW + pad * 2) + 'px';
                logoImg.style.height   = (renderH + pad * 2) + 'px';
                logoImg.style.margin   = '-' + pad + 'px'; // bleed equally all 4 sides
                logoImg.src = pngDataUrl;

            } catch (e) {
                console.warn('[T1] Logo shadow bake failed:', e);
            }
            setTimeout(resolve, 150);
        });
    }

    function restoreLogoShadow(container) {
        var logoImg = container.querySelector('#npc-logo-img');
        if (logoImg && logoImg._shadowOriginalSrc) {
            logoImg.src          = logoImg._shadowOriginalSrc;
            logoImg.style.width  = logoImg._shadowOriginalW  || '';
            logoImg.style.height = logoImg._shadowOriginalH  || '';
            if (typeof logoImg._shadowOriginalMaxWidth !== 'undefined') {
                logoImg.style.maxWidth = logoImg._shadowOriginalMaxWidth;
            }
            if (logoImg._shadowOriginalMargin) {
                logoImg.style.margin = logoImg._shadowOriginalMargin;
            } else {
                logoImg.style.removeProperty('margin');
            }
            delete logoImg._shadowOriginalSrc;
            delete logoImg._shadowOriginalW;
            delete logoImg._shadowOriginalH;
            delete logoImg._shadowOriginalMargin;
            delete logoImg._shadowOriginalMaxWidth;
        }
    }

    /**
     * preProcess(container) → Promise
     * Called before html2canvas capture. Use for canvas manipulations, image swaps, etc.
     */
    window.npcTemplateHooks.preProcess = function (container) {
        return bakeLogoShadow(container);
    };

    /**
     * postProcess(container) → void
     * Called after html2canvas capture to restore any mutations made in preProcess.
     */
    window.npcTemplateHooks.postProcess = function (container) {
        restoreLogoShadow(container);
    };
})();
