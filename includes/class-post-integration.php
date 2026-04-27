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
            // Load html2canvas from local assets
            wp_enqueue_script('html2canvas', MJASHIK_NPC_PLUGIN_URL . 'assets/js/html2canvas.min.js', [], '1.4.1', true);
            
            wp_enqueue_style(
                'mjashik-npc-bangla-fonts',
                MJASHIK_NPC_PLUGIN_URL . 'assets/css/bangla-fonts.css',
                array(),
                MJASHIK_NPC_VERSION
            );

            wp_enqueue_style(
                'mjashik-npc-admin-css',
                MJASHIK_NPC_PLUGIN_URL . 'assets/css/admin.css',
                array('mjashik-npc-bangla-fonts'),
                MJASHIK_NPC_VERSION
            );

            // ── Template-specific card.js (must load BEFORE admin.js) ──
            $active_tpl    = MJASHIK_NPC_Template_Loader::get_active_template();
            $template_card_js = MJASHIK_NPC_Template_Loader::get_template_file($active_tpl, 'card.js');
            if ($template_card_js) {
                wp_enqueue_script(
                    'mjashik-npc-template-card-js',
                    MJASHIK_NPC_PLUGIN_URL . 'templates/' . $active_tpl . '/card.js',
                    array('html2canvas'),
                    MJASHIK_NPC_VERSION,
                    true
                );
            }

            // ── Core admin.js (depends on template card.js being registered first) ──
            wp_enqueue_script(
                'mjashik-npc-admin-js',
                MJASHIK_NPC_PLUGIN_URL . 'assets/js/admin.js',
                array('jquery', 'html2canvas', 'mjashik-npc-template-card-js'),
                MJASHIK_NPC_VERSION,
                true
            );
            
            wp_localize_script('mjashik-npc-admin-js', 'mjashik_npc_data', array(
                'post_id'         => isset($post) ? $post->ID : 0,
                'generating_text' => __('Generating...', 'newspaper-social-media-photo-card'),
                'download_text'   => __('Download Photo Card', 'newspaper-social-media-photo-card'),
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
                <?php esc_html_e('Download Photo Card', 'newspaper-social-media-photo-card'); ?>
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
            wp_enqueue_script('html2canvas', MJASHIK_NPC_PLUGIN_URL . 'assets/js/html2canvas.min.js', [], '1.4.1', true);
            
            wp_enqueue_style('dashicons');
            wp_enqueue_style('mjashik-npc-bangla-fonts', MJASHIK_NPC_PLUGIN_URL . 'assets/css/bangla-fonts.css', array(), MJASHIK_NPC_VERSION);
            wp_enqueue_style('mjashik-npc-admin-css', MJASHIK_NPC_PLUGIN_URL . 'assets/css/admin.css', array('mjashik-npc-bangla-fonts'), MJASHIK_NPC_VERSION);

            // ── Template-specific card.js (must load BEFORE admin.js) ──
            $active_tpl       = MJASHIK_NPC_Template_Loader::get_active_template();
            $template_card_js = MJASHIK_NPC_Template_Loader::get_template_file($active_tpl, 'card.js');
            if ($template_card_js) {
                wp_enqueue_script(
                    'mjashik-npc-template-card-js',
                    MJASHIK_NPC_PLUGIN_URL . 'templates/' . $active_tpl . '/card.js',
                    array('html2canvas'),
                    MJASHIK_NPC_VERSION,
                    true
                );
            }

            // ── Core admin.js ──
            wp_enqueue_script('mjashik-npc-admin-js', MJASHIK_NPC_PLUGIN_URL . 'assets/js/admin.js', array('jquery', 'html2canvas', 'mjashik-npc-template-card-js'), MJASHIK_NPC_VERSION, true);
            wp_localize_script('mjashik-npc-admin-js', 'mjashik_npc_data', array(
                'post_id'         => isset($post) ? $post->ID : 0,
                'generating_text' => esc_html__('Generating...', 'newspaper-social-media-photo-card'),
                'download_text'   => esc_html__('Download Photo Card', 'newspaper-social-media-photo-card'),
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
                <h4 style="margin-top: 0; margin-bottom: 15px;"><?php esc_html_e('Share this news as a Photo Card', 'newspaper-social-media-photo-card'); ?></h4>
                <button type="button" id="mjashik-download-card-btn" class="button" style="background: #2271b1; color: #fff; border-color: #2271b1; padding: 10px 20px; font-size: 16px; border-radius: 4px; cursor: pointer;">
                    <span class="dashicons dashicons-camera" style="vertical-align: middle;"></span>
                    <?php esc_html_e('Download Photo Card', 'newspaper-social-media-photo-card'); ?>
                </button>
                <div id="mjashik-card-loading" style="display: none; margin-top: 10px;">
                    <?php esc_html_e('Generating high-quality image, please wait...', 'newspaper-social-media-photo-card'); ?>
                </div>
            </div>
            <?php
            $button_html = ob_get_clean();
            $content .= $button_html;
        }
        return $content;
    }
    
    /**
     * Get SVG code for social icons
     */
    private function mjashik_get_social_icon_svg($type, $color = 'currentColor') {
        $size = '20';
        $style = 'width:'.$size.'px; height:'.$size.'px; fill:'.$color.';';
        switch ($type) {
            case 'facebook':
                return '<svg style="'.$style.'" viewBox="0 0 24 24"><path d="M12 2.04c-5.5 0-10 4.49-10 10.02 0 5 3.66 9.15 8.44 9.9v-7h-2.54V12h2.54V9.79c0-2.5 1.5-3.89 3.77-3.89 1.1 0 2.23.2 2.23.2v2.45h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.77l-.44 2.95h-2.33v7C18.34 21.19 22 17.06 22 12.06c0-5.53-4.5-10.02-10-10.02z"/></svg>';
            case 'twitter':
                return '<svg style="'.$style.'" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>';
            case 'instagram':
                return '<svg style="'.$style.'" viewBox="0 0 24 24"><path d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2m-.2 2A3.6 3.6 0 0 0 4 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6C20 5.61 18.39 4 16.4 4H7.6m8.4 2.4c.54 0 1 .45 1 1 0 .56-.46 1.01-1 1.01s-1-.45-1-1m-4 1.2c2.2 0 4 1.8 4 4s-1.8 4-4 4-4-1.8-4-4 1.8-4 4-4m0 1.6c-1.32 0-2.4 1.08-2.4 2.4 0 1.32 1.08 2.4 2.4 2.4s2.4-1.08 2.4-2.4c0-1.32-1.08-2.4-2.4-2.4z"/></svg>';
            case 'youtube':
                return '<svg style="'.$style.'" viewBox="0 0 24 24"><path d="M21.58 7.19c-.23-.86-.91-1.54-1.77-1.77C18.25 5 12 5 12 5s-6.25 0-7.81.42c-.86.23-1.54.91-1.77 1.77C2 8.75 2 12 2 12s0 3.25.42 4.81c.23.86.91 1.54 1.77 1.77C5.75 19 12 19 12 19s6.25 0 7.81-.42c.86-.23 1.54-.91 1.77-1.77C22 15.25 22 12 22 12s0-3.25-.42-4.81zM10 15V9l5.2 3z"/></svg>';
            case 'linkedin':
                return '<svg style="'.$style.'" viewBox="0 0 24 24"><path d="M20.45 20.45h-3.56v-5.56c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.66H9.33V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.36-1.85 3.6 0 4.26 2.37 4.26 5.45v6.29zM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12zM3.55 20.45h3.57V9H3.55v11.45zM22 2H2v20h20V2z"/></svg>';
        }
        return '';
    }

    /**
     * Render the Hidden Card in Admin or Frontend Footer
     * Delegates HTML rendering to the active template's template-card.php
     */
    public function mjashik_render_hidden_card() {
        global $post, $pagenow;
        
        // Check if we should render
        if (is_admin()) {
            if ($pagenow !== 'post.php' && $pagenow !== 'post-new.php') {
                return;
            }
            $post_id = isset($_GET['post']) ? intval($_GET['post']) : (isset($post) ? $post->ID : 0);
            $_post = get_post($post_id);
            $post_date = $_post ? $_post->post_date : current_time('mysql');
            $post_title = $_post ? $_post->post_title : '';
        } else {
            if (!is_single() || get_post_type() !== 'post') return;
            $post_id = $post->ID;
            $post_date = $post->post_date;
            $post_title = $post->post_title;
        }
        
        // Settings
        $logo_url       = get_option('mjashik_npc_logo_url');
        $logo_shadow    = get_option('mjashik_npc_logo_shadow_color', '#000000');
        $font_color     = get_option('mjashik_npc_font_color', '#ffffff');
        $title_area_bg  = get_option('mjashik_npc_title_area_bg_color', '#AA0001');
        $date_bg        = get_option('mjashik_npc_date_bg_color', '#AA0001');
        $date_color     = get_option('mjashik_npc_date_text_color', '#ffffff');
        $footer_bg      = get_option('mjashik_npc_footer_bg_color', '#AA0001');
        $footer_color   = get_option('mjashik_npc_footer_text_color', '#ffffff');
        $date_format    = get_option('mjashik_npc_date_format', 'd F Y');
        $website_url    = get_option('mjashik_npc_website_url', 'www.hostbuybd.com');
        $title_fs       = get_option('mjashik_npc_title_font_size', 42);
        $footer_fs      = get_option('mjashik_npc_footer_font_size', 22);
        $title_font     = get_option('mjashik_npc_title_font', 'SolaimanLipi');
        $date_font      = get_option('mjashik_npc_date_font', 'SolaimanLipi');
        
        $social_raw     = get_option('mjashik_npc_social_links', '[]');
        $social_links   = json_decode($social_raw, true);
        if (!is_array($social_links)) $social_links = array();
        
        $thumbnail_url = $post_id ? get_the_post_thumbnail_url($post_id, 'full') : '';
        $date  = date_i18n($date_format, strtotime($post_date));
        $title = $post_title;

        // Layout Config — dynamic: image flex-grows, title auto-height, footer fixed
        $card_w   = 800;
        $card_h   = 800;
        $footer_h = 80;

        // SVG icon callable — passed into template scope
        $post_obj = $this;
        $mjashik_social_icon_fn = function($type, $color) use ($post_obj) {
            return $post_obj->mjashik_get_social_icon_svg($type, $color);
        };

        // Bundle vars for the template
        $card_vars = compact(
            'logo_url', 'logo_shadow', 'font_color', 'title_area_bg',
            'date_bg', 'date_color', 'footer_bg', 'footer_color',
            'date_format', 'website_url', 'title_fs', 'footer_fs',
            'title_font', 'date_font', 'social_links',
            'thumbnail_url', 'date', 'title',
            'card_w', 'card_h', 'footer_h',
            'mjashik_social_icon_fn'
        );

        // Load the active template's card file
        $active_tpl = MJASHIK_NPC_Template_Loader::get_active_template();
        MJASHIK_NPC_Template_Loader::include_template($active_tpl, 'template-card.php', $card_vars);
    }
}
