<?php
/**
 * WordPress Font Test
 * Add this to your theme's functions.php temporarily or run via WP-CLI
 */

add_action('init', function() {
    if (isset($_GET['test_npc_font'])) {
        $base_dir = WP_PLUGIN_DIR . '/news-photo-card';
        $font = $base_dir . '/assets/fonts/NotoSansBengali-Regular.ttf';
        
        header('Content-Type: text/plain; charset=utf-8');
        
        echo "=== News Photo Card Font Test ===\n\n";
        echo "Plugin Dir: " . $base_dir . "\n";
        echo "Font Path: " . $font . "\n";
        echo "Font Exists: " . (file_exists($font) ? "YES ✓" : "NO ✗") . "\n\n";
        
        if (file_exists($font)) {
            // Test GD rendering
            $img = imagecreatetruecolor(400, 100);
            $bg = imagecolorallocate($img, 255, 255, 255);
            $text_color = imagecolorallocate($img, 0, 0, 0);
            imagefill($img, 0, 0, $bg);
            
            $bengali = "সাকিবিকে দলে বর্জনের";
            $result = imagettftext($img, 20, 0, 10, 50, $text_color, $font, $bengali);
            
            echo "GD Render Test: " . ($result !== false ? "SUCCESS ✓" : "FAILED ✗") . "\n";
            
            if ($result) {
                echo "Bounding Box: " . print_r($result, true) . "\n";
            }
            
            imagedestroy($img);
        }
        
        // Check Imagick
        echo "\n=== Imagick Status ===\n";
        echo "Extension Loaded: " . (extension_loaded('imagick') ? "YES ✓" : "NO ✗") . "\n";
        echo "Class Exists: " . (class_exists('Imagick') ? "YES ✓" : "NO ✗") . "\n";
        
        exit;
    }
});

// Usage: Visit http://yoursite.com/?test_npc_font
