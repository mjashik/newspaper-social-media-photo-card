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
            var previewId = button.attr('id') === 'mjashik_upload_logo_btn' ? '#mjashik_logo_preview' : '#mjashik_bg_preview';
            $(previewId).html('<img src="' + attachment.url + '" style="max-width: 300px; max-height: 200px; margin-top: 10px; border: 1px solid #ddd; padding: 5px;">');

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

            html2canvas(element, {
                useCORS: true,
                scale: 2,
                backgroundColor: null,
                allowTaint: true,
                logging: false
            }).then(function (canvas) {
                // Trigger download
                var link = document.createElement('a');
                link.download = 'news-card-' + (mjashikNPC.post_id || 'image') + '.jpg';
                link.href = canvas.toDataURL("image/jpeg", 0.9);
                link.click();

                // Reset UI
                $loading.hide();
                $btn.removeClass('disabled').prop('disabled', false).html(originalText);
            }).catch(function (error) {
                console.error('Photo Card Gen Error:', error);
                alert('Error generating card. Check console for details.');
                $loading.hide();
                $btn.removeClass('disabled').prop('disabled', false).html(originalText);
            });
        });
    }
});
