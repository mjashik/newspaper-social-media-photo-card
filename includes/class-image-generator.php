<?php
/**
 * Image Generator Class
 * Handles the generation of photo cards
 */

if (!defined('ABSPATH')) {
    exit;
}

class MJASHIK_NPC_Image_Generator {
    
    /**
     * Generate photo card image
     */
    public static function mjashik_generate_card($post_id) {
        // Get post data
        $post = get_post($post_id);
        if (!$post) {
            return false;
        }
        
        // Get settings
        $logo_url = get_option('mjashik_npc_logo_url');
        $background_url = get_option('mjashik_npc_background_url');
        $font_color = get_option('mjashik_npc_font_color', '#ffffff');
        $date_format = get_option('mjashik_npc_date_format', 'd F Y');
        $title_font_size = get_option('mjashik_npc_title_font_size', 32);
        $date_font_size = get_option('mjashik_npc_date_font_size', 20);
        $website_url = get_option('mjashik_npc_website_url', '');
        
        // Get post thumbnail
        $thumbnail_id = get_post_thumbnail_id($post_id);
        $thumbnail_url = wp_get_attachment_url($thumbnail_id);
        
        // Create canvas
        $width = 800;
        $height = 800;
        $image = imagecreatetruecolor($width, $height);
        
        // Load background image
        if ($background_url) {
            $background = self::mjashik_load_image($background_url);
            if ($background) {
                imagecopyresampled($image, $background, 0, 0, 0, 0, $width, $height, imagesx($background), imagesy($background));
                imagedestroy($background);
            } else {
                error_log('MJASHIK NPC: Failed to load background from: ' . $background_url);
                // Use default background
                $bg_color = imagecolorallocate($image, 30, 30, 30);
                imagefill($image, 0, 0, $bg_color);
            }
        } else {
            // Default background color
            $bg_color = imagecolorallocate($image, 30, 30, 30);
            imagefill($image, 0, 0, $bg_color);
        }
        
        // Add semi-transparent overlay for better text readability (lighter overlay)
        $overlay = imagecolorallocatealpha($image, 0, 0, 0, 30);
        imagefilledrectangle($image, 0, 0, $width, $height, $overlay);
        
        // Convert hex color to RGB
        $rgb = self::mjashik_hex_to_rgb($font_color);
        $text_color = imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']);
        
        // Load logo with transparency support
        if ($logo_url) {
            $logo = self::mjashik_load_image($logo_url);
            if ($logo) {
                // Enable alpha blending for transparency
                imagealphablending($image, true);
                imagesavealpha($image, true);
                
                $logo_width = 150;
                $logo_height = (imagesy($logo) / imagesx($logo)) * $logo_width;
                $logo_x = ($width - $logo_width) / 2;
                $logo_y = 30;
                
                // Create temporary image for logo with transparency
                $logo_resized = imagecreatetruecolor($logo_width, $logo_height);
                imagealphablending($logo_resized, false);
                imagesavealpha($logo_resized, true);
                $transparent = imagecolorallocatealpha($logo_resized, 0, 0, 0, 127);
                imagefill($logo_resized, 0, 0, $transparent);
                imagealphablending($logo_resized, true);
                
                imagecopyresampled($logo_resized, $logo, 0, 0, 0, 0, $logo_width, $logo_height, imagesx($logo), imagesy($logo));
                imagecopy($image, $logo_resized, $logo_x, $logo_y, 0, 0, $logo_width, $logo_height);
                
                imagedestroy($logo);
                imagedestroy($logo_resized);
            } else {
                // Logo failed to load - add debug text
                error_log('MJASHIK NPC: Failed to load logo from: ' . $logo_url);
                
                // Add debug text on image
                $debug_color = imagecolorallocate($image, 255, 0, 0);
                $font_path = self::mjashik_get_font_path();
                if ($font_path && file_exists($font_path)) {
                    imagettftext($image, 20, 0, 350, 50, $debug_color, $font_path, 'LOGO FAILED');
                }
            }
        }
        
        // Load post thumbnail
        if ($thumbnail_url) {
            $photo = self::mjashik_load_image($thumbnail_url);
            if ($photo) {
                $photo_size = 300;
                $photo_x = ($width - $photo_size) / 2;
                $photo_y = 200;
                
                // Create circular mask
                $mask = imagecreatetruecolor($photo_size, $photo_size);
                $transparent = imagecolorallocatealpha($mask, 255, 255, 255, 127);
                imagefill($mask, 0, 0, $transparent);
                $white = imagecolorallocate($mask, 255, 255, 255);
                imagefilledellipse($mask, $photo_size/2, $photo_size/2, $photo_size, $photo_size, $white);
                
                // Resize photo
                $photo_resized = imagecreatetruecolor($photo_size, $photo_size);
                imagecopyresampled($photo_resized, $photo, 0, 0, 0, 0, $photo_size, $photo_size, imagesx($photo), imagesy($photo));
                
                // Apply mask
                imagecolortransparent($mask, $white);
                imagecopymerge($image, $photo_resized, $photo_x, $photo_y, 0, 0, $photo_size, $photo_size, 100);
                
                imagedestroy($photo);
                imagedestroy($photo_resized);
                imagedestroy($mask);
            }
        }
        
        // Add date
        $date_text = date_i18n($date_format, strtotime($post->post_date));
        $font_path = self::mjashik_get_font_path();
        
        if ($font_path && file_exists($font_path)) {
            // Add date
            $date_y = 530;
            self::mjashik_add_text_centered($image, $date_font_size, $date_y, $text_color, $font_path, $date_text, $width);
            
            // Add title (wrapped) - Normalize text first
            $title = $post->post_title;
            
            // Ensure proper UTF-8 encoding
            if (!mb_check_encoding($title, 'UTF-8')) {
                $title = mb_convert_encoding($title, 'UTF-8', 'auto');
            }
            
            // Normalize Unicode (NFC form for Bengali)
            if (class_exists('Normalizer')) {
                $title = Normalizer::normalize($title, Normalizer::FORM_C);
            }
            
            $title_y = 580;
            $max_width = $width - 100;
            self::mjashik_add_wrapped_text($image, $title_font_size, $title_y, $text_color, $font_path, $title, $max_width, $width);
            
            // Add website URL at bottom
            if (!empty($website_url)) {
                $url_y = 750;
                $url_font_size = 16;
                self::mjashik_add_text_centered($image, $url_font_size, $url_y, $text_color, $font_path, $website_url, $width);
            }
        }
        
        // Save image
        $upload_dir = wp_upload_dir();
        $filename = 'photo-card-' . $post_id . '-' . time() . '.jpg';
        $filepath = $upload_dir['path'] . '/' . $filename;
        
        imagejpeg($image, $filepath, 90);
        imagedestroy($image);
        
        return $upload_dir['url'] . '/' . $filename;
    }
    
    /**
     * Load image from URL or local path
     */
    private static function mjashik_load_image($url) {
        if (empty($url)) {
            return false;
        }
        
        // Method 1: Try WordPress uploads directory
        $upload_dir = wp_upload_dir();
        
        // Remove protocol and domain to get relative path
        $parsed_url = parse_url($url);
        if (isset($parsed_url['path'])) {
            // Try to find file in WordPress uploads
            $relative_path = $parsed_url['path'];
            
            // Check if it's in wp-content/uploads
            if (strpos($relative_path, '/wp-content/uploads/') !== false) {
                $file_path = ABSPATH . ltrim(substr($relative_path, strpos($relative_path, 'wp-content/')), '/');
                
                if (file_exists($file_path)) {
                    return self::mjashik_create_image_from_file($file_path);
                }
            }
            
            // Try upload basedir
            $filename = basename($url);
            $possible_paths = array(
                $upload_dir['basedir'] . '/' . $filename,
                $upload_dir['path'] . '/' . $filename,
            );
            
            foreach ($possible_paths as $path) {
                if (file_exists($path)) {
                    return self::mjashik_create_image_from_file($path);
                }
            }
        }
        
        // Method 2: Try direct file_get_contents
        $image_data = @file_get_contents($url);
        if ($image_data) {
            $image = @imagecreatefromstring($image_data);
            if ($image) {
                return $image;
            }
        }
        
        error_log('MJASHIK NPC: Failed to load image from: ' . $url);
        return false;
    }
    
    /**
     * Create image resource from file path
     */
    private static function mjashik_create_image_from_file($file_path) {
        if (!file_exists($file_path)) {
            return false;
        }
        
        $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                return @imagecreatefromjpeg($file_path);
            case 'png':
                return @imagecreatefrompng($file_path);
            case 'gif':
                return @imagecreatefromgif($file_path);
            case 'webp':
                return @imagecreatefromwebp($file_path);
            default:
                return @imagecreatefromstring(file_get_contents($file_path));
        }
    }
    
    /**
     * Convert hex color to RGB
     */
    private static function mjashik_hex_to_rgb($hex) {
        $hex = str_replace('#', '', $hex);
        
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        
        return array('r' => $r, 'g' => $g, 'b' => $b);
    }
    
    /**
     * Get font path for Bengali text
     */
    private static function mjashik_get_font_path() {
        // Try to use a Bengali-compatible font
        $font_paths = array(
            MJASHIK_NPC_PLUGIN_DIR . 'assets/fonts/NotoSansBengali-Regular.ttf',
            MJASHIK_NPC_PLUGIN_DIR . 'assets/fonts/SolaimanLipi.ttf',
            '/usr/share/fonts/truetype/noto/NotoSansBengali-Regular.ttf',
            'C:/Windows/Fonts/arial.ttf'
        );
        
        foreach ($font_paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        return false;
    }
    
    /**
     * Add centered text
     */
    private static function mjashik_add_text_centered($image, $font_size, $y, $color, $font_path, $text, $width) {
        $bbox = imagettfbbox($font_size, 0, $font_path, $text);
        $text_width = abs($bbox[4] - $bbox[0]);
        $x = ($width - $text_width) / 2;
        
        imagettftext($image, $font_size, 0, $x, $y, $color, $font_path, $text);
    }
    
    /**
     * Add wrapped text - Bengali safe (only breaks at spaces)
     */
    private static function mjashik_add_wrapped_text($image, $font_size, $y, $color, $font_path, $text, $max_width, $canvas_width) {
        $lines = array();
        $current_line = '';
        
        // Split by spaces to get words
        $words = explode(' ', $text);
        
        foreach ($words as $word) {
            // Skip empty words
            if (empty(trim($word))) {
                continue;
            }
            
            $test_line = empty($current_line) ? $word : $current_line . ' ' . $word;
            $bbox = imagettfbbox($font_size, 0, $font_path, $test_line);
            $test_width = abs($bbox[4] - $bbox[0]);
            
            if ($test_width > $max_width && !empty($current_line)) {
                // Line is full, save current line and start new one
                $lines[] = $current_line;
                $current_line = $word;
            } else {
                $current_line = $test_line;
            }
            
            // Stop if we have 3 lines already
            if (count($lines) >= 3) {
                break;
            }
        }
        
        // Add remaining text
        if (!empty($current_line) && count($lines) < 3) {
            $lines[] = $current_line;
        }
        
        // Render lines
        $line_height = $font_size + 10;
        foreach ($lines as $index => $line) {
            $line_y = $y + ($index * $line_height);
            self::mjashik_add_text_centered($image, $font_size, $line_y, $color, $font_path, $line, $canvas_width);
        }
    }
}
