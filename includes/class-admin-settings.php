<?php
/**
 * Admin Settings Class
 * Handles the admin settings page for News Photo Card plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class MJASHIK_NPC_Admin_Settings {
    
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
            __('News Photo Card', 'news-photo-card'),
            __('Photo Card', 'news-photo-card'),
            'manage_options',
            'news-photo-card',
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
        register_setting('mjashik_npc_settings', 'mjashik_npc_logo_url');
        
        // Title font color
        register_setting('mjashik_npc_settings', 'mjashik_npc_font_color');
        
        // Title area background color
        register_setting('mjashik_npc_settings', 'mjashik_npc_title_area_bg_color');

        // Date badge background color
        register_setting('mjashik_npc_settings', 'mjashik_npc_date_bg_color');

        // Date badge text color
        register_setting('mjashik_npc_settings', 'mjashik_npc_date_text_color');

        // Footer background color
        register_setting('mjashik_npc_settings', 'mjashik_npc_footer_bg_color');

        // Footer text color
        register_setting('mjashik_npc_settings', 'mjashik_npc_footer_text_color');

        // Date format
        register_setting('mjashik_npc_settings', 'mjashik_npc_date_format');
        
        // Show download button
        register_setting('mjashik_npc_settings', 'mjashik_npc_show_download_button');
        
        // Title font size
        register_setting('mjashik_npc_settings', 'mjashik_npc_title_font_size');
        
        // Date font size
        register_setting('mjashik_npc_settings', 'mjashik_npc_date_font_size');
        
        // Website URL
        register_setting('mjashik_npc_settings', 'mjashik_npc_website_url');
        
        // Watermark opacity
        register_setting('mjashik_npc_settings', 'mjashik_npc_watermark_opacity');
    }
    
    /**
     * Process reset to defaults
     */
    public function mjashik_process_settings_reset() {
        if (isset($_POST['mjashik_npc_reset_settings']) && isset($_POST['mjashik_npc_reset_nonce'])) {
            if (!wp_verify_nonce($_POST['mjashik_npc_reset_nonce'], 'mjashik_npc_reset_action')) {
                wp_die(__('Security check failed', 'news-photo-card'));
            }
            
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have permission', 'news-photo-card'));
            }
            
            // Delete all plugin options EXECPT the logo
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
            delete_option('mjashik_npc_website_url');
            delete_option('mjashik_npc_watermark_opacity');
            
            add_settings_error('mjashik_npc_messages', 'mjashik_npc_message', __('Settings restored to default values', 'news-photo-card'), 'updated');
        }
    }
    
    /**
     * Enqueue admin scripts
     */
    public function mjashik_enqueue_admin_scripts($hook) {
        if ($hook !== 'toplevel_page_news-photo-card') {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        wp_enqueue_style(
            'mjashik-npc-admin',
            MJASHIK_NPC_PLUGIN_URL . 'assets/css/admin.css',
            array(),
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
            <h1><?php echo esc_html__('News Photo Card Settings', 'news-photo-card'); ?></h1>

            <!-- Two-column layout: Settings Left, Preview Right -->
            <div style="display:flex; gap:30px; align-items:flex-start; margin-top:15px;">

            <!-- LEFT COLUMN: Settings Form -->
            <div style="flex:1 1 0; min-width:400px;">
            <form method="post" action="options.php">
                <?php settings_fields('mjashik_npc_settings'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html__('Logo Image', 'news-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="mjashik_npc_logo_url" 
                                   name="mjashik_npc_logo_url" 
                                   value="<?php echo esc_attr(get_option('mjashik_npc_logo_url')); ?>" 
                                   class="regular-text" />
                            <button type="button" class="button mjashik-upload-button" data-target="mjashik_npc_logo_url">
                                <?php echo esc_html__('Upload Logo', 'news-photo-card'); ?>
                            </button>
                            <div class="mjashik-image-preview">
                                <?php if (get_option('mjashik_npc_logo_url')): ?>
                                    <img src="<?php echo esc_url(get_option('mjashik_npc_logo_url')); ?>" style="max-width: 200px; margin-top: 10px;" />
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- ============ COLOR SETTINGS ============ -->
                    <tr>
                        <td colspan="2" style="padding: 15px 0 6px;">
                            <h2 style="margin:0; padding: 8px 12px; background:#2c3e50; color:#fff; border-radius:4px; font-size:14px;">
                                🎨 <?php esc_html_e('Color Settings', 'news-photo-card'); ?>
                            </h2>
                        </td>
                    </tr>

                    <!-- Title Area Colors -->
                    <tr>
                        <th scope="row" style="padding-left:20px;">
                            <label for="mjashik_npc_font_color"><?php esc_html_e('📝 Title Text Color', 'news-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="color"
                                   id="mjashik_npc_font_color"
                                   name="mjashik_npc_font_color"
                                   value="<?php echo esc_attr(get_option('mjashik_npc_font_color', '#1a1a1a')); ?>"
                                   style="width:60px; height:36px; padding:2px; cursor:pointer; border:1px solid #ccc; border-radius:4px;" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style="padding-left:20px;">
                            <label for="mjashik_npc_title_area_bg_color"><?php esc_html_e('📝 Title Area Background', 'news-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="color"
                                   id="mjashik_npc_title_area_bg_color"
                                   name="mjashik_npc_title_area_bg_color"
                                   value="<?php echo esc_attr(get_option('mjashik_npc_title_area_bg_color', '#ffffff')); ?>"
                                   style="width:60px; height:36px; padding:2px; cursor:pointer; border:1px solid #ccc; border-radius:4px;" />
                            <p class="description"><?php esc_html_e('Background color of the title/headline area.', 'news-photo-card'); ?></p>
                        </td>
                    </tr>

                    <!-- Date Badge Colors -->
                    <tr>
                        <th scope="row" style="padding-left:20px;">
                            <label for="mjashik_npc_date_bg_color"><?php esc_html_e('📅 Date Badge Background', 'news-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="color"
                                   id="mjashik_npc_date_bg_color"
                                   name="mjashik_npc_date_bg_color"
                                   value="<?php echo esc_attr(get_option('mjashik_npc_date_bg_color', '#e74c3c')); ?>"
                                   style="width:60px; height:36px; padding:2px; cursor:pointer; border:1px solid #ccc; border-radius:4px;" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style="padding-left:20px;">
                            <label for="mjashik_npc_date_text_color"><?php esc_html_e('📅 Date Badge Text Color', 'news-photo-card'); ?></label>
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
                            <label for="mjashik_npc_footer_bg_color"><?php esc_html_e('🌐 Footer Background', 'news-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="color"
                                   id="mjashik_npc_footer_bg_color"
                                   name="mjashik_npc_footer_bg_color"
                                   value="<?php echo esc_attr(get_option('mjashik_npc_footer_bg_color', '#2c3e50')); ?>"
                                   style="width:60px; height:36px; padding:2px; cursor:pointer; border:1px solid #ccc; border-radius:4px;" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style="padding-left:20px;">
                            <label for="mjashik_npc_footer_text_color"><?php esc_html_e('🌐 Footer Text Color', 'news-photo-card'); ?></label>
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
                            <label for="mjashik_npc_watermark_opacity"><?php esc_html_e('🔍 Watermark Opacity', 'news-photo-card'); ?></label>
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
                            <p class="description"><?php esc_html_e('Set to 0 to hide watermark. Default: 8%', 'news-photo-card'); ?></p>
                        </td>
                    </tr>

                    
                    <tr>
                        <th scope="row">
                            <label for="mjashik_npc_title_font_size"><?php echo esc_html__('Title Font Size', 'news-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="number" 
                                   id="mjashik_npc_title_font_size" 
                                   name="mjashik_npc_title_font_size" 
                                   value="<?php echo esc_attr(get_option('mjashik_npc_title_font_size', '32')); ?>" 
                                   min="10" 
                                   max="100" />
                            <span class="description"><?php echo esc_html__('Font size in pixels', 'news-photo-card'); ?></span>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mjashik_npc_date_font_size"><?php echo esc_html__('Date Font Size', 'news-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="number" 
                                   id="mjashik_npc_date_font_size" 
                                   name="mjashik_npc_date_font_size" 
                                   value="<?php echo esc_attr(get_option('mjashik_npc_date_font_size', '20')); ?>" 
                                   min="10" 
                                   max="50" />
                            <span class="description"><?php echo esc_html__('Font size in pixels', 'news-photo-card'); ?></span>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mjashik_npc_date_format"><?php echo esc_html__('Date Format', 'news-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="mjashik_npc_date_format" 
                                   name="mjashik_npc_date_format" 
                                   value="<?php echo esc_attr(get_option('mjashik_npc_date_format', 'd F Y')); ?>" 
                                   class="regular-text" />
                            <p class="description">
                                <?php echo esc_html__('PHP date format. Example: d F Y', 'news-photo-card'); ?>
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mjashik_npc_website_url"><?php echo esc_html__('Website URL', 'news-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="mjashik_npc_website_url" 
                                   name="mjashik_npc_website_url" 
                                   value="<?php echo esc_attr(get_option('mjashik_npc_website_url', '')); ?>" 
                                   class="regular-text" 
                                   placeholder="www.yourwebsite.com" />
                            <p class="description">
                                <?php echo esc_html__('Website URL to display at the bottom of photo card', 'news-photo-card'); ?>
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mjashik_npc_show_download_button"><?php echo esc_html__('Show Download Button (Frontend)', 'news-photo-card'); ?></label>
                        </th>
                        <td>
                            <select id="mjashik_npc_show_download_button" name="mjashik_npc_show_download_button">
                                <option value="yes" <?php selected(get_option('mjashik_npc_show_download_button', 'yes'), 'yes'); ?>><?php esc_html_e('Yes, show below posts', 'news-photo-card'); ?></option>
                                <option value="no" <?php selected(get_option('mjashik_npc_show_download_button', 'yes'), 'no'); ?>><?php esc_html_e('No, admin only', 'news-photo-card'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('If Yes, site visitors will see the generate button at the bottom of single news posts.', 'news-photo-card'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <div style="display:flex; align-items:center; gap:15px; margin-top:20px;">
                    <?php submit_button('', 'primary', 'submit', false); ?>
                    
                    <button type="submit" name="mjashik_npc_reset_settings" value="1" class="button" onclick="return confirm('<?php esc_attr_e('Are you sure you want to reset all settings to their default values? This cannot be undone.', 'news-photo-card'); ?>');">
                        <?php esc_html_e('Reset to Defaults', 'news-photo-card'); ?>
                    </button>
                    <?php wp_nonce_field('mjashik_npc_reset_action', 'mjashik_npc_reset_nonce'); ?>
                </div>
            </form>
            </div><!-- /LEFT COLUMN -->

            <?php
            // Current settings for preview
            $prev_logo         = get_option('mjashik_npc_logo_url');
            $prev_font_color   = get_option('mjashik_npc_font_color', '#1a1a1a');
            $prev_title_bg     = get_option('mjashik_npc_title_area_bg_color', '#ffffff');
            $prev_date_bg      = get_option('mjashik_npc_date_bg_color', '#e74c3c');
            $prev_date_color   = get_option('mjashik_npc_date_text_color', '#ffffff');
            $prev_footer_bg    = get_option('mjashik_npc_footer_bg_color', '#2c3e50');
            $prev_footer_color = get_option('mjashik_npc_footer_text_color', '#ffffff');
            $prev_title_size   = (int) get_option('mjashik_npc_title_font_size', 42);
            $prev_date_fmt     = get_option('mjashik_npc_date_format', 'd F Y');
            $prev_website      = get_option('mjashik_npc_website_url', '');

            // Demo values
            $demo_title = 'সুপার ফাস্ট নিউজপেপার হোস্টিং কিনুন HOSTBUY.BD থেকে';
            $demo_date  = date_i18n($prev_date_fmt, current_time('timestamp'));

            // Scale: render card at 800×800 then CSS-scale to 400×400
            $scale    = 0.5;
            $card_w   = 800;
            $card_h   = 800;
            $footer_h = 70;
            $scaled_w = (int)($card_w * $scale);
            $scaled_h = (int)($card_h * $scale);

            // Title area bg
            $title_bg_style = "background-color:" . esc_attr($prev_title_bg) . ";";
            ?>

            <!-- RIGHT COLUMN: Preview (sticky) -->
            <div style="flex:0 0 420px; position:sticky; top:40px;">
                <h3 style="margin-top:0; margin-bottom:10px;">📸 <?php esc_html_e('Photo Card Preview', 'news-photo-card'); ?></h3>
                <p class="description" style="margin-bottom:15px;"><?php esc_html_e('Save changes to refresh.', 'news-photo-card'); ?></p>

            <div style="display:flex; flex-direction:column; gap:20px; align-items:flex-start;">

                <!-- Outer wrapper holds scaled space (400×400) -->
                <div style="width:<?php echo $scaled_w; ?>px; height:<?php echo $scaled_h; ?>px; flex-shrink:0; position:relative;">
                    <!-- Scale wrapper (800×800 → 50%) -->
                    <div style="transform-origin:top left; transform:scale(<?php echo $scale; ?>); width:<?php echo $card_w; ?>px; height:<?php echo $card_h; ?>px; box-shadow:0 8px 40px rgba(0,0,0,0.22); border-radius:6px; overflow:hidden; position:absolute; top:0; left:0;">
                        <!-- Card: flex column, image grows, title auto, footer fixed -->
                        <div style="width:<?php echo $card_w; ?>px; height:<?php echo $card_h; ?>px; position:relative; overflow:hidden; font-family:'Noto Sans Bengali',sans-serif; background:#fff; display:flex; flex-direction:column;">

                            <!-- 1. IMAGE AREA — flex:1 fills remaining space -->
                            <div style="position:relative; width:100%; flex:1 1 auto; min-height:200px; overflow:hidden; background:linear-gradient(135deg,#dde3ea 0%,#b2bec3 100%);">

                                <!-- Gradient overlay — z-index:10 -->
                                <div style="position:absolute; bottom:0; left:0; width:100%; height:160px; background:linear-gradient(to top,rgba(0,0,0,0.65),transparent); z-index:10;"></div>

                                <!-- Logo — z-index:30, always on top -->
                                <div style="position:absolute; top:28px; left:28px; z-index:30; filter:drop-shadow(0 4px 6px rgba(0,0,0,0.45));">
                                    <?php if ($prev_logo): ?>
                                        <img src="<?php echo esc_url($prev_logo); ?>" style="height:auto; width:auto; max-width:240px; display:block;" />
                                    <?php else: ?>
                                        <div style="background:rgba(255,255,255,0.9); color:#2c3e50; padding:8px 18px; font-size:24px; font-weight:700; border-radius:4px;">YOUR LOGO</div>
                                    <?php endif; ?>
                                </div>

                                <!-- Date badge — z-index:30 -->
                                <div style="position:absolute; top:28px; right:28px; background:<?php echo esc_attr($prev_date_bg); ?>; color:<?php echo esc_attr($prev_date_color); ?>; padding:10px 22px; font-weight:bold; font-size:18px; border-radius:50px; box-shadow:0 4px 12px rgba(0,0,0,0.3); z-index:30; border:2px solid rgba(255,255,255,0.5);">
                                    <?php echo esc_html($demo_date); ?>
                                </div>
                            </div>


                            <!-- 2. TITLE AREA — flex:0 auto height, custom bg & text color -->
                            <div style="position:relative; width:100%; flex:0 0 auto; border-top:5px solid <?php echo esc_attr($prev_date_bg); ?>; box-sizing:border-box; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:28px 50px; text-align:center; overflow:hidden; <?php echo $title_bg_style; ?>">
                                <!-- Watermark logo -->
                                <?php if ($prev_logo): ?>
                                <?php $wm_opac_val = ((int) get_option('mjashik_npc_watermark_opacity', 8)) / 100; ?>
                                <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); opacity:<?php echo esc_attr($wm_opac_val); ?>; width:55%; z-index:2;">
                                    <img src="<?php echo esc_url($prev_logo); ?>" style="width:100%; height:auto;" />
                                </div>
                                <?php endif; ?>

                                <!-- Headline -->
                                <div style="position:relative; z-index:10; width:100%;">
                                    <h1 style="margin:0; padding:0; font-size:<?php echo $prev_title_size; ?>px; line-height:1.5; font-weight:700; color:<?php echo esc_attr($prev_font_color); ?>; width:100%; text-shadow:0 1px 2px rgba(0,0,0,0.06);">
                                        <?php echo esc_html($demo_title); ?>
                                    </h1>
                                </div>
                            </div>

                            <!-- 3. FOOTER — fixed height, custom colors -->
                            <div style="width:100%; height:<?php echo $footer_h; ?>px; background:<?php echo esc_attr($prev_footer_bg); ?>; color:<?php echo esc_attr($prev_footer_color); ?>; display:flex; align-items:center; justify-content:center; font-size:22px; font-weight:600; letter-spacing:1.5px; flex:0 0 <?php echo $footer_h; ?>px; position:relative; overflow:hidden;">
                                <div style="position:absolute; top:0; left:0; width:100%; height:4px; background:rgba(255,255,255,0.1);"></div>
                                <span style="text-shadow:0 2px 4px rgba(0,0,0,0.2);"><?php echo esc_html($prev_website ?: 'www.yourwebsite.com'); ?></span>
                            </div>

                        </div>
                    </div><!-- /.scale-wrapper -->
                </div><!-- /.outer-wrapper -->

                <!-- Info Box -->
                <div style="max-width:280px; align-self:center;">
                    <div style="background:#f8f9fa; border:1px solid #dee2e6; border-radius:8px; padding:20px;">
                        <h3 style="margin-top:0; color:#2c3e50;">📋 Preview Info</h3>
                        <ul style="margin:0; padding-left:18px; color:#555; line-height:2;">
                            <li><?php esc_html_e('Image expands when title is short', 'news-photo-card'); ?></li>
                            <li><?php esc_html_e('Image shrinks as title grows', 'news-photo-card'); ?></li>
                            <li><?php esc_html_e('Footer is always fixed at bottom', 'news-photo-card'); ?></li>
                            <li><?php esc_html_e('Card size: 800 × 800 px PNG', 'news-photo-card'); ?></li>
                        </ul>
                        <hr>
                        <p style="margin:0; color:#888; font-size:13px;"><?php esc_html_e('Save changes to refresh the preview.', 'news-photo-card'); ?></p>
                    </div>
                </div>

            </div><!-- /.preview-cards -->
            </div><!-- /RIGHT COLUMN -->

            </div><!-- /.two-column-layout -->

        </div><!-- /.wrap -->
        <?php
    }
}
