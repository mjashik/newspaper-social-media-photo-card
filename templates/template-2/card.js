/**
 * Template 2 — Card Generation Hooks (Kalbela Style)
 *
 * Registers preprocessing/postprocessing hooks for html2canvas capture.
 * Template 2 needs:
 *   - SVG → PNG conversion (html2canvas can't render SVGs)
 *   - Logo drop-shadow baking via canvas API (html2canvas ignores CSS filter)
 */
(function () {
    window.npcTemplateHooks = window.npcTemplateHooks || {};

    /* ──────────────────────────────────────────────
     * SVG → PNG conversion
     * html2canvas cannot render <img src="*.svg">.
     * We draw each SVG to a canvas and swap the src
     * temporarily before capture.
     * ────────────────────────────────────────────── */
    function convertSvgsToPng(container) {
        var svgImgs = container.querySelectorAll('img');
        var promises = [];

        svgImgs.forEach(function (img) {
            if (!img.src || !img.src.match(/\.svg/i)) return;
            if (!img.complete || img.naturalWidth === 0) return;

            var promise = new Promise(function (resolve) {
                var renderW = img.offsetWidth  || img.naturalWidth;
                var renderH = img.offsetHeight || img.naturalHeight;
                var scale   = 4;
                var canvas  = document.createElement('canvas');
                canvas.width  = renderW * scale;
                canvas.height = renderH * scale;
                var ctx = canvas.getContext('2d');
                ctx.scale(scale, scale);
                try {
                    ctx.drawImage(img, 0, 0, renderW, renderH);
                    var pngDataUrl = canvas.toDataURL('image/png');
                    img._originalSrc = img.src;
                    img._originalW   = img.style.width;
                    img._originalH   = img.style.height;
                    img.style.width  = renderW + 'px';
                    img.style.height = renderH + 'px';
                    img.src = pngDataUrl;
                } catch (e) {
                    console.warn('[T2] SVG convert failed:', e);
                }
                setTimeout(resolve, 100);
            });
            promises.push(promise);
        });

        return Promise.all(promises);
    }

    function restoreSvgSrcs(container) {
        container.querySelectorAll('img').forEach(function (img) {
            if (img._originalSrc) {
                img.src          = img._originalSrc;
                img.style.width  = img._originalW || '';
                img.style.height = img._originalH || '';
                delete img._originalSrc;
                delete img._originalW;
                delete img._originalH;
            }
        });
    }

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

            // Reliable dimensions: use offsetHeight (the CSS height:45px)
            // and compute width proportionally from naturalWidth/naturalHeight.
            var renderH = logoImg.offsetHeight || 45;
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
                console.warn('[T2] Logo shadow bake failed:', e);
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

    /* ──────────────────────────────────────────────
     * Register hooks
     * ────────────────────────────────────────────── */

    /**
     * preProcess(container) → Promise
     * 1. Convert SVGs to PNG
     * 2. Bake logo shadow via canvas
     */
    window.npcTemplateHooks.preProcess = function (container) {
        return convertSvgsToPng(container).then(function () {
            return bakeLogoShadow(container);
        });
    };

    /**
     * postProcess(container) → void
     * Restore all mutations made during preProcess.
     */
    window.npcTemplateHooks.postProcess = function (container) {
        restoreLogoShadow(container);
        restoreSvgSrcs(container);
    };
})();
