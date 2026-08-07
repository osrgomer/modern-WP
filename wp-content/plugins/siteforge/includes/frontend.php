<?php
defined('ABSPATH') || exit;

add_filter('template_include', 'sf_template_include');
function sf_template_include($template) {
    if (is_singular('sf_page')) {
        return SF_PATH . 'includes/template-page.php';
    }
    return $template;
}

add_action('wp_enqueue_scripts', 'sf_frontend_assets');
function sf_frontend_assets() {
    if (!is_singular('sf_page')) return;
    wp_enqueue_style('sf-frontend', SF_URL . 'assets/css/frontend.css', [], SF_VERSION);
    $settings = [
        'primary_color' => get_option('sf_primary_color', '#6366f1'),
        'font'          => get_option('sf_font', 'Inter'),
    ];
    wp_add_inline_style('sf-frontend', ":root { --sf-primary: {$settings['primary_color']}; --sf-font: '{$settings['font']}', sans-serif; }");
}
