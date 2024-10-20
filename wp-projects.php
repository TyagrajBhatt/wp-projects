<?php
/**
 * Plugin Name: WP Projects
 * Plugin URI: https://example.com/wp-projects
 * Description: A plugin to manage and display projects using a custom post type and shortcode.
 * Version: 1.0.0
 * Author: Tyagraj Bhatt
 * Author URI: https://example.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wp-projects
 * Domain Path: /languages
 */

// If this file is called directly, abort.

if (!defined('WPINC')) {
    die;
}

define('WP_PROJECTS_VERSION', '1.4.0');
define('WP_PROJECTS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP_PROJECTS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once WP_PROJECTS_PLUGIN_DIR . 'includes/class-wp-projects-loader.php';
require_once WP_PROJECTS_PLUGIN_DIR . 'includes/class-wp-projects-post-type.php';
require_once WP_PROJECTS_PLUGIN_DIR . 'includes/class-wp-projects-shortcode.php';
require_once WP_PROJECTS_PLUGIN_DIR . 'admin/class-wp-projects-admin.php';

// Initialize the plugin
function wp_projects_init() {
    $loader = new WP_Projects_Loader();
    $loader->run();
}


wp_projects_init();