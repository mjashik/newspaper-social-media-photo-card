<?php
/**
 * Post Integration Class
 * Handles the integration with WordPress posts
 */

if (!defined('ABSPATH')) {
    exit;
}

class MJASHIK_NPC_Post_Integration {
    
    public function __construct() {
        // এডমিন হুক যোগ করা হয়েছে
        add_action('admin_enqueue_scripts', array($this, 'mjashik_admin_enqueue_scripts'));
        add_action('edit_form_after_title', array($this, 'mjashik_add_admin_download_button'));
        add_action('admin_footer', array($this, 'mjashik_render_hidden_card_admin'));
    }
    
    /**
     * Enqueue Admin scripts
     */
    public function mjashik_admin_enqueue_scripts($hook) {
        global $post;
        
        if (($hook == 'post-new.php' || $hook == 'post.php') && get_post_type() == 'post') {
            // Load html2canvas from CDN
            wp_enqueue_script('html2canvas', 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js', [], '1.4.1', true);
            
            wp_enqueue_style(
                'mjashik-npc-admin-css',
                MJASHIK_NPC_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                MJASHIK_NPC_VERSION
            );
            
            wp_enqueue_script(
                'mjashik-npc-admin-js',
                MJASHIK_NPC_PLUGIN_URL . 'assets/js/admin.js',
                array('jquery', 'html2canvas'),
                MJASHIK_NPC_VERSION,
                true
            );
            
            wp_localize_script('mjashik-npc-admin-js', 'mjashikNPC', array(
                'post_id' => isset($post) ? $post->ID : 0,
                'generating_text' => __('Generating...', 'news-photo-card'),
                'download_text' => __('Download Photo Card', 'news-photo-card'),
            ));
        }
    }
    
    /**
     * Add download button in Admin Edit Screen (After Title)
     */
    public function mjashik_add_admin_download_button($post) {
        if ($post->post_type !== 'post') return;
        
        ?>
        <div class="mjashik-npc-admin-container" style="margin-top: 10px; margin-bottom: 20px;">
            <button type="button" id="mjashik-download-card-btn" class="button button-primary button-large">
                <span class="dashicons dashicons-camera" style="margin-top: 3px; margin-right: 5px;"></span>
                <?php _e('Download Photo Card', 'news-photo-card'); ?>
            </button>
            <span id="mjashik-card-loading" style="display: none; margin-left: 10px; vertical-align: middle;">
                <span class="spinner is-active" style="float: none; margin: 0;"></span> Generating...
            </span>
        </div>
        <?php
    }
    
    /**
     * Render the Hidden Card in Admin Footer
     */
    public function mjashik_render_hidden_card_admin() {
        global $post;
        if (!$post || (get_post_type($post) !== 'post')) return;
        
        $post_id = $post->ID;
        
        // Settings
        $logo_url = get_option('mjashik_npc_logo_url');
        $background_url = get_option('mjashik_npc_background_url'); 
        $font_color = get_option('mjashik_npc_font_color', '#000000'); 
        $date_format = get_option('mjashik_npc_date_format', 'd F Y');
        $website_url = get_option('mjashik_npc_website_url', '');
        
        $thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');
        $date = date_i18n($date_format, strtotime($post->post_date));
        $title = $post->post_title;
        
        if ($font_color === '#ffffff') $font_color = '#1a1a1a';

        // Layout Config
        $card_w = 800;
        $card_h = 800;
        $footer_h = 60; // Fixed footer height
        $image_h = 650; // Image height
        $text_h = $card_h - $image_h - $footer_h; // Leftover for text (290px)

        // Premium Background Logic with Pattern
        // Using a subtle geometric pattern if no custom background
        $css_pattern = "background-color: #ffffff; opacity: 0.8; background-image:  linear-gradient(30deg, #f4f6f8 12%, transparent 12.5%, transparent 87%, #f4f6f8 87.5%, #f4f6f8), linear-gradient(150deg, #f4f6f8 12%, transparent 12.5%, transparent 87%, #f4f6f8 87.5%, #f4f6f8), linear-gradient(30deg, #f4f6f8 12%, transparent 12.5%, transparent 87%, #f4f6f8 87.5%, #f4f6f8), linear-gradient(150deg, #f4f6f8 12%, transparent 12.5%, transparent 87%, #f4f6f8 87.5%, #f4f6f8), linear-gradient(60deg, #f4f6f877 25%, transparent 25.5%, transparent 75%, #f4f6f877 75%, #f4f6f877), linear-gradient(60deg, #f4f6f877 25%, transparent 25.5%, transparent 75%, #f4f6f877 75%, #f4f6f877); background-size: 40px 70px; background-position: 0 0, 0 0, 20px 35px, 20px 35px, 0 0, 20px 35px;";
        
        $text_area_bg = $background_url 
             ? "background-image: url('{$background_url}'); background-size: cover; background-position: center;" 
             : $css_pattern;

        echo "
        <div id='npc-hidden-container' style='position:absolute; left:-9999px; top:-9999px;'>
            <div id='npc-card-capture' style='width: {$card_w}px; height: {$card_h}px; position: relative; overflow: hidden; font-family: \"Noto Sans Bengali\", sans-serif; background: #fff; display: flex; flex-direction: column;'>
                
                <!-- 1. FEATURED IMAGE AREA (TOP) -->
                <div style='position: relative; width: 100%; height: auto; min-height: 200px; flex: 1 1 auto; overflow: hidden;'>
                    " . ($thumbnail_url 
                        ? "<img src='{$thumbnail_url}' style='width: 100%; height: 100%; object-fit: cover; object-position: center top;' crossorigin='anonymous'>" 
                        : "<div style='width:100%; height:100%; background:#ddd;'></div>") . "
                    
                    <!-- Gradient Overlay for Contrast -->
                    <div style='position: absolute; bottom: 0; left: 0; width: 100%; height: 150px; background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);'></div>

                    <!-- FLOATING BADGES -->
                    <!-- Logo (Top Left - Floating) -->
                    <div style='position: absolute; top: 30px; left: 30px; z-index: 20; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.4));'>
                         " . ($logo_url ? "<img src='{$logo_url}' style='max-height: 70px; height: auto; width: auto; max-width: 250px;' crossorigin='anonymous'>" : "") . "
                    </div>

                    <!-- Date (Top Right - Modern Rounded Badge) -->
                    <div style='position: absolute; top: 30px; right: 30px; background: #e74c3c; color: #fff; padding: 10px 20px; font-weight: bold; font-size: 18px; border-radius: 50px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); z-index: 20; border: 2px solid #fff;'>
                        {$date}
                    </div>
                </div>

                <!-- 2. TEXT AREA (MIDDLE) - PREMIUM LOOK -->
                <div style='position: relative; width: 100%; height: auto; border-top: 6px solid #e74c3c; box-sizing: border-box; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 30px 40px; text-align: center; flex: 0 0 auto; overflow: hidden;'>
                    
                    <!-- Background Layer -->
                    <div style='position: absolute; top: 0; left: 0; width: 100%; height: 100%; {$text_area_bg} z-index: 0;'></div>
                    
                    <!-- Optional: White Overlay for readability if using pattern/image -->
                    <div style='position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.85); z-index: 1;'></div>

                    <!-- Watermark Logo (Very faint) -->
                    <div style='position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.05; width: 50%; pointer-events: none; z-index: 2;'>
                         " . ($logo_url ? "<img src='{$logo_url}' style='width: 100%; height: auto;' crossorigin='anonymous'>" : "") . "
                    </div>

                    <!-- Decorative Quote -->
                    <div style='position: absolute; top: -20px; left: 20px; font-size: 140px; color: #e74c3c; opacity: 0.05; font-family: serif; line-height: 1; z-index: 2;'>❝</div>

                    <!-- HEADLINE CONTAINER -->
                    <!-- This container ensures the text pops with a background if needed, or simply centers perfectly -->
                    <div style='position: relative; z-index: 10; width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px 0;'>
                        <h1 style='margin: 0; padding: 0; font-size: 42px; line-height: 1.4; font-weight: 700; color: {$font_color}; width: 100%; display: -webkit-box; -webkit-line-clamp: 6; -webkit-box-orient: vertical; overflow: hidden; text-shadow: 0 1px 1px rgba(0,0,0,0.1);'>
                            {$title}
                        </h1>
                    </div>
                </div>

                <!-- 3. FOOTER URL BAR (BOTTOM) -->
                <div style='width: 100%; height: {$footer_h}px; background: #2c3e50; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 600; letter-spacing: 1px; flex-shrink: 0; position: relative; overflow: hidden;'>
                     
                     <!-- Subtle Accent Line -->
                     <div style='position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: rgba(0,0,0,0.2);'></div>
                     
                     <span style='opacity: 1; text-shadow: 0 2px 4px rgba(0,0,0,0.3);'>{$website_url}</span>
                </div>

            </div>
        </div>";
    }
}
