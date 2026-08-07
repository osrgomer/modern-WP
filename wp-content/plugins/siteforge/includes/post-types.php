<?php
defined('ABSPATH') || exit;

add_action('init', 'sf_register_post_types');
function sf_register_post_types() {
    register_post_type('sf_page', [
        'labels'        => ['name' => 'SF Pages', 'singular_name' => 'SF Page'],
        'public'        => true,
        'show_in_menu'  => false,
        'show_in_rest'  => true,
        'supports'      => ['title', 'custom-fields'],
        'rewrite'       => ['slug' => 'p'],
    ]);

    register_post_type('sf_widget', [
        'labels'       => ['name' => 'SF Widgets', 'singular_name' => 'SF Widget'],
        'public'       => false,
        'show_in_rest' => true,
        'supports'     => ['title', 'custom-fields'],
    ]);
}
