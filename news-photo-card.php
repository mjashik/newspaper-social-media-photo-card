<?php
/**
 * Plugin Name: News Photo Card Generator
 * Plugin URI: https://yoursite.com
 * Description: Generate beautiful photo cards for news posts with logo, date, title, and background image
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yoursite.com
 * Text Domain: news-photo-card
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('MJASHIK_NPC_VERSION', '1.0.0');
define('MJASHIK_NPC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MJASHIK_NPC_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once MJASHIK_NPC_PLUGIN_DIR . 'includes/class-admin-settings.php';
require_once MJASHIK_NPC_PLUGIN_DIR . 'includes/class-image-generator.php';
require_once MJASHIK_NPC_PLUGIN_DIR . 'includes/class-post-integration.php';

/**
 * Initialize the plugin
 */
function mjashik_npc_init() {
    // Initialize admin settings
    if (is_admin()) {
        new MJASHIK_NPC_Admin_Settings();
    }
    
    // Initialize post integration
    new MJASHIK_NPC_Post_Integration();
}
add_action('plugins_loaded', 'mjashik_npc_init');

/**
 * Activation hook
 */
function mjashik_npc_activate() {
    // Set default options
    $defaults = array(
        'logo_url' => '',
        'background_url' => '',
        'font_color' => '#ffffff',
        'date_format' => 'd F Y',
        'show_download_button' => 'yes'
    );
    
    foreach ($defaults as $key => $value) {
        if (get_option('mjashik_npc_' . $key) === false) {
            add_option('mjashik_npc_' . $key, $value);
        }
    }
}
register_activation_hook(__FILE__, 'mjashik_npc_activate');

/**
 * Deactivation hook
 */
function mjashik_npc_deactivate() {
    // Cleanup if needed
}
register_deactivation_hook(__FILE__, 'mjashik_npc_deactivate');
