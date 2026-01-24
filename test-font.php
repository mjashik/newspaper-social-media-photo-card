<?php
// Font Path Test Script
// Run this to check if font is loading correctly

$base_dir = dirname(__FILE__);
echo "Plugin Directory: " . $base_dir . "\n\n";

$fonts = array(
    $base_dir . '/assets/fonts/NotoSansBengali-Bold.ttf',
    $base_dir . '/assets/fonts/NotoSansBengali-Regular.ttf',
);

foreach ($fonts as $font) {
    echo "Checking: " . $font . "\n";
    echo "Exists: " . (file_exists($font) ? "YES" : "NO") . "\n";
    
    if (file_exists($font)) {
        echo "File Size: " . filesize($font) . " bytes\n";
        
        // Test if GD can read it
        if (function_exists('imagettfbbox')) {
            $test = @imagettfbbox(20, 0, $font, 'Test বাংলা');
            echo "GD Can Read: " . ($test !== false ? "YES" : "NO") . "\n";
        }
    }
    echo "\n";
}

// Test Bengali text rendering
echo "=== Bengali Text Test ===\n";
$bengali = "সাকিবিকে দলে বর্জনের নাটকীয় সাদিধানুত";
echo "Original: " . $bengali . "\n";
echo "UTF-8 Check: " . (mb_check_encoding($bengali, 'UTF-8') ? "VALID" : "INVALID") . "\n";

if (class_exists('Normalizer')) {
    $normalized = Normalizer::normalize($bengali, Normalizer::FORM_C);
    echo "Normalized: " . $normalized . "\n";
    echo "Same as original: " . ($normalized === $bengali ? "YES" : "NO") . "\n";
}
