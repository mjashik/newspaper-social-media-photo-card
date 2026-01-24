jQuery(document).ready(function($) {
    // Color picker
    $('.mjashik-color-picker').wpColorPicker();
    
    // Media uploader
    $('.mjashik-upload-button').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var targetInput = $('#' + button.data('target'));
        var preview = button.siblings('.mjashik-image-preview');
        
        var mediaUploader = wp.media({
            title: 'Select Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            targetInput.val(attachment.url);
            
            // Update preview
            preview.html('<img src="' + attachment.url + '" style="max-width: 200px; margin-top: 10px;" />');
        });
        
        mediaUploader.open();
    });
});
