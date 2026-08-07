<?php
defined('ABSPATH') || exit;

add_action('admin_menu', 'sf_admin_menu');
function sf_admin_menu() {
    add_menu_page('SiteForge', 'SiteForge', 'edit_posts', 'siteforge', 'sf_dashboard_page', 'dashicons-layout', 3);
    add_submenu_page('siteforge', 'Pages', 'Pages', 'edit_posts', 'siteforge', 'sf_dashboard_page');
    add_submenu_page('siteforge', 'Editor', 'Editor', 'edit_posts', 'siteforge-editor', 'sf_editor_page');
    add_submenu_page('siteforge', 'Settings', 'Settings', 'manage_options', 'siteforge-settings', 'sf_settings_page');
}

add_action('admin_enqueue_scripts', 'sf_admin_assets');
function sf_admin_assets($hook) {
    if (strpos($hook, 'siteforge') === false) return;
    wp_enqueue_style('sf-admin', SF_URL . 'assets/css/admin.css', [], SF_VERSION);
    wp_enqueue_script('sf-admin', SF_URL . 'assets/js/admin.js', ['jquery'], SF_VERSION, true);
    wp_localize_script('sf-admin', 'SF', [
        'api'   => rest_url('siteforge/v1'),
        'nonce' => wp_create_nonce('wp_rest'),
        'admin' => admin_url('admin.php'),
    ]);
}

function sf_dashboard_page() { ?>
<div class="sf-wrap" id="sf-dashboard">
    <div class="sf-topbar">
        <div class="sf-logo">⚡ SiteForge</div>
        <button class="sf-btn sf-btn-primary" id="sf-new-page">+ New Page</button>
    </div>
    <div class="sf-pages-grid" id="sf-pages-grid">
        <div class="sf-loading">Loading pages...</div>
    </div>
</div>
<?php }

function sf_editor_page() {
    $page_id = intval($_GET['page_id'] ?? 0); ?>
<div class="sf-editor-wrap" id="sf-editor" data-page="<?php echo $page_id; ?>">
    <div class="sf-editor-topbar">
        <a href="<?php echo admin_url('admin.php?page=siteforge'); ?>" class="sf-back">← Pages</a>
        <div class="sf-editor-title" id="sf-page-title" contenteditable="true">Loading...</div>
        <div class="sf-editor-actions">
            <select id="sf-page-status"><option value="draft">Draft</option><option value="publish">Published</option></select>
            <button class="sf-btn sf-btn-outline" id="sf-preview-btn">Preview</button>
            <button class="sf-btn sf-btn-primary" id="sf-save-btn">Save</button>
        </div>
    </div>
    <div class="sf-editor-body">
        <div class="sf-sidebar" id="sf-sidebar">
            <div class="sf-sidebar-tabs">
                <button class="sf-tab active" data-tab="sections">Sections</button>
                <button class="sf-tab" data-tab="settings">Settings</button>
            </div>
            <div class="sf-tab-content" id="tab-sections">
                <div class="sf-section-search"><input type="text" placeholder="Search sections..." id="sf-section-search"></div>
                <div class="sf-section-list" id="sf-section-list"></div>
            </div>
            <div class="sf-tab-content hidden" id="tab-settings">
                <div class="sf-field"><label>Page Theme</label>
                    <select id="sf-theme-select"><option value="light">Light</option><option value="dark">Dark</option></select>
                </div>
                <div class="sf-field"><label>Font Family</label>
                    <select id="sf-font-select">
                        <option value="Inter">Inter</option>
                        <option value="Poppins">Poppins</option>
                        <option value="Playfair Display">Playfair Display</option>
                        <option value="Roboto">Roboto</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="sf-canvas-wrap">
            <div class="sf-canvas" id="sf-canvas">
                <div class="sf-canvas-empty" id="sf-canvas-empty">
                    <div class="sf-empty-icon">🎨</div>
                    <p>Click a section from the left to add it here</p>
                </div>
            </div>
        </div>
        <div class="sf-props-panel" id="sf-props-panel">
            <div class="sf-props-empty">Select a section to edit its content</div>
        </div>
    </div>
</div>
<?php }

function sf_settings_page() { ?>
<div class="sf-wrap" id="sf-settings">
    <div class="sf-topbar"><div class="sf-logo">⚡ SiteForge — Settings</div></div>
    <div class="sf-settings-form">
        <div class="sf-field"><label>Site Name</label><input type="text" id="sf-s-name"></div>
        <div class="sf-field"><label>Primary Color</label><input type="color" id="sf-s-color"></div>
        <div class="sf-field"><label>Default Font</label>
            <select id="sf-s-font">
                <option value="Inter">Inter</option><option value="Poppins">Poppins</option>
                <option value="Playfair Display">Playfair Display</option><option value="Roboto">Roboto</option>
            </select>
        </div>
        <button class="sf-btn sf-btn-primary" id="sf-save-settings">Save Settings</button>
        <div id="sf-settings-msg"></div>
    </div>
</div>
<?php }
