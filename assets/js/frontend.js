jQuery(document).ready(function ($) {
    var $btn = $('#mjashik-download-card-btn');
    var $loading = $('#mjashik-card-loading');

    $btn.on('click', function (e) {
        e.preventDefault();

        $btn.hide();
        $loading.show();

        // Find the capture element
        var element = document.querySelector("#npc-card-capture");
        if (!element) {
            console.error('Capture element #npc-card-capture not found');
            alert('Error: Card template not found.');
            $loading.hide();
            $btn.show();
            return;
        }

        // We need to ensure the hidden container is technically "visible" to the renderer
        // but completely off-screen. The current CSS setup (left: -9999px) is good.
        // html2canvas works best if images are loaded. 
        // We'll give a tiny delay to ensure everything is settled if needed? No, usually fine.

        html2canvas(element, {
            useCORS: true, // Crucial for loading images from media library if cross-origin (unlikely on localhost but good practice)
            scale: 2,      // High resolution
            backgroundColor: null, // Transparent background if not set in CSS
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
            $btn.show();
        }).catch(function (error) {
            console.error('Photo Card Gen Error:', error);
            alert('Error generating card. Check console for details.');
            $loading.hide();
            $btn.show();
        });
    });
});
