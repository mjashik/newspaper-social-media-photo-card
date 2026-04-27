<?php
/**
 * Template Loader Class
 * Discovers and loads Photo Card templates from the /templates/ directory.
 *
 * Newspaper Social Media Photo Card Plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

class MJASHIK_NPC_Template_Loader {

    /**
     * Absolute path to the templates directory
     */
    private static $templates_dir;

    /**
     * Initialise paths
     */
    public static function init() {
        self::$templates_dir = MJASHIK_NPC_PLUGIN_DIR . 'templates/';
    }

    /**
     * Discover all available templates by scanning the /templates/ directory.
     * Each template must have a template-info.php that returns an array.
     *
     * @return array  slug => info array
     */
    public static function get_available_templates() {
        $templates = array();

        if (!is_dir(self::$templates_dir)) {
            return $templates;
        }

        $dirs = glob(self::$templates_dir . '*', GLOB_ONLYDIR);
        if (empty($dirs)) {
            return $templates;
        }

        foreach ($dirs as $dir) {
            $info_file = trailingslashit($dir) . 'template-info.php';
            if (!file_exists($info_file)) {
                continue;
            }
            $info = include $info_file;
            if (is_array($info) && !empty($info['slug'])) {
                $templates[$info['slug']] = $info;
            }
        }

        return $templates;
    }

    /**
     * Get the currently active template slug (saved option).
     * Falls back to 'template-1' if nothing is set.
     *
     * @return string
     */
    public static function get_active_template() {
        return get_option('mjashik_npc_active_template', 'template-1');
    }

    /**
     * Get the full path to a specific file inside a template folder.
     *
     * @param string $template_slug
     * @param string $filename   e.g. 'template-style.php' or 'template-card.php'
     * @return string|false      Absolute path or false if not found
     */
    public static function get_template_file($template_slug, $filename) {
        $path = self::$templates_dir . sanitize_file_name($template_slug) . '/' . $filename;
        return file_exists($path) ? $path : false;
    }

    /**
     * Include a template file with the given variables available in scope.
     *
     * @param string $template_slug
     * @param string $filename
     * @param array  $vars         Variables to extract into the template scope
     * @return bool  Whether the file was found and included
     */
    public static function include_template($template_slug, $filename, $vars = array()) {
        $path = self::get_template_file($template_slug, $filename);
        if (!$path) {
            // Graceful fallback to template-1
            $path = self::get_template_file('template-1', $filename);
        }
        if (!$path) {
            return false;
        }
        if (!empty($vars)) {
            extract($vars, EXTR_SKIP); // phpcs:ignore WordPress.PHP.DontExtract
        }
        include $path;
        return true;
    }
}

// Initialise immediately so $templates_dir is always set after the file is loaded
MJASHIK_NPC_Template_Loader::init();
