<?php
defined('ABSPATH') || exit;

add_action('rest_api_init', 'sf_register_routes');
function sf_register_routes() {
    register_rest_route('siteforge/v1', '/pages', [
        'methods'             => 'GET',
        'callback'            => 'sf_get_pages',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('siteforge/v1', '/page/(?P<id>\d+)', [
        [
            'methods'             => 'GET',
            'callback'            => 'sf_get_page',
            'permission_callback' => '__return_true',
        ],
        [
            'methods'             => 'POST',
            'callback'            => 'sf_save_page',
            'permission_callback' => function() { return current_user_can('edit_posts'); },
        ],
    ]);

    register_rest_route('siteforge/v1', '/page', [
        'methods'             => 'POST',
        'callback'            => 'sf_create_page',
        'permission_callback' => function() { return current_user_can('edit_posts'); },
    ]);

    register_rest_route('siteforge/v1', '/page/(?P<id>\d+)/delete', [
        'methods'             => 'POST',
        'callback'            => 'sf_delete_page',
        'permission_callback' => function() { return current_user_can('delete_posts'); },
    ]);

    register_rest_route('siteforge/v1', '/settings', [
        [
            'methods'             => 'GET',
            'callback'            => 'sf_get_settings',
            'permission_callback' => function() { return current_user_can('manage_options'); },
        ],
        [
            'methods'             => 'POST',
            'callback'            => 'sf_save_settings',
            'permission_callback' => function() { return current_user_can('manage_options'); },
        ],
    ]);
}

function sf_get_pages() {
    $posts = get_posts(['post_type' => 'sf_page', 'numberposts' => -1, 'post_status' => 'any']);
    return array_map('sf_format_page', $posts);
}

function sf_get_page($req) {
    $post = get_post($req['id']);
    if (!$post || $post->post_type !== 'sf_page') return new WP_Error('not_found', 'Page not found', ['status' => 404]);
    return sf_format_page($post);
}

function sf_format_page($post) {
    return [
        'id'       => $post->ID,
        'title'    => $post->post_title,
        'slug'     => $post->post_name,
        'status'   => $post->post_status,
        'sections' => json_decode(get_post_meta($post->ID, '_sf_sections', true) ?: '[]', true),
        'settings' => json_decode(get_post_meta($post->ID, '_sf_page_settings', true) ?: '{}', true),
        'url'      => get_permalink($post->ID),
    ];
}

function sf_save_page($req) {
    $id = $req['id'];
    $body = $req->get_json_params();

    if (isset($body['title'])) wp_update_post(['ID' => $id, 'post_title' => sanitize_text_field($body['title'])]);
    if (isset($body['status'])) wp_update_post(['ID' => $id, 'post_status' => sanitize_text_field($body['status'])]);
    if (isset($body['sections'])) update_post_meta($id, '_sf_sections', wp_json_encode($body['sections']));
    if (isset($body['settings'])) update_post_meta($id, '_sf_page_settings', wp_json_encode($body['settings']));

    return sf_get_page($req);
}

function sf_create_page($req) {
    $body = $req->get_json_params();
    $id = wp_insert_post([
        'post_type'   => 'sf_page',
        'post_title'  => sanitize_text_field($body['title'] ?? 'New Page'),
        'post_status' => 'draft',
        'post_name'   => sanitize_title($body['title'] ?? 'new-page'),
    ]);
    if (is_wp_error($id)) return $id;
    update_post_meta($id, '_sf_sections', '[]');
    update_post_meta($id, '_sf_page_settings', wp_json_encode(['theme' => 'light', 'font' => 'Inter']));
    return sf_format_page(get_post($id));
}

function sf_delete_page($req) {
    $post = get_post($req['id']);
    if (!$post || $post->post_type !== 'sf_page') return new WP_Error('not_found', 'Page not found', ['status' => 404]);
    wp_delete_post($req['id'], true);
    return ['success' => true];
}

function sf_get_settings() {
    return [
        'site_name'    => get_option('sf_site_name', get_bloginfo('name')),
        'primary_color'=> get_option('sf_primary_color', '#6366f1'),
        'font'         => get_option('sf_font', 'Inter'),
        'homepage'     => get_option('sf_homepage', 0),
    ];
}

function sf_save_settings($req) {
    $body = $req->get_json_params();
    foreach (['site_name', 'primary_color', 'font', 'homepage'] as $key) {
        if (isset($body[$key])) update_option('sf_' . $key, sanitize_text_field($body[$key]));
    }
    return sf_get_settings();
}
