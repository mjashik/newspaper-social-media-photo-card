jQuery(document).ready(function ($) {
    $('.mjashik-download-card-btn').on('click', function (e) {
        e.preventDefault();

        var button = $(this);
        var postId = button.data('post-id');
        var loadingDiv = $('.mjashik-card-loading');

        // Disable button
        button.addClass('disabled').prop('disabled', true);
        button.find('.mjashik-btn-text').text(mjashikNPC.generating_text);
        loadingDiv.show();

        // AJAX request
        $.ajax({
            url: mjashikNPC.ajax_url,
            type: 'POST',
            data: {
                action: 'mjashik_generate_photo_card',
                nonce: mjashikNPC.nonce,
                post_id: postId
            },
            success: function (response) {
                if (response.success) {
                    // Download the image
                    var link = document.createElement('a');
                    link.href = response.data.image_url;
                    link.download = 'photo-card-' + postId + '.jpg';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    // Reset button
                    button.removeClass('disabled').prop('disabled', false);
                    button.find('.mjashik-btn-text').text(mjashikNPC.download_text);
                    loadingDiv.hide();
                } else {
                    alert(response.data.message || mjashikNPC.error_text);
                    button.removeClass('disabled').prop('disabled', false);
                    button.find('.mjashik-btn-text').text(mjashikNPC.download_text);
                    loadingDiv.hide();
                }
            },
            error: function () {
                alert(mjashikNPC.error_text);
                button.removeClass('disabled').prop('disabled', false);
                button.find('.mjashik-btn-text').text(mjashikNPC.download_text);
                loadingDiv.hide();
            }
        });
    });
});
