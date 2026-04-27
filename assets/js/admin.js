jQuery(document).ready(function ($) {

    // ═══════════════════════════════════════════════
    // 1. Admin Upload Button
    // ═══════════════════════════════════════════════
    $('.mjashik-upload-button').click(function (e) {
        e.preventDefault();
        var button = $(this);
        var custom_uploader = wp.media({
            title: 'Select Image',
            library: { type: 'image' },
            button:  { text: 'Use this image' },
            multiple: false
        }).on('select', function () {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            button.prev('input').val(attachment.url);
            var previewId = button.parent().find('.mjashik-image-preview');
            previewId.html('<img src="' + attachment.url + '" style="max-width: 300px; max-height: 200px; margin-top: 10px; border: 1px solid #ddd; padding: 5px;">');
        }).open();
    });

    // ═══════════════════════════════════════════════
    // 2. Social Links Repeater
    // ═══════════════════════════════════════════════
    var $socialContainer = $('#mjashik_npc_social_repeater');
    var $socialInput     = $('#mjashik_npc_social_links');
    var $addSocialBtn    = $('#mjashik_npc_add_social_link');
    var maxSocialLinks   = 5;

    if ($socialInput.length && $socialContainer.length) {
        var existingLinks = [];
        try { existingLinks = JSON.parse($socialInput.val() || '[]'); } catch (e) {}

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

        $socialContainer.on('click', '.remove-social-btn', function (e) {
            e.preventDefault();
            $(this).closest('.mjashik-social-row').remove();
            updateSocialData();
        });

        $socialContainer.on('click', '.mjashik-social-upload-btn', function (e) {
            e.preventDefault();
            var button = $(this);
            var uploader = wp.media({
                title:    'Select Custom Icon',
                library:  { type: 'image' },
                button:   { text: 'Use this image' },
                multiple: false
            }).on('select', function () {
                var attachment = uploader.state().get('selection').first().toJSON();
                button.prev('input').val(attachment.url).trigger('change');
            }).open();
        });
    }

    function addSocialRow(type, text, customImg) {
        customImg = customImg || '';
        text      = text      || '';
        var isCustom = (type === 'custom')
            ? 'style="display:flex; gap:10px; flex-wrap:wrap; margin-top:5px;"'
            : 'style="display:none; gap:10px; flex-wrap:wrap; margin-top:5px;"';

        var options = [
            { val: 'facebook',  label: 'Facebook'      },
            { val: 'youtube',   label: 'YouTube'       },
            { val: 'twitter',   label: 'X (Twitter)'   },
            { val: 'instagram', label: 'Instagram'     },
            { val: 'linkedin',  label: 'LinkedIn'      },
            { val: 'custom',    label: 'Custom Image'  }
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
                type:       $(this).find('.social-type-select').val(),
                text:       $(this).find('.social-text-input').val(),
                custom_img: $(this).find('.social-custom-img-input').val()
            });
        });
        $socialInput.val(JSON.stringify(data));
    }

    // ═══════════════════════════════════════════════
    // 3. Photo Card Generation — Core
    //
    // Template-specific preprocessing is delegated to
    // window.npcTemplateHooks (set by each template's card.js):
    //
    //   npcTemplateHooks.preProcess(container)  → Promise
    //   npcTemplateHooks.postProcess(container) → void
    // ═══════════════════════════════════════════════
    var $btn     = $('#mjashik-download-card-btn');
    var $loading = $('#mjashik-card-loading');

    if ($btn.length) {
        $btn.on('click', function (e) {
            e.preventDefault();

            var originalText = $btn.html();
            $btn.addClass('disabled').prop('disabled', true);
            $btn.html('<span class="dashicons dashicons-update"></span> Generating...');
            $loading.show();

            var element = document.querySelector('#npc-card-capture');
            if (!element) {
                alert('Error: Photo Card Template not found. Please reload the page.');
                $loading.hide();
                $btn.removeClass('disabled').prop('disabled', false).html(originalText);
                return;
            }

            // Resolve template hooks (fall back to no-op if card.js not loaded)
            var hooks = window.npcTemplateHooks || {};
            var preProcess  = typeof hooks.preProcess  === 'function' ? hooks.preProcess  : function () { return Promise.resolve(); };
            var postProcess = typeof hooks.postProcess === 'function' ? hooks.postProcess : function () {};

            // Run: preProcess → html2canvas → postProcess → download
            preProcess(element)
                .then(function () {
                    return html2canvas(element, {
                        useCORS:         true,
                        scale:           2,
                        backgroundColor: '#ffffff',
                        allowTaint:      true,
                        logging:         false
                    });
                })
                .then(function (canvas) {
                    postProcess(element);

                    var link      = document.createElement('a');
                    link.download = 'news-card-' + (mjashik_npc_data.post_id || 'image') + '.png';
                    link.href     = canvas.toDataURL('image/png');
                    link.click();

                    $loading.hide();
                    $btn.removeClass('disabled').prop('disabled', false).html(originalText);
                })
                .catch(function (error) {
                    postProcess(element);
                    console.error('Photo Card Gen Error:', error);
                    alert('Error generating card. Check console for details.');
                    $loading.hide();
                    $btn.removeClass('disabled').prop('disabled', false).html(originalText);
                });
        });
    }
});
