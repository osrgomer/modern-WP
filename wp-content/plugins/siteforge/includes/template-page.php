<?php
defined('ABSPATH') || exit;

$post     = get_post();
$sections = json_decode(get_post_meta($post->ID, '_sf_sections', true) ?: '[]', true);
$ps       = json_decode(get_post_meta($post->ID, '_sf_page_settings', true) ?: '{}', true);
$font     = $ps['font'] ?? get_option('sf_font', 'Inter');
$theme    = $ps['theme'] ?? 'light';
$primary  = get_option('sf_primary_color', '#6366f1');
?><!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="<?php echo esc_attr($theme); ?>">
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo esc_html($post->post_title); ?> — <?php bloginfo('name'); ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=<?php echo urlencode($font); ?>:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>:root{--sf-primary:<?php echo esc_attr($primary); ?>;--sf-font:'<?php echo esc_attr($font); ?>',sans-serif;}</style>
<?php wp_head(); ?>
</head>
<body style="font-family:var(--sf-font)">

<?php foreach ($sections as $section):
    $type   = $section['type'] ?? '';
    $fields = $section['fields'] ?? [];
    sf_render_section($type, $fields);
endforeach; ?>

<?php wp_footer(); ?>
</body>
</html>
<?php

function sf_render_section($type, $f) {
    $wrap_style = '';
    if (!empty($f['bg_color'])) $wrap_style = "style=\"background:{$f['bg_color']};color:{$f['text_color']}\"";
    echo "<section class=\"sf-section sf-sec-{$type}\" {$wrap_style}>";
    switch ($type) {
        case 'hero':         sf_tpl_hero($f);         break;
        case 'products':     sf_tpl_products($f);     break;
        case 'food_menu':    sf_tpl_food_menu($f);    break;
        case 'features':     sf_tpl_features($f);     break;
        case 'gallery':      sf_tpl_gallery($f);      break;
        case 'testimonials': sf_tpl_testimonials($f); break;
        case 'cta':          sf_tpl_cta($f);          break;
        case 'contact':      sf_tpl_contact($f);      break;
        case 'pricing':      sf_tpl_pricing($f);      break;
        case 'text_block':   sf_tpl_text_block($f);   break;
        case 'map_embed':    sf_tpl_map($f);          break;
        case 'social_links': sf_tpl_social($f);       break;
    }
    echo '</section>';
}

function sf_tpl_hero($f) {
    $heading    = esc_html($f['heading'] ?? 'Welcome');
    $subheading = esc_html($f['subheading'] ?? '');
    $btn_text   = esc_html($f['btn_text'] ?? 'Get Started');
    $btn_url    = esc_url($f['btn_url'] ?? '#');
    echo "<div class=\"sf-hero-inner\"><h1>{$heading}</h1><p>{$subheading}</p>";
    if ($btn_text) echo "<a href=\"{$btn_url}\" class=\"sf-btn-hero\">{$btn_text}</a>";
    echo '</div>';
}

function sf_tpl_products($f) {
    $title = esc_html($f['title'] ?? 'Products');
    $items = is_string($f['items'] ?? '') ? json_decode($f['items'], true) : ($f['items'] ?? []);
    echo "<div class=\"sf-container\"><h2 class=\"sf-section-title\">{$title}</h2><div class=\"sf-products-grid\">";
    foreach ((array)$items as $p) {
        $name  = esc_html($p['name'] ?? '');
        $price = esc_html($p['price'] ?? '');
        $img   = esc_url($p['img'] ?? '');
        $badge = esc_html($p['badge'] ?? '');
        echo "<div class=\"sf-product-card\">";
        if ($badge) echo "<span class=\"sf-badge\">{$badge}</span>";
        if ($img)   echo "<img src=\"{$img}\" alt=\"{$name}\">";
        echo "<div class=\"sf-product-info\"><h3>{$name}</h3><span class=\"sf-price\">{$price}</span><button class=\"sf-add-cart\">Add to Cart</button></div></div>";
    }
    echo '</div></div>';
}

function sf_tpl_food_menu($f) {
    $title  = esc_html($f['title'] ?? 'Menu');
    $accent = esc_attr($f['accent'] ?? '#e74c3c');
    $items  = is_string($f['items'] ?? '') ? json_decode($f['items'], true) : ($f['items'] ?? []);
    echo "<div class=\"sf-container\"><h2 class=\"sf-section-title\">{$title}</h2><div class=\"sf-menu-list\">";
    foreach ((array)$items as $item) {
        $name  = esc_html($item['name'] ?? '');
        $desc  = esc_html($item['desc'] ?? '');
        $price = esc_html($item['price'] ?? '');
        $img   = esc_url($item['img'] ?? '');
        echo "<div class=\"sf-menu-item\">";
        if ($img) echo "<img src=\"{$img}\" alt=\"{$name}\">";
        echo "<div class=\"sf-menu-info\"><h3>{$name}</h3><p>{$desc}</p></div><span class=\"sf-menu-price\" style=\"color:{$accent}\">{$price}</span></div>";
    }
    echo '</div></div>';
}

function sf_tpl_features($f) {
    $title = esc_html($f['title'] ?? 'Features');
    $items = is_string($f['items'] ?? '') ? json_decode($f['items'], true) : ($f['items'] ?? []);
    echo "<div class=\"sf-container\"><h2 class=\"sf-section-title\">{$title}</h2><div class=\"sf-features-grid\">";
    foreach ((array)$items as $item) {
        $icon  = esc_html($item['icon'] ?? '✨');
        $t     = esc_html($item['title'] ?? '');
        $desc  = esc_html($item['desc'] ?? '');
        echo "<div class=\"sf-feature-card\"><div class=\"sf-feature-icon\">{$icon}</div><h3>{$t}</h3><p>{$desc}</p></div>";
    }
    echo '</div></div>';
}

function sf_tpl_gallery($f) {
    $title  = esc_html($f['title'] ?? 'Gallery');
    $images = is_string($f['images'] ?? '') ? json_decode($f['images'], true) : ($f['images'] ?? []);
    echo "<div class=\"sf-container\"><h2 class=\"sf-section-title\">{$title}</h2><div class=\"sf-gallery-grid\">";
    foreach ((array)$images as $img) {
        echo "<div class=\"sf-gallery-item\"><img src=\"" . esc_url($img) . "\" alt=\"\"></div>";
    }
    echo '</div></div>';
}

function sf_tpl_testimonials($f) {
    $title = esc_html($f['title'] ?? 'Testimonials');
    $items = is_string($f['items'] ?? '') ? json_decode($f['items'], true) : ($f['items'] ?? []);
    $bg    = esc_attr($f['bg'] ?? '');
    echo "<div class=\"sf-container\" " . ($bg ? "style=\"background:{$bg}\"" : '') . "><h2 class=\"sf-section-title\">{$title}</h2><div class=\"sf-testimonials-grid\">";
    foreach ((array)$items as $t) {
        $name  = esc_html($t['name'] ?? '');
        $role  = esc_html($t['role'] ?? '');
        $text  = esc_html($t['text'] ?? '');
        $stars = intval($t['stars'] ?? 5);
        $star_html = str_repeat('★', $stars) . str_repeat('☆', 5 - $stars);
        echo "<div class=\"sf-testimonial-card\"><div class=\"sf-stars\">{$star_html}</div><p>\"{$text}\"</p><div class=\"sf-reviewer\"><strong>{$name}</strong><span>{$role}</span></div></div>";
    }
    echo '</div></div>';
}

function sf_tpl_cta($f) {
    $heading = esc_html($f['heading'] ?? 'Get Started');
    $subtext = esc_html($f['subtext'] ?? '');
    $btn     = esc_html($f['btn_text'] ?? 'Contact Us');
    $url     = esc_url($f['btn_url'] ?? '#');
    echo "<div class=\"sf-container\" style=\"text-align:center\"><h2>{$heading}</h2>";
    if ($subtext) echo "<p>{$subtext}</p>";
    echo "<a href=\"{$url}\" class=\"sf-btn-hero\">{$btn}</a></div>";
}

function sf_tpl_contact($f) {
    $title   = esc_html($f['title'] ?? 'Contact');
    $phone   = esc_html($f['phone'] ?? '');
    $address = esc_html($f['address'] ?? '');
    echo "<div class=\"sf-container\"><div class=\"sf-contact-inner\"><div class=\"sf-contact-info\"><h2>{$title}</h2>";
    if ($phone)   echo "<p>📞 {$phone}</p>";
    if ($address) echo "<p>📍 {$address}</p>";
    echo "</div><form class=\"sf-contact-form\" method=\"post\">
        <input type=\"text\" placeholder=\"Your Name\" required>
        <input type=\"email\" placeholder=\"Email\" required>
        <textarea placeholder=\"Message\" rows=\"4\" required></textarea>
        <button type=\"submit\" class=\"sf-btn-hero\">Send Message</button>
    </form></div></div>";
}

function sf_tpl_pricing($f) {
    $title = esc_html($f['title'] ?? 'Pricing');
    $plans = is_string($f['plans'] ?? '') ? json_decode($f['plans'], true) : ($f['plans'] ?? []);
    echo "<div class=\"sf-container\"><h2 class=\"sf-section-title\">{$title}</h2><div class=\"sf-pricing-grid\">";
    foreach ((array)$plans as $p) {
        $name     = esc_html($p['name'] ?? '');
        $price    = esc_html($p['price'] ?? '');
        $period   = esc_html($p['period'] ?? '/mo');
        $features = $p['features'] ?? [];
        $hl       = !empty($p['highlight']) ? ' sf-pricing-highlight' : '';
        echo "<div class=\"sf-pricing-card{$hl}\"><h3>{$name}</h3><div class=\"sf-pricing-price\">{$price}<span>{$period}</span></div><ul>";
        foreach ((array)$features as $feat) echo "<li>✓ " . esc_html($feat) . "</li>";
        echo "</ul><button class=\"sf-pricing-btn\">Get Started</button></div>";
    }
    echo '</div></div>';
}

function sf_tpl_text_block($f) {
    $heading = esc_html($f['heading'] ?? '');
    $content = esc_html($f['content'] ?? '');
    $align   = esc_attr($f['align'] ?? 'center');
    $max_w   = esc_attr($f['max_width'] ?? '800px');
    echo "<div class=\"sf-container\" style=\"text-align:{$align}\"><div style=\"max-width:{$max_w};margin:0 auto\">";
    if ($heading) echo "<h2>{$heading}</h2>";
    echo "<p>{$content}</p></div></div>";
}

function sf_tpl_map($f) {
    $title = esc_html($f['title'] ?? 'Find Us');
    $url   = esc_url($f['embed_url'] ?? '');
    $h     = intval($f['height'] ?? 400);
    echo "<div class=\"sf-container\"><h2 class=\"sf-section-title\">{$title}</h2>";
    if ($url) echo "<iframe src=\"{$url}\" width=\"100%\" height=\"{$h}\" style=\"border:0;border-radius:12px\" allowfullscreen loading=\"lazy\"></iframe>";
    echo '</div>';
}

function sf_tpl_social($f) {
    $title = esc_html($f['title'] ?? 'Follow Us');
    $links = is_string($f['links'] ?? '') ? json_decode($f['links'], true) : ($f['links'] ?? []);
    echo "<div class=\"sf-container\" style=\"text-align:center\"><h2>{$title}</h2><div class=\"sf-social-links\">";
    foreach ((array)$links as $l) {
        $icon     = esc_html($l['icon'] ?? '');
        $platform = esc_html($l['platform'] ?? '');
        $url      = esc_url($l['url'] ?? '#');
        echo "<a href=\"{$url}\" class=\"sf-social-btn\">{$icon} {$platform}</a>";
    }
    echo '</div></div>';
}
