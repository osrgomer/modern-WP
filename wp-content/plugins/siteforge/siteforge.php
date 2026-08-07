<?php
/**
 * Plugin Name: SiteForge
 * Description: Visual site editor for shops, restaurants, portfolios & more.
 * Version: 1.0.0
 * Author: SiteForge
 */

defined('ABSPATH') || exit;

define('SF_PATH', plugin_dir_path(__FILE__));
define('SF_URL', plugin_dir_url(__FILE__));
define('SF_VERSION', '1.0.0');

require_once SF_PATH . 'includes/post-types.php';
require_once SF_PATH . 'includes/api.php';
require_once SF_PATH . 'includes/admin.php';
require_once SF_PATH . 'includes/frontend.php';

register_activation_hook(__FILE__, 'sf_activate');
function sf_activate() {
    sf_register_post_types();
    flush_rewrite_rules();
}
