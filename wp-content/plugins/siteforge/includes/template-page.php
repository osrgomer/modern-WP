<?php
defined('ABSPATH') || exit;

$post     = get_post();
$sections = json_decode(get_post_meta($post->ID, '_sf_sections', true) ?: '[]', true);
$ps       = json_decode(get_post_meta($post->ID, '_sf_page_settings', true) ?: '{}', true);
$font     = $ps['font'] ?? get_option('sf_font', 'Inter');
$theme    = $ps['theme'] ?? 'light';
?><!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="<?php echo esc_attr($theme); ?>">
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo esc_html($post->post_title); ?> — <?php bloginfo('name'); ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($font); ?>:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<?php wp_head(); ?>
</head>
<body style="font-family:'<?php echo esc_attr($font); ?>',sans-serif">
<?php foreach ($sections as $section): ?>
    <?php sf_render_section($section); ?>
<?php endforeach; ?>
<?php wp_footer(); ?>
</body>
</html>
<?php

function sf_render_section($s) {
    $type = $s['type'] ?? '';
    $d    = $s['data'] ?? [];
    $bg   = $d['bg'] ?? '';
    $style = $bg ? "style=\"background:{$bg}\"" : '';
    echo "<section class=\"sf-section sf-{$type}\" {$style}>";
    switch ($type) {
        case 'hero':        sf_section_hero($d); break;
        case 'products':    sf_section_products($d); break;
        case 'food_menu':   sf_section_food_menu($d); break;
        case 'features':    sf_section_features($d); break;
        case 'gallery':     sf_section_gallery($d); break;
        case 'testimonials':sf_section_testimonials($d); break;
        case 'cta':         sf_section_cta($d); break;
        case 'contact':     sf_section_contact($d); break;
        case 'team':        sf_section_team($d); break;
        case 'stats':       sf_section_stats($d); break;
        case 'pricing':     sf_section_pricing($d); break;
        case 'html':        echo wp_kses_post($d['html'] ?? ''); break;
    }
    echo '</section>';
}

function sf_section_hero($d) {
    $title    = esc_html($d['title'] ?? 'Welcome to Our Site');
    $subtitle = esc_html($d['subtitle'] ?? 'We make amazing things happen');
    $btn_text = esc_html($d['btn_text'] ?? 'Get Started');
    $btn_url  = esc_url($d['btn_url'] ?? '#');
    $img      = esc_url($d['image'] ?? '');
    $align    = $d['align'] ?? 'center';
    echo "<div class=\"sf-hero-inner sf-align-{$align}\">";
    if ($img) echo "<div class=\"sf-hero-img\"><img src=\"{$img}\" alt=\"\"></div>";
    echo "<div class=\"sf-hero-content\"><h1>{$title}</h1><p>{$subtitle}</p>";
    if ($btn_text) echo "<a href=\"{$btn_url}\" class=\"sf-btn-hero\">{$btn_text}</a>";
    echo '</div></div>';
}

function sf_section_products($d) {
    $title    = esc_html($d['title'] ?? 'Our Products');
    $products = $d['items'] ?? [];
    echo "<div class=\"sf-container\"><h2 class=\"sf-section-title\">{$title}</h2><div class=\"sf-products-grid\">";
    foreach ($products as $p) {
        $name  = esc_html($p['name'] ?? '');
        $price = esc_html($p['price'] ?? '');
        $img   = esc_url($p['image'] ?? '');
        $desc  = esc_html($p['desc'] ?? '');
        echo "<div class=\"sf-product-card\">";
        if ($img) echo "<img src=\"{$img}\" alt=\"{$name}\">";
        echo "<div class=\"sf-product-info\"><h3>{$name}</h3><p>{$desc}</p><span class=\"sf-price\">{$price}</span></div></div>";
    }
    echo '</div></div>';
}

function sf_section_food_menu($d) {
    $title    = esc_html($d['title'] ?? 'Our Menu');
    $items    = $d['items'] ?? [];
    $cats     = array_unique(array_column($items, 'category'));
    echo "<div class=\"sf-container\"><h2 class=\"sf-section-title\">{$title}</h2>";
    foreach ($cats as $cat) {
        echo "<div class=\"sf-menu-category\"><h3>" . esc_html($cat) . "</h3><div class=\"sf-menu-items\">";
        foreach ($items as $item) {
            if (($item['category'] ?? '') !== $cat) continue;
            $name  = esc_html($item['name'] ?? '');
            $desc  = esc_html($item['desc'] ?? '');
            $price = esc_html($item['price'] ?? '');
            $img   = esc_url($item['image'] ?? '');
            echo "<div class=\"sf-menu-item\">";
            if ($img) echo "<img src=\"{$img}\" alt=\"{$name}\">";
            echo "<div class=\"sf-menu-item-info\"><h4>{$name}</h4><p>{$desc}</p></div><span class=\"sf-price\">{$price}</span></div>";
        }
        echo '</div></div>';
    }
    echo '</div>';
}

function sf_section_features($d) {
    $title    = esc_html($d['title'] ?? 'Why Choose Us');
    $features = $d['items'] ?? [];
    echo "<div class=\"sf-container\"><h2 class=\"sf-section-title\">{$title}</h2><div class=\"sf-features-grid\">";
    foreach ($features as $f) {
        $icon  = esc_html($f['icon'] ?? '✨');
        $title = esc_html($f['title'] ?? '');
        $desc  = esc_html($f['desc'] ?? '');
        echo "<div class=\"sf-feature-card\"><div class=\"sf-feature-icon\">{$icon}</div><h3>{$title}</h3><p>{$desc}</p></div>";
    }
    echo '</div></div>';
}

function sf_section_gallery($d) {
    $title  = esc_html($d['title'] ?? 'Gallery');
    $images = $d['images'] ?? [];
    echo "<div class=\"sf-container\"><h2 class=\"sf-section-title\">{$title}</h2><div class=\"sf-gallery-grid\">";
    foreach ($images as $img) {
        echo "<div class=\"sf-gallery-item\"><img src=\"" . esc_url($img) . "\" alt=\"\"></div>";
    }
    echo '</div></div>';
}

function sf_section_testimonials($d) {
    $title = esc_html($d['title'] ?? 'What People Say');
    $items = $d['items'] ?? [];
    echo "<div class=\"sf-container\"><h2 class=\"sf-section-title\">{$title}</h2><div class=\"sf-testimonials-grid\">";
    foreach ($items as $t) {
        $name   = esc_html($t['name'] ?? '');
        $text   = esc_html($t['text'] ?? '');
        $rating = intval($t['rating'] ?? 5);
        $avatar = esc_url($t['avatar'] ?? '');
        $stars  = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
        echo "<div class=\"sf-testimonial\"><div class=\"sf-stars\">{$stars}</div><p>\"{$text}\"</p>";
        echo "<div class=\"sf-testimonial-author\">";
        if ($avatar) echo "<img src=\"{$avatar}\" alt=\"{$name}\">";
        echo "<strong>{$name}</strong></div></div>";
    }
    echo '</div></div>';
}

function sf_section_cta($d) {
    $title    = esc_html($d['title'] ?? 'Ready to get started?');
    $subtitle = esc_html($d['subtitle'] ?? '');
    $btn_text = esc_html($d['btn_text'] ?? 'Contact Us');
    $btn_url  = esc_url($d['btn_url'] ?? '#');
    echo "<div class=\"sf-container sf-cta-inner\"><h2>{$title}</h2>";
    if ($subtitle) echo "<p>{$subtitle}</p>";
    echo "<a href=\"{$btn_url}\" class=\"sf-btn-hero\">{$btn_text}</a></div>";
}

function sf_section_contact($d) {
    $title = esc_html($d['title'] ?? 'Contact Us');
    $email = esc_html($d['email'] ?? '');
    $phone = esc_html($d['phone'] ?? '');
    $addr  = esc_html($d['address'] ?? '');
    echo "<div class=\"sf-container\"><h2 class=\"sf-section-title\">{$title}</h2><div class=\"sf-contact-grid\">";
    echo "<div class=\"sf-contact-info\">";
    if ($email) echo "<div class=\"sf-contact-item\">📧 <a href=\"mailto:{$email}\">{$email}</a></div>";
    if ($phone) echo "<div class=\"sf-contact-item\">📞 {$phone}</div>";
    if ($addr)  echo "<div class=\"sf-contact-item\">📍 {$addr}</div>";
    echo "</div><form class=\"sf-contact-form\" method=\"post\">";
    echo "<input type=\"text\" placeholder=\"Your Name\" required><input type=\"email\" placeholder=\"Email\" required>";
    echo "<textarea placeholder=\"Message\" rows=\"4\" required></textarea>";
    echo "<button type=\"submit\" class=\"sf-btn-hero\">Send Message</button></form></div></div>";
}

function sf_section_team($d) {
    $title   = esc_html($d['title'] ?? 'Meet the Team');
    $members = $d['items'] ?? [];
    echo "<div class=\"sf-container\"><h2 class=\"sf-section-title\">{$title}</h2><div class=\"sf-team-grid\">";
    foreach ($members as $m) {
        $name   = esc_html($m['name'] ?? '');
        $role   = esc_html($m['role'] ?? '');
        $avatar = esc_url($m['avatar'] ?? '');
        echo "<div class=\"sf-team-card\">";
        if ($avatar) echo "<img src=\"{$avatar}\" alt=\"{$name}\">";
        else echo "<div class=\"sf-team-avatar-placeholder\">" . strtoupper(substr($name, 0, 1)) . "</div>";
        echo "<h3>{$name}</h3><p>{$role}</p></div>";
    }
    echo '</div></div>';
}

function sf_section_stats($d) {
    $title = esc_html($d['title'] ?? '');
    $items = $d['items'] ?? [];
    echo "<div class=\"sf-container\">";
    if ($title) echo "<h2 class=\"sf-section-title\">{$title}</h2>";
    echo "<div class=\"sf-stats-grid\">";
    foreach ($items as $s) {
        $num   = esc_html($s['number'] ?? '');
        $label = esc_html($s['label'] ?? '');
        echo "<div class=\"sf-stat\"><div class=\"sf-stat-number\">{$num}</div><div class=\"sf-stat-label\">{$label}</div></div>";
    }
    echo '</div></div>';
}

function sf_section_pricing($d) {
    $title = esc_html($d['title'] ?? 'Pricing');
    $plans = $d['items'] ?? [];
    echo "<div class=\"sf-container\"><h2 class=\"sf-section-title\">{$title}</h2><div class=\"sf-pricing-grid\">";
    foreach ($plans as $p) {
        $name     = esc_html($p['name'] ?? '');
        $price    = esc_html($p['price'] ?? '');
        $period   = esc_html($p['period'] ?? '/mo');
        $features = $p['features'] ?? [];
        $featured = !empty($p['featured']) ? ' sf-plan-featured' : '';
        echo "<div class=\"sf-plan{$featured}\"><h3>{$name}</h3><div class=\"sf-plan-price\">{$price}<span>{$period}</span></div><ul>";
        foreach ($features as $f) echo "<li>✓ " . esc_html($f) . "</li>";
        echo "</ul><a href=\"#\" class=\"sf-btn-hero\">Get Started</a></div>";
    }
    echo '</div></div>';
}
