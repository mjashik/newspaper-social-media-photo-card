<?php
/**
 * Image Generator Class
 * Handles the generation of photo cards
 * Fixes Bengali Font Rendering Issues
 */

if (!defined('ABSPATH')) {
    exit;
}

class MJASHIK_NPC_Image_Generator {
    
    /**
     * Generate photo card image
     */
    public static function mjashik_generate_card($post_id) {
        // ১. সার্ভারে Imagick আছে কিনা চেক করা (সেরা কোয়ালিটির জন্য)
        if (extension_loaded('imagick') && class_exists('Imagick')) {
            return self::mjashik_generate_card_imagick($post_id);
        } else {
            // ২. না থাকলে GD ব্যবহার করা (সাথে বাংলা ফিক্সার)
            return self::mjashik_generate_card_gd($post_id);
        }
    }

    /**
     * ==========================================
     * METHOD 1: IMAGICK (Best for Bengali)
     * ==========================================
     */
    private static function mjashik_generate_card_imagick($post_id) {
        $post = get_post($post_id);
        if (!$post) return false;

        $logo_url = get_option('mjashik_npc_logo_url');
        $background_url = get_option('mjashik_npc_background_url');
        $font_color = get_option('mjashik_npc_font_color', '#ffffff');
        $date_format = get_option('mjashik_npc_date_format', 'd F Y');
        $title_font_size = get_option('mjashik_npc_title_font_size', 32);
        $date_font_size = get_option('mjashik_npc_date_font_size', 20);
        $website_url = get_option('mjashik_npc_website_url', '');

        // ক্যানভাস তৈরি
        $image = new Imagick();
        $image->newImage(800, 800, new ImagickPixel('white'));
        $image->setImageFormat('jpg');

        // ব্যাকগ্রাউন্ড
        $bg_loaded = false;
        if ($background_url) {
            $bg_path = self::mjashik_get_local_path($background_url);
            if ($bg_path && file_exists($bg_path)) {
                try {
                    $background = new Imagick($bg_path);
                    $background->resizeImage(800, 800, Imagick::FILTER_LANCZOS, 1, true);
                    $geo = $background->getImageGeometry();
                    $x = ($geo['width'] - 800) / 2;
                    $y = ($geo['height'] - 800) / 2;
                    $background->cropImage(800, 800, $x, $y);
                    $image->compositeImage($background, Imagick::COMPOSITE_OVER, 0, 0);
                    $bg_loaded = true;
                } catch (Exception $e) {}
            }
        }
        
        if (!$bg_loaded) {
            $draw = new ImagickDraw();
            $draw->setFillColor('#333333');
            $draw->rectangle(0, 0, 800, 800);
            $image->drawImage($draw);
        }

        // ওভারলে (কালো ছায়া)
        $draw = new ImagickDraw();
        $draw->setFillColor(new ImagickPixel('rgba(0, 0, 0, 0.4)'));
        $draw->rectangle(0, 0, 800, 800);
        $image->drawImage($draw);

        // লোগো
        if ($logo_url) {
            $logo_path = self::mjashik_get_local_path($logo_url);
            if ($logo_path && file_exists($logo_path)) {
                try {
                    $logo = new Imagick($logo_path);
                    $logo->scaleImage(150, 0);
                    $geo = $logo->getImageGeometry();
                    $x = (800 - $geo['width']) / 2;
                    $image->compositeImage($logo, Imagick::COMPOSITE_OVER, $x, 30);
                } catch (Exception $e) {}
            }
        }

        // ফিচার্ড ইমেজ (গোল)
        $thumb_id = get_post_thumbnail_id($post_id);
        if ($thumb_id) {
            $thumb_path = get_attached_file($thumb_id);
            if ($thumb_path && file_exists($thumb_path)) {
                try {
                    $photo = new Imagick($thumb_path);
                    $photo->cropThumbnailImage(300, 300);
                    
                    // মাস্ক তৈরি
                    $mask = new Imagick();
                    $mask->newImage(300, 300, new ImagickPixel('transparent'));
                    $mask->setImageFormat('png');
                    $draw_mask = new ImagickDraw();
                    $draw_mask->setFillColor('white');
                    $draw_mask->circle(150, 150, 150, 298);
                    $mask->drawImage($draw_mask);
                    
                    $photo->compositeImage($mask, Imagick::COMPOSITE_DSTIN, 0, 0);
                    $image->compositeImage($photo, Imagick::COMPOSITE_OVER, 250, 200); // (800-300)/2 = 250
                    
                    $photo->clear(); $mask->clear();
                } catch (Exception $e) {}
            }
        }

        // ফন্ট সেটআপ
        $font_path = self::mjashik_get_font_path();
        $draw_text = new ImagickDraw();
        $draw_text->setFillColor($font_color);
        if ($font_path) {
            $draw_text->setFont($font_path);
        }
        $draw_text->setTextEncoding('UTF-8');

        // তারিখ
        $date_text = date_i18n($date_format, strtotime($post->post_date));
        self::mjashik_imagick_text_centered($image, $draw_text, $date_text, 530, $date_font_size);

        // টাইটেল (বাংলা ফিক্স সহ)
        $title = $post->post_title;
        // ইউনিকোড নরমালাইজেশন (গুরুত্বপূর্ণ)
        if (class_exists('Normalizer')) {
            $title = Normalizer::normalize($title, Normalizer::FORM_C);
        }
        
        // লাইন ব্রেক ঠিক করা
        self::mjashik_imagick_text_wrapped($image, $draw_text, $title, 580, $title_font_size, 700);

        // ওয়েবসাইট
        if (!empty($website_url)) {
            self::mjashik_imagick_text_centered($image, $draw_text, $website_url, 750, 16);
        }

        // সেভ করা
        $upload_dir = wp_upload_dir();
        $filename = 'card-' . $post_id . '-' . time() . '.jpg';
        $filepath = $upload_dir['path'] . '/' . $filename;
        
        $image->setImageCompressionQuality(90);
        $image->writeImage($filepath);
        $image->clear();

        return $upload_dir['url'] . '/' . $filename;
    }

    /**
     * ==========================================
     * METHOD 2: GD LIBRARY (Fallback)
     * ==========================================
     */
    private static function mjashik_generate_card_gd($post_id) {
        $post = get_post($post_id);
        if (!$post) return false;
        
        // সেটিংস
        $logo_url = get_option('mjashik_npc_logo_url');
        $background_url = get_option('mjashik_npc_background_url');
        $font_color = get_option('mjashik_npc_font_color', '#ffffff');
        $date_format = get_option('mjashik_npc_date_format', 'd F Y');
        $title_font_size = get_option('mjashik_npc_title_font_size', 32);
        
        // ক্যানভাস
        $width = 800;
        $height = 800;
        $image = imagecreatetruecolor($width, $height);
        
        // ব্যাকগ্রাউন্ড
        $bg_loaded = false;
        if ($background_url) {
            $background = self::mjashik_load_image_gd($background_url);
            if ($background) {
                imagecopyresampled($image, $background, 0, 0, 0, 0, $width, $height, imagesx($background), imagesy($background));
                imagedestroy($background);
                $bg_loaded = true;
            }
        }
        if (!$bg_loaded) {
            $col = imagecolorallocate($image, 50, 50, 50);
            imagefill($image, 0, 0, $col);
        }
        
        // ওভারলে
        $overlay = imagecolorallocatealpha($image, 0, 0, 0, 80); // ~60% transparent
        imagefilledrectangle($image, 0, 0, $width, $height, $overlay);
        
        // টেক্সট কালার
        $rgb = self::mjashik_hex_to_rgb($font_color);
        $text_color = imagecolorallocate($image, $rgb['r'], $rgb['g'], $rgb['b']);
        
        // লোগো
        if ($logo_url) {
            $logo = self::mjashik_load_image_gd($logo_url);
            if ($logo) {
                $lw = 150;
                $lh = (imagesy($logo)/imagesx($logo)) * $lw;
                $lx = ($width - $lw)/2;
                imagecopyresampled($image, $logo, $lx, 30, 0, 0, $lw, $lh, imagesx($logo), imagesy($logo));
                imagedestroy($logo);
            }
        }

        // ছবি (বৃত্তাকার)
        $tid = get_post_thumbnail_id($post_id);
        if ($tid) {
            $turl = wp_get_attachment_url($tid);
            $photo = self::mjashik_load_image_gd($turl);
            if ($photo) {
                $ps = 300; // Photo Size
                $px = ($width - $ps)/2;
                
                // ক্রপ এবং রিসাইজ
                $temp = imagecreatetruecolor($ps, $ps);
                imagecopyresampled($temp, $photo, 0, 0, 0, 0, $ps, $ps, imagesx($photo), imagesy($photo));
                
                // মাস্কিং (Circular Mask via Alpha)
                $final = imagecreatetruecolor($ps, $ps);
                imagealphablending($final, false);
                imagesavealpha($final, true);
                $trans = imagecolorallocatealpha($final, 0, 0, 0, 127);
                imagefill($final, 0, 0, $trans);
                
                $r = $ps / 2;
                for ($x=0; $x<$ps; $x++) {
                    for ($y=0; $y<$ps; $y++) {
                        if (((($x-$r)*($x-$r)) + (($y-$r)*($y-$r))) <= ($r*$r)) {
                            $c = imagecolorat($temp, $x, $y);
                            imagesetpixel($final, $x, $y, $c);
                        }
                    }
                }
                
                imagecopy($image, $final, $px, 200, 0, 0, $ps, $ps);
                imagedestroy($photo); imagedestroy($temp); imagedestroy($final);
            }
        }

        // ফন্ট চেক
        $font_path = self::mjashik_get_font_path();
        
        if ($font_path) {
            // তারিখ
            $date = date_i18n($date_format, strtotime($post->post_date));
            self::mjashik_gd_text_centered($image, 20, 530, $text_color, $font_path, $date, $width);
            
            // টাইটেল (GD স্পেশাল ফিক্স)
            $title = $post->post_title;
            // বাংলা টেক্সট ফিক্স (ই-কার সমস্যা সমাধানের চেষ্টা)
            $title = self::mjashik_fix_bangla_gd($title); 
            
            self::mjashik_gd_text_wrapped($image, $title_font_size, 580, $text_color, $font_path, $title, 700, $width);
            
            // ওয়েবসাইট
            $web = get_option('mjashik_npc_website_url', '');
            if ($web) {
                self::mjashik_gd_text_centered($image, 16, 750, $text_color, $font_path, $web, $width);
            }
        } else {
            // ফন্ট না পেলে ডিবাগ মেসেজ
            $red = imagecolorallocate($image, 255, 0, 0);
            imagestring($image, 5, 250, 400, "Error: Font file not found!", $red);
        }

        // ফাইল সেভ
        $upload = wp_upload_dir();
        $file = 'card-gd-' . $post_id . '-' . time() . '.jpg';
        $path = $upload['path'] . '/' . $file;
        imagejpeg($image, $path, 90);
        imagedestroy($image);
        
        return $upload['url'] . '/' . $file;
    }

    /**
     * ==========================================
     * HELPERS
     * ==========================================
     */

    // ফন্ট পাথ খুঁজে বের করার শক্তিশালী ফাংশন
    private static function mjashik_get_font_path() {
        // বর্তমান ফাইলের ডিরেক্টরি থেকে ফন্ট ফোল্ডারের সঠিক পথ বের করা
        $base_dir = dirname(dirname(__FILE__)); // goes up from 'includes' to plugin root
        
        $fonts = array(
            $base_dir . '/assets/fonts/NotoSansBengali-Bold.ttf',
            $base_dir . '/assets/fonts/NotoSansBengali-Regular.ttf',
            $base_dir . '/assets/fonts/SolaimanLipi.ttf'
        );

        foreach ($fonts as $font) {
            if (file_exists($font)) {
                return $font;
            }
        }
        
        return false;
    }

    // GD এর জন্য বাংলা টেক্সট ফিক্সার (Experimental)
    private static function mjashik_fix_bangla_gd($text) {
        // ইউনিকোড নরমালাইজেশন
        if (class_exists('Normalizer')) {
            $text = Normalizer::normalize($text, Normalizer::FORM_C);
        }
        // নোট: GD তে ই-কার (ি) সাধারণত বর্ণের পরে বসে যায়।
        // এটি ফিক্স করা খুব জটিল, তবে সাধারণ রেন্ডারিং এর জন্য আমরা টেক্সটকে অক্ষত রাখাই ভালো।
        // ভুলভাবে সোয়াপ করলে যুক্তবর্ণ আরও ভেঙ্গে যেতে পারে।
        return $text;
    }

    // Imagick Helpers
    private static function mjashik_imagick_text_centered($image, $draw, $text, $y, $size) {
        $draw->setFontSize($size);
        $metrics = $image->queryFontMetrics($draw, $text);
        $x = (800 - $metrics['textWidth']) / 2;
        $image->annotateImage($draw, $x, $y + $metrics['ascender'], 0, $text);
    }

    private static function mjashik_imagick_text_wrapped($image, $draw, $text, $y, $size, $max_width) {
        $draw->setFontSize($size);
        $words = explode(' ', $text);
        $lines = [];
        $curr = '';
        
        foreach ($words as $word) {
            if (trim($word) == '') continue;
            $test = $curr === '' ? $word : $curr . ' ' . $word;
            $m = $image->queryFontMetrics($draw, $test);
            if ($m['textWidth'] > $max_width && $curr !== '') {
                $lines[] = $curr;
                $curr = $word;
            } else {
                $curr = $test;
            }
            if (count($lines) >= 3) break;
        }
        if ($curr !== '' && count($lines) < 3) $lines[] = $curr;
        
        foreach ($lines as $i => $line) {
            self::mjashik_imagick_text_centered($image, $draw, $line, $y + ($i * $size * 1.5), $size);
        }
    }

    // GD Helpers
    private static function mjashik_gd_text_centered($im, $size, $y, $col, $font, $text, $w) {
        $box = imagettfbbox($size, 0, $font, $text);
        $tw = abs($box[4] - $box[0]);
        $x = ($w - $tw) / 2;
        imagettftext($im, $size, 0, $x, $y, $col, $font, $text);
    }

    private static function mjashik_gd_text_wrapped($im, $size, $y, $col, $font, $text, $mw, $cw) {
        $words = explode(' ', $text);
        $lines = [];
        $curr = '';
        
        foreach ($words as $word) {
            $test = $curr === '' ? $word : $curr . ' ' . $word;
            $box = imagettfbbox($size, 0, $font, $test);
            $tw = abs($box[4] - $box[0]);
            
            // GD বাংলা উইডথ বাগ ফিক্সের জন্য ১.১ বাফার
            if (($tw * 1.1) > $mw && $curr !== '') {
                $lines[] = $curr;
                $curr = $word;
            } else {
                $curr = $test;
            }
            if (count($lines) >= 3) break;
        }
        if ($curr !== '' && count($lines) < 3) $lines[] = $curr;
        
        foreach ($lines as $i => $line) {
            self::mjashik_gd_text_centered($im, $size, $y + ($i * $size * 1.6), $col, $font, $line, $cw);
        }
    }

    private static function mjashik_get_local_path($url) {
        if (!$url) return false;
        $upload = wp_upload_dir();
        $path = str_replace($upload['baseurl'], $upload['basedir'], $url);
        if (file_exists($path)) return $path;
        
        // Fallback for local testing
        $parsed = parse_url($url);
        if (isset($parsed['path']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $parsed['path'])) {
            return $_SERVER['DOCUMENT_ROOT'] . $parsed['path'];
        }
        return false;
    }

    private static function mjashik_load_image_gd($url) {
        $path = self::mjashik_get_local_path($url);
        if (!$path) return false;
        $info = getimagesize($path);
        switch ($info['mime']) {
            case 'image/jpeg': return imagecreatefromjpeg($path);
            case 'image/png': return imagecreatefrompng($path);
            case 'image/gif': return imagecreatefromgif($path);
            default: return imagecreatefromstring(file_get_contents($path));
        }
    }

    private static function mjashik_hex_to_rgb($hex) {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        return ['r' => hexdec(substr($hex,0,2)), 'g' => hexdec(substr($hex,2,2)), 'b' => hexdec(substr($hex,4,2))];
    }
}