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
        add_action('wp_enqueue_scripts', array($this, 'mjashik_enqueue_scripts'));
        add_filter('the_content', array($this, 'mjashik_add_download_button'));
        add_action('wp_ajax_mjashik_generate_photo_card', array($this, 'mjashik_ajax_generate_card'));
        add_action('wp_ajax_nopriv_mjashik_generate_photo_card', array($this, 'mjashik_ajax_generate_card'));
    }
    
    /**
     * Enqueue frontend scripts
     */
    public function mjashik_enqueue_scripts() {
        if (is_single()) {
            wp_enqueue_style(
                'mjashik-npc-frontend',
                MJASHIK_NPC_PLUGIN_URL . 'assets/css/frontend.css',
                array(),
                MJASHIK_NPC_VERSION
            );
            
            wp_enqueue_script(
                'mjashik-npc-frontend',
                MJASHIK_NPC_PLUGIN_URL . 'assets/js/frontend.js',
                array('jquery'),
                MJASHIK_NPC_VERSION,
                true
            );
            
            wp_localize_script('mjashik-npc-frontend', 'mjashikNPC', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('mjashik_npc_nonce'),
                'post_id' => get_the_ID(),
                'generating_text' => __('Generating...', 'news-photo-card'),
                'download_text' => __('Download Photo Card', 'news-photo-card'),
                'error_text' => __('Error generating photo card', 'news-photo-card')
            ));
        }
    }
    
    /**
     * Add download button to post content
     */
    public function mjashik_add_download_button($content) {
        if (!is_single() || get_option('mjashik_npc_show_download_button') !== 'yes') {
            return $content;
        }
        
        $button_html = '
        <div class="mjashik-photo-card-section">
            <button class="mjashik-download-card-btn" data-post-id="' . get_the_ID() . '">
                <span class="mjashik-btn-icon">📸</span>
                <span class="mjashik-btn-text">' . __('Download Photo Card', 'news-photo-card') . '</span>
            </button>
            <div class="mjashik-card-loading" style="display: none;">
                <div class="mjashik-spinner"></div>
                <p>' . __('Generating your photo card...', 'news-photo-card') . '</p>
            </div>
        </div>';
        
        return $content . $button_html;
    }
    
    /**
     * AJAX handler for generating photo card
     */
    public function mjashik_ajax_generate_card() {
        check_ajax_referer('mjashik_npc_nonce', 'nonce');
        
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        
        if (!$post_id) {
            wp_send_json_error(array('message' => __('Invalid post ID', 'news-photo-card')));
        }
        
        // Generate the card
        $image_url = MJASHIK_NPC_Image_Generator::mjashik_generate_card($post_id);
        
        if ($image_url) {
            wp_send_json_success(array(
                'image_url' => $image_url,
                'message' => __('Photo card generated successfully', 'news-photo-card')
            ));
        } else {
            wp_send_json_error(array('message' => __('Failed to generate photo card', 'news-photo-card')));
        }
    }
}
