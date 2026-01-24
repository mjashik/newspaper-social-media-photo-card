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
        
        // Background setting
        register_setting('mjashik_npc_settings', 'mjashik_npc_background_url');
        
        // Font color
        register_setting('mjashik_npc_settings', 'mjashik_npc_font_color');
        
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
                    
                    <tr>
                        <th scope="row">
                            <label><?php echo esc_html__('Background Image', 'news-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="mjashik_npc_background_url" 
                                   name="mjashik_npc_background_url" 
                                   value="<?php echo esc_attr(get_option('mjashik_npc_background_url')); ?>" 
                                   class="regular-text" />
                            <button type="button" class="button mjashik-upload-button" data-target="mjashik_npc_background_url">
                                <?php echo esc_html__('Upload Background', 'news-photo-card'); ?>
                            </button>
                            <div class="mjashik-image-preview">
                                <?php if (get_option('mjashik_npc_background_url')): ?>
                                    <img src="<?php echo esc_url(get_option('mjashik_npc_background_url')); ?>" style="max-width: 200px; margin-top: 10px;" />
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="mjashik_npc_font_color"><?php echo esc_html__('Font Color', 'news-photo-card'); ?></label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="mjashik_npc_font_color" 
                                   name="mjashik_npc_font_color" 
                                   value="<?php echo esc_attr(get_option('mjashik_npc_font_color', '#ffffff')); ?>" 
                                   class="mjashik-color-picker" />
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
                            <label for="mjashik_npc_show_download_button"><?php echo esc_html__('Show Download Button', 'news-photo-card'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" 
                                       id="mjashik_npc_show_download_button" 
                                       name="mjashik_npc_show_download_button" 
                                       value="yes" 
                                       <?php checked(get_option('mjashik_npc_show_download_button', 'yes'), 'yes'); ?> />
                                <?php echo esc_html__('Display download button on posts', 'news-photo-card'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
