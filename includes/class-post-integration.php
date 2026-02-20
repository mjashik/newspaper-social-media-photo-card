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
        // Admin Hooks
        add_action('admin_enqueue_scripts', array($this, 'mjashik_admin_enqueue_scripts'));
        add_action('edit_form_after_title', array($this, 'mjashik_add_admin_download_button'));
        add_action('admin_footer', array($this, 'mjashik_render_hidden_card'));

        // Frontend Hooks
        if (get_option('mjashik_npc_show_download_button', 'yes') === 'yes') {
            add_action('wp_enqueue_scripts', array($this, 'mjashik_frontend_enqueue_scripts'));
            add_filter('the_content', array($this, 'mjashik_add_frontend_download_button'));
            add_action('wp_footer', array($this, 'mjashik_render_hidden_card'));
        }
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
     * Enqueue Frontend scripts
     */
    public function mjashik_frontend_enqueue_scripts() {
        if (is_single() && get_post_type() === 'post') {
            global $post;
            wp_enqueue_script('html2canvas', 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js', [], '1.4.1', true);
            
            // Enqueue dashicons for the button icon (usually only loaded for logged-in users)
            wp_enqueue_style('dashicons');

            // Reusing admin CSS/JS for the card rendering and button functionality
            wp_enqueue_style('mjashik-npc-admin-css', MJASHIK_NPC_PLUGIN_URL . 'assets/css/admin.css', array(), MJASHIK_NPC_VERSION);
            wp_enqueue_script('mjashik-npc-admin-js', MJASHIK_NPC_PLUGIN_URL . 'assets/js/admin.js', array('jquery', 'html2canvas'), MJASHIK_NPC_VERSION, true);
            wp_localize_script('mjashik-npc-admin-js', 'mjashikNPC', array(
                'post_id' => isset($post) ? $post->ID : 0,
                'generating_text' => esc_html__('Generating...', 'news-photo-card'),
                'download_text' => esc_html__('Download Photo Card', 'news-photo-card'),
            ));
        }
    }

    /**
     * Add download button in Frontend Single Post (Appended to content)
     */
    public function mjashik_add_frontend_download_button($content) {
        if (is_single() && get_post_type() === 'post' && in_the_loop() && is_main_query()) {
            ob_start();
            ?>
            <div class="mjashik-npc-frontend-container" style="margin-top: 30px; margin-bottom: 30px; padding: 20px; background: #f9f9f9; border: 1px solid #eee; border-radius: 8px; text-align: center;">
                <h4 style="margin-top: 0; margin-bottom: 15px;"><?php esc_html_e('Share this news as a Photo Card', 'news-photo-card'); ?></h4>
                <button type="button" id="mjashik-download-card-btn" class="button" style="background: #2271b1; color: #fff; border-color: #2271b1; padding: 10px 20px; font-size: 16px; border-radius: 4px; cursor: pointer;">
                    <span class="dashicons dashicons-camera" style="vertical-align: middle;"></span>
                    <?php esc_html_e('Download Photo Card', 'news-photo-card'); ?>
                </button>
                <div id="mjashik-card-loading" style="display: none; margin-top: 10px;">
                    <?php esc_html_e('Generating high-quality image, please wait...', 'news-photo-card'); ?>
                </div>
            </div>
            <?php
            $button_html = ob_get_clean();
            $content .= $button_html;
        }
        return $content;
    }
    
    /**
     * Render the Hidden Card in Admin or Frontend Footer
     */
    public function mjashik_render_hidden_card() {
        global $post;
        
        // Check if we should render
        if (is_admin()) {
            if (!$post || (get_post_type($post) !== 'post')) return;
        } else {
            if (!is_single() || get_post_type() !== 'post') return;
        }
        
        $post_id = $post->ID;
        
        // Settings
        $logo_url       = get_option('mjashik_npc_logo_url');
        $background_url = get_option('mjashik_npc_background_url');
        $font_color     = get_option('mjashik_npc_font_color', '#1a1a1a');
        $title_area_bg  = get_option('mjashik_npc_title_area_bg_color', '#ffffff');
        $date_bg        = get_option('mjashik_npc_date_bg_color', '#e74c3c');
        $date_color     = get_option('mjashik_npc_date_text_color', '#ffffff');
        $footer_bg      = get_option('mjashik_npc_footer_bg_color', '#2c3e50');
        $footer_color   = get_option('mjashik_npc_footer_text_color', '#ffffff');
        $date_format    = get_option('mjashik_npc_date_format', 'd F Y');
        $website_url    = get_option('mjashik_npc_website_url', '');
        $title_fs       = get_option('mjashik_npc_title_font_size', 42);
        
        $thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');
        $date  = date_i18n($date_format, strtotime($post->post_date));
        $title = $post->post_title;

        // Layout Config — dynamic: image flex-grows, title auto-height, footer fixed
        $card_w   = 800;
        $card_h   = 800;
        $footer_h = 70;

        echo "
        <div id='npc-hidden-container' style='position:absolute; left:-9999px; top:-9999px;'>
            <div id='npc-card-capture' style='width:{$card_w}px; height:{$card_h}px; position:relative; overflow:hidden; font-family:\"Noto Sans Bengali\",sans-serif; background:#fff; display:flex; flex-direction:column;'>

                <!-- 1. IMAGE AREA — fills remaining space (flex:1), uses CSS background-image for html2canvas -->
                <div style='position:relative; width:100%; flex:1 1 auto; min-height:200px; overflow:hidden; "
                    . ($thumbnail_url
                        ? "background-image:url(" . esc_url($thumbnail_url) . "); background-size:cover; background-position:center top;"
                        : "background:linear-gradient(135deg,#dde3ea,#b2bec3);")
                    . "'>

                    <!-- Gradient Overlay -->
                    <div style='position:absolute; bottom:0; left:0; width:100%; height:160px; background:linear-gradient(to top,rgba(0,0,0,0.65),transparent); z-index:10;'></div>

                    <!-- Logo (Top Left) -->
                    <div style='position:absolute; top:28px; left:28px; z-index:30;'>
                        " . ($logo_url ? "<img src='" . esc_url($logo_url) . "' style='height:70px; width:auto; max-width:240px; display:block;' crossorigin='anonymous'>" : "") . "
                    </div>

                    <!-- Date Badge (Top Right) -->
                    <div style='position:absolute; top:28px; right:28px; background:" . esc_attr($date_bg) . "; color:" . esc_attr($date_color) . "; padding:10px 22px; font-weight:bold; font-size:18px; border-radius:50px; box-shadow:0 4px 12px rgba(0,0,0,0.3); z-index:30; border:2px solid rgba(255,255,255,0.6);'>
                        " . esc_html($date) . "
                    </div>
                </div>

                <!-- 2. TITLE AREA — auto height, customizable bg & text color -->
                <div style='position:relative; width:100%; flex:0 0 auto; border-top:5px solid " . esc_attr($date_bg) . "; box-sizing:border-box; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:28px 50px; text-align:center; overflow:hidden; "
                    . ($background_url
                        ? "background-image:url(" . esc_url($background_url) . "); background-size:cover; background-position:center;"
                        : "background-color:" . esc_attr($title_area_bg) . ";")
                    . "'>

                    " . ($background_url ? "<!-- Semi-transparent overlay for readability over image -->
                    <div style='position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.82); z-index:1;'></div>" : "") . "
                    <!-- Watermark logo -->
                    <div style='position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); opacity:" . esc_attr(((int) get_option('mjashik_npc_watermark_opacity', 8)) / 100) . "; width:55%; pointer-events:none; z-index:2;'>
                        " . ($logo_url ? "<img src='" . esc_url($logo_url) . "' style='width:100%; height:auto;' crossorigin='anonymous'>" : "") . "
                    </div>

                    <!-- Headline -->
                    <div style='position:relative; z-index:10; width:100%;'>
                        <h1 style='margin:0; padding:0; font-size:" . esc_attr($title_fs) . "px; line-height:1.5; font-weight:700; color:" . esc_attr($font_color) . "; width:100%; text-shadow:0 1px 2px rgba(0,0,0,0.06);'>
                            " . esc_html($title) . "
                        </h1>
                    </div>
                </div>

                <!-- 3. FOOTER — fixed height, customizable bg & text color -->
                <div style='width:100%; height:{$footer_h}px; background:" . esc_attr($footer_bg) . "; color:" . esc_attr($footer_color) . "; display:flex; align-items:center; justify-content:center; font-size:22px; font-weight:600; letter-spacing:1.5px; flex:0 0 {$footer_h}px; position:relative; overflow:hidden;'>
                    <div style='position:absolute; top:0; left:0; width:100%; height:4px; background:rgba(255,255,255,0.1);'></div>
                    <span style='text-shadow:0 2px 4px rgba(0,0,0,0.2);'>" . esc_url($website_url) . "</span>
                </div>

            </div>
        </div>";

    }
}
