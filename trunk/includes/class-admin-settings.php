<?php
/**
 * Admin Settings Class
 * Handles the admin settings page for News Photo Card plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class MJASHIK_NPC_Admin_Settings {
    
    /**
     * All available Bangla fonts
     */
    private function mjashik_get_font_list() {
        return array(
            'SolaimanLipi'         => __('SolaimanLipi', 'newspaper-social-media-photo-card'),
            'Nikosh'               => __('Nikosh', 'newspaper-social-media-photo-card'),
            'SiyamRupali'          => __('SiyamRupali', 'newspaper-social-media-photo-card'),
            'Kalpurush'            => __('Kalpurush', 'newspaper-social-media-photo-card'),
            'Mukti'                => __('Mukti', 'newspaper-social-media-photo-card'),
            'AdorshoLipi'          => __('AdorshoLipi', 'newspaper-social-media-photo-card'),
            'AponaLohit'           => __('AponaLohit', 'newspaper-social-media-photo-card'),
            'BalooDa2'             => __('Baloo Da 2', 'newspaper-social-media-photo-card'),
            'Bensen'               => __('Bensen', 'newspaper-social-media-photo-card'),
            'BensenHandwriting'    => __('Bensen Handwriting', 'newspaper-social-media-photo-card'),
            'CharuChandan'         => __('Charu Chandan', 'newspaper-social-media-photo-card'),
            'CharuChandan3D'       => __('Charu Chandan 3D', 'newspaper-social-media-photo-card'),
            'CharuChandanHardStroke' => __('Charu Chandan Hard Stroke', 'newspaper-social-media-photo-card'),
            'CharukolaUltraLight'  => __('Charukola Ultra Light', 'newspaper-social-media-photo-card'),
            'EkusheyLohit'         => __('Ekushey Lohit', 'newspaper-social-media-photo-card'),
            'LohitBengali'         => __('Lohit Bengali', 'newspaper-social-media-photo-card'),
            'NotoSerifBengali'     => __('Noto Serif Bengali', 'newspaper-social-media-photo-card'),
            'TiroBangla'           => __('Tiro Bangla', 'newspaper-social-media-photo-card'),
            'Vrinda'               => __('Vrinda', 'newspaper-social-media-photo-card'),
            'BanglaFont'           => __('Bangla', 'newspaper-social-media-photo-card'),
        );
    }

    public function __construct() {
        add_action('admin_menu', array($this, 'mjashik_add_admin_menu'));
        add_action('admin_init', array($this, 'mjashik_register_settings'));
        add_action('admin_init', array($this, 'mjashik_process_settings_reset'));
        add_action('admin_enqueue_scripts', array($this, 'mjashik_enqueue_admin_scripts'));
    }
    
    /**
     * Add admin menu
     */
    public function mjashik_add_admin_menu() {
        add_menu_page(
            __('News Photo Card', 'newspaper-social-media-photo-card'),
            __('Photo Card', 'newspaper-social-media-photo-card'),
            'manage_options',
            'newspaper-social-media-photo-card',
            array($this, 'mjashik_settings_page'),
            'dashicons-format-image',
            30
        );
    }
    
    /**
     * Register settings
     */
    public function mjashik_register_settings() {
        // Logo setting
        register_setting('mjashik_npc_settings', 'mjashik_npc_logo_url', array(
            'sanitize_callback' => 'esc_url_raw',
        ));
        
        // Title font color
        register_setting('mjashik_npc_settings', 'mjashik_npc_font_color', array(
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        // Logo shadow color
        register_setting('mjashik_npc_settings', 'mjashik_npc_logo_shadow_color', array(
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        // Title area background color
        register_setting('mjashik_npc_settings', 'mjashik_npc_title_area_bg_color', array(
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        // Date badge background color
        register_setting('mjashik_npc_settings', 'mjashik_npc_date_bg_color', array(
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        // Date badge text color
        register_setting('mjashik_npc_settings', 'mjashik_npc_date_text_color', array(
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        // Footer background color
        register_setting('mjashik_npc_settings', 'mjashik_npc_footer_bg_color', array(
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        // Footer text color
        register_setting('mjashik_npc_settings', 'mjashik_npc_footer_text_color', array(
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        // Date format
        register_setting('mjashik_npc_settings', 'mjashik_npc_date_format', array(
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        // Show download button
        register_setting('mjashik_npc_settings', 'mjashik_npc_show_download_button', array(
            'sanitize_callback' => array($this, 'mjashik_sanitize_yes_no'),
        ));
        
        // Title font size
        register_setting('mjashik_npc_settings', 'mjashik_npc_title_font_size', array(
            'sanitize_callback' => 'absint',
        ));
        
        // Date font size
        register_setting('mjashik_npc_settings', 'mjashik_npc_date_font_size', array(
            'sanitize_callback' => 'absint',
        ));
        
        // Footer font size
        register_setting('mjashik_npc_settings', 'mjashik_npc_footer_font_size', array(
            'sanitize_callback' => 'absint',
        ));
        
        // Website URL
        register_setting('mjashik_npc_settings', 'mjashik_npc_website_url', array(
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        // Watermark opacity
        register_setting('mjashik_npc_settings', 'mjashik_npc_watermark_opacity', array(
            'sanitize_callback' => 'absint',
        ));

        // Title font
        register_setting('mjashik_npc_settings', 'mjashik_npc_title_font', array(
            'sanitize_callback' => array($this, 'mjashik_sanitize_font'),
        ));

        // Date font
        register_setting('mjashik_npc_settings', 'mjashik_npc_date_font', array(
            'sanitize_callback' => array($this, 'mjashik_sanitize_font'),
        ));

        // Social Links JSON
        register_setting('mjashik_npc_settings', 'mjashik_npc_social_links', array(
            'sanitize_callback' => array($this, 'mjashik_sanitize_social_links'),
        ));

        // Active template
        register_setting('mjashik_npc_settings', 'mjashik_npc_active_template', array(
            'sanitize_callback' => 'sanitize_text_field',
        ));
    }

    /**
     * Sanitize Social Links JSON
     */
    public function mjashik_sanitize_social_links($value) {
        $decoded = json_decode($value, true);
        if (!is_array($decoded)) {
            return '[]';
        }

        $sanitized = array();
        foreach ($decoded as $link) {
            $type = isset($link['type']) ? sanitize_text_field($link['type']) : 'facebook';
            $text = isset($link['text']) ? sanitize_text_field($link['text']) : '';
            $custom_img = isset($link['custom_img']) ? esc_url_raw($link['custom_img']) : '';
            
            $sanitized[] = array(
                'type' => $type,
                'text' => $text,
                'custom_img' => $custom_img
            );
        }

        return json_encode($sanitized);
    }
    
    /**
     * Sanitize yes/no dropdown values
     */
    public function mjashik_sanitize_yes_no($value) {
        return in_array($value, array('yes', 'no'), true) ? $value : 'yes';
    }

    /**
     * Sanitize font selection — must be one of the allowed font keys
     */
    public function mjashik_sanitize_font($value) {
        $allowed = array_keys($this->mjashik_get_font_list());
        return in_array($value, $allowed, true) ? $value : 'SolaimanLipi';
    }
    
    /**
     * Get SVG code for social icons
     */
    public function mjashik_get_social_icon_svg($type, $color = 'currentColor') {
        $size = '20';
        $style = 'width:'.$size.'px; height:'.$size.'px; fill:'.$color.';';
        switch ($type) {
            case 'facebook':
                return '<svg style="'.$style.'" viewBox="0 0 24 24"><path d="M12 2.04c-5.5 0-10 4.49-10 10.02 0 5 3.66 9.15 8.44 9.9v-7h-2.54V12h2.54V9.79c0-2.5 1.5-3.89 3.77-3.89 1.1 0 2.23.2 2.23.2v2.45h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.77l-.44 2.95h-2.33v7C18.34 21.19 22 17.06 22 12.06c0-5.53-4.5-10.02-10-10.02z"/></svg>';
            case 'twitter':
                return '<svg style="'.$style.'" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>';
            case 'instagram':
                return '<svg style="'.$style.'" viewBox="0 0 24 24"><path d="M7.8 2h8.4C19.4 2 22 4.6 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8C4.6 22 2 19.4 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2m-.2 2A3.6 3.6 0 0 0 4 7.6v8.8C4 18.39 5.61 20 7.6 20h8.8a3.6 3.6 0 0 0 3.6-3.6V7.6C20 5.61 18.39 4 16.4 4H7.6m8.4 2.4c.54 0 1 .45 1 1 0 .56-.46 1.01-1 1.01s-1-.45-1-1.01c0-.55.46-1 1-1m-4 1.2c2.2 0 4 1.8 4 4s-1.8 4-4 4-4-1.8-4-4 1.8-4 4-4m0 1.6c-1.32 0-2.4 1.08-2.4 2.4 0 1.32 1.08 2.4 2.4 2.4s2.4-1.08 2.4-2.4c0-1.32-1.08-2.4-2.4-2.4z"/></svg>';
            case 'youtube':
                return '<svg style="'.$style.'" viewBox="0 0 24 24"><path d="M21.58 7.19c-.23-.86-.91-1.54-1.77-1.77C18.25 5 12 5 12 5s-6.25 0-7.81.42c-.86.23-1.54.91-1.77 1.77C2 8.75 2 12 2 12s0 3.25.42 4.81c.23.86.91 1.54 1.77 1.77C5.75 19 12 19 12 19s6.25 0 7.81-.42c.86-.23 1.54-.91 1.77-1.77C22 15.25 22 12 22 12s0-3.25-.42-4.81zM10 15V9l5.2 3z"/></svg>';
            case 'linkedin':
                return '<svg style="'.$style.'" viewBox="0 0 24 24"><path d="M20.45 20.45h-3.56v-5.56c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.66H9.33V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.36-1.85 3.6 0 4.26 2.37 4.26 5.45v6.29zM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12zM3.55 20.45h3.57V9H3.55v11.45zM22 2H2v20h20V2z"/></svg>';
        }
        return '';
    }
    
    /**
     * Process reset to defaults
     */
    public function mjashik_process_settings_reset() {
        if (isset($_POST['mjashik_npc_reset_settings']) && isset($_POST['mjashik_npc_reset_nonce'])) {
            if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['mjashik_npc_reset_nonce'])), 'mjashik_npc_reset_action')) {
                wp_die(__('Security check failed', 'newspaper-social-media-photo-card'));
            }
            
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have permission', 'newspaper-social-media-photo-card'));
            }
            
            // Delete all plugin options EXECPT the logo
            delete_option('mjashik_npc_logo_shadow_color');
            delete_option('mjashik_npc_font_color');
            delete_option('mjashik_npc_title_area_bg_color');
            delete_option('mjashik_npc_date_bg_color');
            delete_option('mjashik_npc_date_text_color');
            delete_option('mjashik_npc_footer_bg_color');
            delete_option('mjashik_npc_footer_text_color');
            delete_option('mjashik_npc_date_format');
            delete_option('mjashik_npc_show_download_button');
            delete_option('mjashik_npc_title_font_size');
            delete_option('mjashik_npc_date_font_size');
            delete_option('mjashik_npc_footer_font_size');
            delete_option('mjashik_npc_website_url');
            delete_option('mjashik_npc_watermark_opacity');
            delete_option('mjashik_npc_title_font');
            delete_option('mjashik_npc_date_font');
            delete_option('mjashik_npc_social_links');
            
            add_settings_error('mjashik_npc_messages', 'mjashik_npc_message', __('Settings restored to default values', 'newspaper-social-media-photo-card'), 'updated');
        }
    }
    
    /**
     * Enqueue admin scripts
     */
    public function mjashik_enqueue_admin_scripts($hook) {
        if ($hook !== 'toplevel_page_newspaper-social-media-photo-card') {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        wp_enqueue_style(
            'mjashik-npc-bangla-fonts',
            MJASHIK_NPC_PLUGIN_URL . 'assets/css/bangla-fonts.css',
            array(),
            MJASHIK_NPC_VERSION
        );
        
        wp_enqueue_style(
            'mjashik-npc-admin',
            MJASHIK_NPC_PLUGIN_URL . 'assets/css/admin.css',
            array('mjashik-npc-bangla-fonts'),
            MJASHIK_NPC_VERSION
        );
        
        wp_enqueue_script(
            'mjashik-npc-admin',
            MJASHIK_NPC_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery', 'wp-color-picker'),
            MJASHIK_NPC_VERSION,
            true
        );
    }
    
    /**
     * Settings page HTML
     */
    public function mjashik_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('News Photo Card Settings', 'newspaper-social-media-photo-card'); ?></h1>

            <!-- Two-column layout: Settings Left, Preview Right -->
            <div style="display:flex; gap:30px; align-items:flex-start; margin-top:15px;">

            <!-- LEFT COLUMN: Settings Form -->
            <div style="flex:1 1 0; min-width:400px;">
            <form method="post" action="options.php" id="mjashik_npc_main_form">
                <?php settings_fields('mjashik_npc_settings'); ?>
                
                <table class="form-table">

                    <!-- ============ CHOOSE TEMPLATE ============ -->
                    <tr>
                        <td colspan="2" style="padding: 0 0 10px;">
                            <h2 style="margin:0; padding: 8px 12px; background:#1a1a2e; color:#e0e0ff; border-radius:4px; font-size:14px;">
                                🖼️ <?php esc_html_e('Choose Template', 'newspaper-social-media-photo-card'); ?>
                            </h2>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style="padding-left:20px;">
                            <label for="mjashik_npc_active_template"><?php esc_html_e('Active Template', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <?php
                            $active_tpl   = MJASHIK_NPC_Template_Loader::get_active_template();
                            $all_tpls     = MJASHIK_NPC_Template_Loader::get_available_templates();
                            ?>
                            <select id="mjashik_npc_active_template" name="mjashik_npc_active_template" style="min-width:220px; font-size:14px;">
                                <?php foreach ($all_tpls as $tpl_slug => $tpl_info): ?>
                                    <option value="<?php echo esc_attr($tpl_slug); ?>" <?php selected($active_tpl, $tpl_slug); ?>>
                                        <?php echo esc_html($tpl_info['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description"><?php esc_html_e('The selected template will be used for both the preview and the downloaded photo card.', 'newspaper-social-media-photo-card'); ?></p>
                        </td>
                    </tr>
                    <tr><td colspan="2"><hr style="margin:4px 0 0;"></td></tr>

                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html__('Logo Image', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="mjashik_npc_logo_url" 
                                   name="mjashik_npc_logo_url" 
                                   value="<?php echo esc_attr(get_option('mjashik_npc_logo_url')); ?>" 
                                   class="regular-text" />
                            <button type="button" class="button mjashik-upload-button" data-target="mjashik_npc_logo_url">
                                <?php echo esc_html__('Upload Logo', 'newspaper-social-media-photo-card'); ?>
                            </button>
                            <div class="mjashik-image-preview">
                                <?php if (get_option('mjashik_npc_logo_url')): ?>
                                    <img src="<?php echo esc_url(get_option('mjashik_npc_logo_url')); ?>" style="max-width: 200px; margin-top: 10px;" />
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- ============ FONT SETTINGS ============ -->
                    <tr>
                        <td colspan="2" style="padding: 15px 0 6px;">
                            <h2 style="margin:0; padding: 8px 12px; background:#2c3e50; color:#fff; border-radius:4px; font-size:14px;">
                                🔤 <?php esc_html_e('Font Settings', 'newspaper-social-media-photo-card'); ?>
                            </h2>
                        </td>
                    </tr>

                    <!-- Title Font -->
                    <tr>
                        <th scope="row" style="padding-left:20px;">
                            <label for="mjashik_npc_title_font"><?php esc_html_e('📝 Title Font', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <?php
                            $selected_title_font = get_option('mjashik_npc_title_font', 'SolaimanLipi');
                            ?>
                            <select id="mjashik_npc_title_font" name="mjashik_npc_title_font" style="font-size:14px;">
                                <?php foreach ($this->mjashik_get_font_list() as $font_key => $font_label) : ?>
                                    <option value="<?php echo esc_attr($font_key); ?>"
                                        style="font-family:<?php echo esc_attr($font_key); ?>, sans-serif;"
                                        <?php selected($selected_title_font, $font_key); ?>>
                                        <?php echo esc_html($font_label); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description"><?php esc_html_e('Font for the news title on the photo card. Default: SolaimanLipi', 'newspaper-social-media-photo-card'); ?></p>
                        </td>
                    </tr>

                    <!-- Date Font -->
                    <tr>
                        <th scope="row" style="padding-left:20px;">
                            <label for="mjashik_npc_date_font"><?php esc_html_e('📅 Date Badge Font', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <?php
                            $selected_date_font = get_option('mjashik_npc_date_font', 'SolaimanLipi');
                            ?>
                            <select id="mjashik_npc_date_font" name="mjashik_npc_date_font" style="font-size:14px;">
                                <?php foreach ($this->mjashik_get_font_list() as $font_key => $font_label) : ?>
                                    <option value="<?php echo esc_attr($font_key); ?>"
                                        style="font-family:<?php echo esc_attr($font_key); ?>, sans-serif;"
                                        <?php selected($selected_date_font, $font_key); ?>>
                                        <?php echo esc_html($font_label); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description"><?php esc_html_e('Font for the date badge on the photo card. Default: SolaimanLipi', 'newspaper-social-media-photo-card'); ?></p>
                        </td>
                    </tr>

                    <!-- ============ COLOR SETTINGS ============ -->
                    <tr>
                        <td colspan="2" style="padding: 15px 0 6px;">
                            <h2 style="margin:0; padding: 8px 12px; background:#2c3e50; color:#fff; border-radius:4px; font-size:14px;">
                                🎨 <?php esc_html_e('Color Settings', 'newspaper-social-media-photo-card'); ?>
                            </h2>
                        </td>
                    </tr>

                    <!-- Logo Shadow Color -->
                    <tr>
                        <th scope="row" style="padding-left:20px;">
                            <label for="mjashik_npc_logo_shadow_color"><?php esc_html_e('🖼️ Logo Shadow Color', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="color"
                                   id="mjashik_npc_logo_shadow_color"
                                   name="mjashik_npc_logo_shadow_color"
                                   value="<?php echo esc_attr(get_option('mjashik_npc_logo_shadow_color', '#000000')); ?>"
                                   style="width:60px; height:36px; padding:2px; cursor:pointer; border:1px solid #ccc; border-radius:4px;" />
                            <p class="description"><?php esc_html_e('Adds a faint drop shadow behind the logo (helps visibility on light backgrounds).', 'newspaper-social-media-photo-card'); ?></p>
                        </td>
                    </tr>

                    <!-- Title Area Colors -->
                    <tr>
                        <th scope="row" style="padding-left:20px;">
                            <label for="mjashik_npc_font_color"><?php esc_html_e('📝 Title Text Color', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="color"
                                   id="mjashik_npc_font_color"
                                   name="mjashik_npc_font_color"
                                   value="<?php echo esc_attr(get_option('mjashik_npc_font_color', '#ffffff')); ?>"
                                   style="width:60px; height:36px; padding:2px; cursor:pointer; border:1px solid #ccc; border-radius:4px;" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style="padding-left:20px;">
                            <label for="mjashik_npc_title_area_bg_color"><?php esc_html_e('📝 Title Area Background', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="color"
                                   id="mjashik_npc_title_area_bg_color"
                                   name="mjashik_npc_title_area_bg_color"
                                   value="<?php echo esc_attr(get_option('mjashik_npc_title_area_bg_color', '#AA0001')); ?>"
                                   style="width:60px; height:36px; padding:2px; cursor:pointer; border:1px solid #ccc; border-radius:4px;" />
                            <p class="description"><?php esc_html_e('Background color of the title/headline area.', 'newspaper-social-media-photo-card'); ?></p>
                        </td>
                    </tr>

                    <!-- Date Badge Colors -->
                    <tr>
                        <th scope="row" style="padding-left:20px;">
                            <label for="mjashik_npc_date_bg_color"><?php esc_html_e('📅 Date Badge Background', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="color"
                                   id="mjashik_npc_date_bg_color"
                                   name="mjashik_npc_date_bg_color"
                                   value="<?php echo esc_attr(get_option('mjashik_npc_date_bg_color', '#AA0001')); ?>"
                                   style="width:60px; height:36px; padding:2px; cursor:pointer; border:1px solid #ccc; border-radius:4px;" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style="padding-left:20px;">
                            <label for="mjashik_npc_date_text_color"><?php esc_html_e('📅 Date Badge Text Color', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="color"
                                   id="mjashik_npc_date_text_color"
                                   name="mjashik_npc_date_text_color"
                                   value="<?php echo esc_attr(get_option('mjashik_npc_date_text_color', '#ffffff')); ?>"
                                   style="width:60px; height:36px; padding:2px; cursor:pointer; border:1px solid #ccc; border-radius:4px;" />
                        </td>
                    </tr>

                    <!-- Footer Colors -->
                    <tr>
                        <th scope="row" style="padding-left:20px;">
                            <label for="mjashik_npc_footer_bg_color"><?php esc_html_e('🌐 Footer Background', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="color"
                                   id="mjashik_npc_footer_bg_color"
                                   name="mjashik_npc_footer_bg_color"
                                   value="<?php echo esc_attr(get_option('mjashik_npc_footer_bg_color', '#AA0001')); ?>"
                                   style="width:60px; height:36px; padding:2px; cursor:pointer; border:1px solid #ccc; border-radius:4px;" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style="padding-left:20px;">
                            <label for="mjashik_npc_footer_text_color"><?php esc_html_e('🌐 Footer Text Color', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="color"
                                   id="mjashik_npc_footer_text_color"
                                   name="mjashik_npc_footer_text_color"
                                   value="<?php echo esc_attr(get_option('mjashik_npc_footer_text_color', '#ffffff')); ?>"
                                   style="width:60px; height:36px; padding:2px; cursor:pointer; border:1px solid #ccc; border-radius:4px;" />
                        </td>
                    </tr>

                    <!-- Watermark Opacity -->
                    <tr>
                        <th scope="row" style="padding-left:20px;">
                            <label for="mjashik_npc_watermark_opacity"><?php esc_html_e('🔍 Watermark Opacity', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <?php $wm_opacity = (int) get_option('mjashik_npc_watermark_opacity', 8); ?>
                            <input type="range"
                                   id="mjashik_npc_watermark_opacity"
                                   name="mjashik_npc_watermark_opacity"
                                   value="<?php echo esc_attr($wm_opacity); ?>"
                                   min="0" max="30" step="1"
                                   style="width:200px; vertical-align:middle;" 
                                   oninput="document.getElementById('wm_opacity_val').textContent=this.value+'%'" />
                            <span id="wm_opacity_val" style="font-weight:600; margin-left:8px;"><?php echo esc_html($wm_opacity); ?>%</span>
                            <p class="description"><?php esc_html_e('Set to 0 to hide watermark. Default: 8%', 'newspaper-social-media-photo-card'); ?></p>
                        </td>
                    </tr>

                    
                    <tr>
                        <th scope="row">
                            <label for="mjashik_npc_title_font_size"><?php echo esc_html__('Title Font Size', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="number" 
                                   id="mjashik_npc_title_font_size" 
                                   name="mjashik_npc_title_font_size" 
                                   value="<?php echo esc_attr(get_option('mjashik_npc_title_font_size', '42')); ?>" 
                                   min="10" 
                                   max="100" />
                            <span class="description"><?php echo esc_html__('Font size in pixels (Default: 42)', 'newspaper-social-media-photo-card'); ?></span>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mjashik_npc_date_font_size"><?php echo esc_html__('Date Font Size', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="number" 
                                   id="mjashik_npc_date_font_size" 
                                   name="mjashik_npc_date_font_size" 
                                   value="<?php echo esc_attr(get_option('mjashik_npc_date_font_size', '18')); ?>" 
                                   min="10" 
                                   max="50" />
                            <span class="description"><?php echo esc_html__('Font size in pixels (Default: 18)', 'newspaper-social-media-photo-card'); ?></span>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="mjashik_npc_footer_font_size"><?php echo esc_html__('Website Text Size', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="number" 
                                   id="mjashik_npc_footer_font_size" 
                                   name="mjashik_npc_footer_font_size" 
                                   value="<?php echo esc_attr(get_option('mjashik_npc_footer_font_size', '22')); ?>" 
                                   min="10" 
                                   max="50" />
                            <span class="description"><?php echo esc_html__('Font size in pixels (Default: 22)', 'newspaper-social-media-photo-card'); ?></span>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mjashik_npc_date_format"><?php echo esc_html__('Date Format', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="mjashik_npc_date_format" 
                                   name="mjashik_npc_date_format" 
                                   value="<?php echo esc_attr(get_option('mjashik_npc_date_format', 'd F Y')); ?>" 
                                   class="regular-text" />
                            <p class="description">
                                <?php echo esc_html__('PHP date format. Example: d F Y', 'newspaper-social-media-photo-card'); ?>
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mjashik_npc_website_url"><?php echo esc_html__('Website URL', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="mjashik_npc_website_url" 
                                   name="mjashik_npc_website_url" 
                                   value="<?php echo esc_attr(get_option('mjashik_npc_website_url', 'www.hostbuybd.com')); ?>" 
                                   class="regular-text" 
                                   placeholder="www.yourwebsite.com" />
                            <p class="description">
                                <?php echo esc_html__('Website URL to display at the bottom of photo card', 'newspaper-social-media-photo-card'); ?>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- ============ SOCIAL MEDIA SETTINGS ============ -->
                    <tr>
                        <th scope="row" style="padding-top:20px;">
                            <label><?php esc_html_e('Social Media Badges', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td style="padding-top:20px;">
                            <!-- Hidden input to store JSON -->
                            <input type="hidden" id="mjashik_npc_social_links" name="mjashik_npc_social_links" value="<?php echo esc_attr(get_option('mjashik_npc_social_links', '[]')); ?>" />
                            
                            <!-- Container where repeating fields will be rendered by JS -->
                            <div id="mjashik_npc_social_repeater" style="display:flex; flex-direction:column; gap:10px; margin-bottom:15px;"></div>
                            
                            <button type="button" class="button" id="mjashik_npc_add_social_link">
                                <?php esc_html_e('+ Add Social Link', 'newspaper-social-media-photo-card'); ?>
                            </button>
                            <p class="description"><?php esc_html_e('Add icons and text to display next to the Website URL in the footer.', 'newspaper-social-media-photo-card'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mjashik_npc_show_download_button"><?php echo esc_html__('Show Download Button (Frontend)', 'newspaper-social-media-photo-card'); ?></label>
                        </th>
                        <td>
                            <select id="mjashik_npc_show_download_button" name="mjashik_npc_show_download_button">
                                <option value="yes" <?php selected(get_option('mjashik_npc_show_download_button', 'yes'), 'yes'); ?>><?php esc_html_e('Yes, show below posts', 'newspaper-social-media-photo-card'); ?></option>
                                <option value="no" <?php selected(get_option('mjashik_npc_show_download_button', 'yes'), 'no'); ?>><?php esc_html_e('No, admin only', 'newspaper-social-media-photo-card'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('If Yes, site visitors will see the generate button at the bottom of single news posts.', 'newspaper-social-media-photo-card'); ?></p>
                        </td>
                    </tr>
                </table>
            </form>

            <!-- Buttons Layout: Side by Side (valid HTML5) -->
            <div style="display:flex; align-items:center; gap:15px; margin-top:20px; padding:10px 0;">
                
                <!-- Save Button (Hooks into form via form attribute) -->
                <button type="submit" form="mjashik_npc_main_form" class="button button-primary">
                    <?php esc_html_e('Save Changes', 'newspaper-social-media-photo-card'); ?>
                </button>
                
                <!-- Reset Form -->
                <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=newspaper-social-media-photo-card')); ?>" style="margin:0;">
                    <button type="submit" name="mjashik_npc_reset_settings" value="1" class="button" onclick="return confirm('<?php esc_attr_e('Are you sure you want to reset all settings to their default values? This cannot be undone.', 'newspaper-social-media-photo-card'); ?>');">
                        <?php esc_html_e('Reset to Defaults', 'newspaper-social-media-photo-card'); ?>
                    </button>
                    <?php wp_nonce_field('mjashik_npc_reset_action', 'mjashik_npc_reset_nonce'); ?>
                </form>
                
            </div>
            
            </div><!-- /LEFT COLUMN -->

            <?php
            // ── Current settings for preview ──────────────────────────────
            $prev_logo         = get_option('mjashik_npc_logo_url');
            $prev_logo_shadow  = get_option('mjashik_npc_logo_shadow_color', '#000000');
            $prev_font_color   = get_option('mjashik_npc_font_color', '#ffffff');
            $prev_title_bg     = get_option('mjashik_npc_title_area_bg_color', '#AA0001');
            $prev_date_bg      = get_option('mjashik_npc_date_bg_color', '#AA0001');
            $prev_date_color   = get_option('mjashik_npc_date_text_color', '#ffffff');
            $prev_footer_bg    = get_option('mjashik_npc_footer_bg_color', '#AA0001');
            $prev_footer_color = get_option('mjashik_npc_footer_text_color', '#ffffff');
            $prev_title_size   = (int) get_option('mjashik_npc_title_font_size', 42);
            $prev_date_size    = (int) get_option('mjashik_npc_date_font_size', 18);
            $prev_footer_size  = (int) get_option('mjashik_npc_footer_font_size', 22);
            $prev_date_fmt     = get_option('mjashik_npc_date_format', 'd F Y');
            $prev_website      = get_option('mjashik_npc_website_url', 'www.hostbuybd.com');
            $prev_title_font   = get_option('mjashik_npc_title_font', 'SolaimanLipi');
            $prev_date_font    = get_option('mjashik_npc_date_font', 'SolaimanLipi');
            $prev_social_raw   = get_option('mjashik_npc_social_links', '[]');
            $prev_social       = json_decode($prev_social_raw, true);
            if (!is_array($prev_social)) $prev_social = array();
            $prev_wm_opacity   = ((int) get_option('mjashik_npc_watermark_opacity', 8)) / 100;

            // Demo values — these never change regardless of template
            $demo_title = 'আপনার প্রতিষ্ঠানের জন্য সেরা নিউসপেপার হোস্টিং কিনুন HOSTBUYBD.COM থেকে';
            $demo_date  = date_i18n($prev_date_fmt, current_time('timestamp'));

            // Scale: render card at 800×800 then CSS-scale to 400×400
            $scale    = 0.5;
            $card_w   = 800;
            $card_h   = 800;
            $footer_h = 70;
            $scaled_w = (int)($card_w * $scale);
            $scaled_h = (int)($card_h * $scale);

            // Pass SVG icon callable into template scope
            $admin_obj = $this;
            $mjashik_social_icon_fn = function($type, $color) use ($admin_obj) {
                return $admin_obj->mjashik_get_social_icon_svg($type, $color);
            };

            // Active template
            $active_tpl = MJASHIK_NPC_Template_Loader::get_active_template();

            // Bundle all preview vars
            $preview_vars = compact(
                'prev_logo', 'prev_logo_shadow', 'prev_font_color', 'prev_title_bg',
                'prev_date_bg', 'prev_date_color', 'prev_footer_bg', 'prev_footer_color',
                'prev_title_size', 'prev_date_size', 'prev_footer_size',
                'prev_title_font', 'prev_date_font',
                'prev_website', 'prev_social', 'prev_wm_opacity',
                'demo_title', 'demo_date',
                'card_w', 'card_h', 'scale', 'scaled_w', 'scaled_h', 'footer_h',
                'mjashik_social_icon_fn'
            );
            ?>

            <!-- RIGHT COLUMN: Preview (sticky) -->
            <div style="flex:0 0 420px; position:sticky; top:40px;">
                <h3 style="margin-top:0; margin-bottom:10px;">📸 <?php esc_html_e('Photo Card Preview', 'newspaper-social-media-photo-card'); ?></h3>
                <p class="description" style="margin-bottom:15px;"><?php esc_html_e('Save changes to refresh.', 'newspaper-social-media-photo-card'); ?></p>

            <div style="display:flex; flex-direction:column; gap:20px; align-items:flex-start;">

                <?php MJASHIK_NPC_Template_Loader::include_template($active_tpl, 'template-style.php', $preview_vars); ?>

                <!-- Info Box -->
                <div style="max-width:280px; align-self:center;">
                    <div style="background:#f8f9fa; border:1px solid #dee2e6; border-radius:8px; padding:20px;">
                        <h3 style="margin-top:0; color:#2c3e50;">📋 Preview Info</h3>
                        <ul style="margin:0; padding-left:18px; color:#555; line-height:2;">
                            <li><?php esc_html_e('Image expands when title is short', 'newspaper-social-media-photo-card'); ?></li>
                            <li><?php esc_html_e('Image shrinks as title grows', 'newspaper-social-media-photo-card'); ?></li>
                            <li><?php esc_html_e('Footer is always fixed at bottom', 'newspaper-social-media-photo-card'); ?></li>
                            <li><?php esc_html_e('Card size: 800 × 800 px PNG', 'newspaper-social-media-photo-card'); ?></li>
                        </ul>
                        <hr>
                        <p style="margin:0; color:#888; font-size:13px;"><?php esc_html_e('Save changes to refresh the preview.', 'newspaper-social-media-photo-card'); ?></p>
                    </div>
                </div>

            </div><!-- /.preview-cards -->
            </div><!-- /RIGHT COLUMN -->

            </div><!-- /.two-column-layout -->

        </div><!-- /.wrap -->
        <?php
    }
}
