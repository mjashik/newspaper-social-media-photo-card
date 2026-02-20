jQuery(document).ready(function ($) {
    // --- Upload Button Logic (Existing) ---
    $('.mjashik-upload-button').click(function (e) {
        e.preventDefault();
        var button = $(this);
        var custom_uploader = wp.media({
            title: 'Select Image',
            library: {
                type: 'image'
            },
            button: {
                text: 'Use this image'
            },
            multiple: false
        }).on('select', function () {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            button.prev('input').val(attachment.url);

            // Show preview if exists
            var previewId = button.parent().find('.mjashik-image-preview');
            previewId.html('<img src="' + attachment.url + '" style="max-width: 300px; max-height: 200px; margin-top: 10px; border: 1px solid #ddd; padding: 5px;">');

        }).open();
    });

    // --- Photo Card Generation Logic (Admin Side) ---
    var $btn = $('#mjashik-download-card-btn');
    var $loading = $('#mjashik-card-loading');

    if ($btn.length) {
        $btn.on('click', function (e) {
            e.preventDefault();

            var originalText = $btn.html();
            $btn.addClass('disabled').prop('disabled', true);
            $btn.html('<span class="dashicons dashicons-update"></span> Generating...');
            $loading.show();

            // Find the capture element hidden in admin footer
            var element = document.querySelector("#npc-card-capture");

            // If the element doesn't look fully populated (e.g. title is missing because it's a draft), 
            // we might want to update it from JS fields?
            // For now, let's assume the hidden card relies on SAVED post data.

            if (!element) {
                alert('Error: Photo Card Template not found. Please reload pages.');
                $loading.hide();
                $btn.removeClass('disabled').prop('disabled', false).html(originalText);
                return;
            }

            // --- Convert SVG images to PNG before html2canvas (SVG not supported) ---
            function convertSvgsToPng(container) {
                var svgImgs = container.querySelectorAll('img');
                var promises = [];

                svgImgs.forEach(function (img) {
                    if (!img.src || !img.src.match(/\.svg/i)) return;
                    if (!img.complete || img.naturalWidth === 0) return;

                    var promise = new Promise(function (resolve) {
                        // Use rendered (CSS-constrained) dimensions for exact size match
                        var renderW = img.offsetWidth || img.naturalWidth;
                        var renderH = img.offsetHeight || img.naturalHeight;
                        var scale = 4; // 4x for crisp output (html2canvas also scales 2x)
                        var canvas = document.createElement('canvas');
                        canvas.width = renderW * scale;
                        canvas.height = renderH * scale;
                        var ctx = canvas.getContext('2d');
                        ctx.scale(scale, scale);
                        try {
                            ctx.drawImage(img, 0, 0, renderW, renderH);
                            var pngDataUrl = canvas.toDataURL('image/png');
                            img._originalSrc = img.src;
                            img._originalW = img.style.width;
                            img._originalH = img.style.height;
                            // Set explicit pixel dimensions + PNG src
                            img.style.width = renderW + 'px';
                            img.style.height = renderH + 'px';
                            img.src = pngDataUrl;
                        } catch (e) {
                            console.warn('SVG convert failed:', e);
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
                        img.src = img._originalSrc;
                        img.style.width = img._originalW || '';
                        img.style.height = img._originalH || '';
                        delete img._originalSrc;
                        delete img._originalW;
                        delete img._originalH;
                    }
                });
            }

            // Convert SVGs first, then capture
            convertSvgsToPng(element).then(function () {
                return html2canvas(element, {
                    useCORS: true,
                    scale: 2,
                    backgroundColor: '#ffffff',
                    allowTaint: true,
                    logging: false
                });
            }).then(function (canvas) {
                // Restore original SVG srcs
                restoreSvgSrcs(element);

                // Trigger download
                var link = document.createElement('a');
                link.download = 'news-card-' + (mjashikNPC.post_id || 'image') + '.png';
                link.href = canvas.toDataURL("image/png");
                link.click();

                // Reset UI
                $loading.hide();
                $btn.removeClass('disabled').prop('disabled', false).html(originalText);
            }).catch(function (error) {
                restoreSvgSrcs(element);
                console.error('Photo Card Gen Error:', error);
                alert('Error generating card. Check console for details.');
                $loading.hide();
                $btn.removeClass('disabled').prop('disabled', false).html(originalText);
            });
        });
    }
});
