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
    // --- Social Links Repeater Logic ---
    var $socialContainer = $('#mjashik_npc_social_repeater');
    var $socialInput = $('#mjashik_npc_social_links');
    var $addSocialBtn = $('#mjashik_npc_add_social_link');
    var maxSocialLinks = 5;

    if ($socialInput.length && $socialContainer.length) {
        var existingLinks = [];
        try {
            existingLinks = JSON.parse($socialInput.val() || '[]');
        } catch (e) { }

        $socialContainer.empty();
        existingLinks.forEach(function (link) {
            addSocialRow(link.type, link.text, link.custom_img);
        });

        updateSocialData();

        $addSocialBtn.on('click', function (e) {
            e.preventDefault();
            if ($socialContainer.children().length < maxSocialLinks) {
                addSocialRow('facebook', '', '');
                updateSocialData();
            } else {
                alert('Maximum ' + maxSocialLinks + ' social links allowed.');
            }
        });

        // Handle changes dynamically on the container
        $socialContainer.on('input change', 'input, select', function () {
            var $row = $(this).closest('.mjashik-social-row');
            if ($(this).hasClass('social-type-select')) {
                if ($(this).val() === 'custom') {
                    $row.find('.social-custom-wrap').show();
                } else {
                    $row.find('.social-custom-wrap').hide();
                }
            }
            updateSocialData();
        });

        // Handle remove
        $socialContainer.on('click', '.remove-social-btn', function (e) {
            e.preventDefault();
            $(this).closest('.mjashik-social-row').remove();
            updateSocialData();
        });

        // Custom Image Upload in Repeater
        $socialContainer.on('click', '.mjashik-social-upload-btn', function (e) {
            e.preventDefault();
            var button = $(this);
            var uploader = wp.media({
                title: 'Select Custom Icon',
                library: { type: 'image' },
                button: { text: 'Use this image' },
                multiple: false
            }).on('select', function () {
                var attachment = uploader.state().get('selection').first().toJSON();
                button.prev('input').val(attachment.url).trigger('change');
            }).open();
        });
    }

    function addSocialRow(type, text, customImg) {
        customImg = customImg || '';
        text = text || '';
        var isCustom = (type === 'custom') ? 'style="display:flex; gap:10px; flex-wrap:wrap; margin-top:5px;"' : 'style="display:none; gap:10px; flex-wrap:wrap; margin-top:5px;"';

        var options = [
            { val: 'facebook', label: 'Facebook' },
            { val: 'youtube', label: 'YouTube' },
            { val: 'twitter', label: 'X (Twitter)' },
            { val: 'instagram', label: 'Instagram' },
            { val: 'linkedin', label: 'LinkedIn' },
            { val: 'custom', label: 'Custom Image' }
        ];

        var selectHtml = '<select class="social-type-select" style="width:140px;">';
        options.forEach(function (opt) {
            var sel = (opt.val === type) ? ' selected' : '';
            selectHtml += '<option value="' + opt.val + '"' + sel + '>' + opt.label + '</option>';
        });
        selectHtml += '</select>';

        var rowHtml = `
            <div class="mjashik-social-row" style="background:#f9f9f9; border:1px solid #e2e4e7; padding:10px; display:flex; flex-direction:column; gap:8px;">
                <div style="display:flex; gap:10px; align-items:center;">
                    <span style="cursor:move; color:#aaa;">&#9776;</span>
                    ${selectHtml}
                    <input type="text" class="social-text-input regular-text" value="${text.replace(/"/g, '&quot;')}" placeholder="e.g. Fb/jamunatv" style="flex:1;">
                    <button class="button remove-social-btn" style="color:#d63638; border-color:#d63638;">&times;</button>
                </div>
                <div class="social-custom-wrap" ${isCustom}>
                    <input type="text" class="social-custom-img-input regular-text" value="${customImg.replace(/"/g, '&quot;')}" placeholder="Image URL (square recommended)" style="flex:1;">
                    <button class="button mjashik-social-upload-btn">Upload/Select</button>
                </div>
            </div>
        `;
        $socialContainer.append(rowHtml);
    }

    function updateSocialData() {
        var data = [];
        $socialContainer.find('.mjashik-social-row').each(function () {
            data.push({
                type: $(this).find('.social-type-select').val(),
                text: $(this).find('.social-text-input').val(),
                custom_img: $(this).find('.social-custom-img-input').val()
            });
        });
        $socialInput.val(JSON.stringify(data));
    }

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

            function bakeLogoShadow(container) {
                var logoImg = container.querySelector('#npc-logo-img');
                if (!logoImg || !logoImg.src) return Promise.resolve();
                if (!logoImg.complete || logoImg.naturalWidth === 0) return Promise.resolve();

                return new Promise(function (resolve) {
                    var shadowColor = logoImg.getAttribute('data-shadow');
                    if (!shadowColor || shadowColor === 'none' || shadowColor === '') return resolve();

                    var renderW = logoImg.offsetWidth || logoImg.naturalWidth;
                    var renderH = logoImg.offsetHeight || logoImg.naturalHeight;

                    var scale = 4;
                    var dropY = 2;
                    var dropBlur = 6;
                    var pad = 15; // fixed pixel padding for shadow 

                    var canvas = document.createElement('canvas');
                    canvas.width = (renderW + pad * 2) * scale;
                    canvas.height = (renderH + pad * 2) * scale;
                    var ctx = canvas.getContext('2d');

                    ctx.scale(scale, scale);
                    ctx.shadowColor = shadowColor;
                    ctx.shadowBlur = dropBlur;
                    ctx.shadowOffsetX = 0;
                    ctx.shadowOffsetY = dropY;

                    try {
                        ctx.drawImage(logoImg, pad, pad, renderW, renderH);

                        var pngDataUrl = canvas.toDataURL('image/png');

                        logoImg._shadowOriginalSrc = logoImg.src;
                        logoImg._shadowOriginalW = logoImg.style.width;
                        logoImg._shadowOriginalH = logoImg.style.height;
                        logoImg._shadowOriginalMargin = logoImg.style.margin;
                        logoImg._shadowOriginalMaxWidth = logoImg.style.maxWidth;

                        logoImg.style.maxWidth = 'none';
                        logoImg.style.width = (renderW + pad * 2) + 'px';
                        logoImg.style.height = (renderH + pad * 2) + 'px';
                        logoImg.style.margin = '-' + pad + 'px 0 0 -' + pad + 'px';
                        logoImg.src = pngDataUrl;

                    } catch (e) {
                        console.warn('Logo shadow bake failed:', e);
                    }
                    setTimeout(resolve, 100);
                });
            }

            function restoreLogoShadow(container) {
                var logoImg = container.querySelector('#npc-logo-img');
                if (logoImg && logoImg._shadowOriginalSrc) {
                    logoImg.src = logoImg._shadowOriginalSrc;
                    logoImg.style.width = logoImg._shadowOriginalW || '';
                    logoImg.style.height = logoImg._shadowOriginalH || '';
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

            // Convert SVGs first, bake shadow, then capture
            convertSvgsToPng(element).then(function () {
                return bakeLogoShadow(element);
            }).then(function () {
                return html2canvas(element, {
                    useCORS: true,
                    scale: 2,
                    backgroundColor: '#ffffff',
                    allowTaint: true,
                    logging: false
                });
            }).then(function (canvas) {
                // Restore original setups
                restoreLogoShadow(element);
                restoreSvgSrcs(element);

                // Trigger download
                var link = document.createElement('a');
                link.download = 'news-card-' + (mjashik_npc_data.post_id || 'image') + '.png';
                link.href = canvas.toDataURL("image/png");
                link.click();

                // Reset UI
                $loading.hide();
                $btn.removeClass('disabled').prop('disabled', false).html(originalText);
            }).catch(function (error) {
                restoreLogoShadow(element);
                restoreSvgSrcs(element);
                console.error('Photo Card Gen Error:', error);
                alert('Error generating card. Check console for details.');
                $loading.hide();
                $btn.removeClass('disabled').prop('disabled', false).html(originalText);
            });
        });
    }
});
