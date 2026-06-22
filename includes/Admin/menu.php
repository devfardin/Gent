<?php
if (!defined('ABSPATH')) exit;

function gent_register_admin_menu() {
    $cap  = 'manage_options';
    $slug = 'gent';

    add_menu_page(
        __('Gent', 'gent'),
        __('Gent', 'gent'),
        $cap,
        $slug,
         '__return_null',
        'dashicons-store',
        56
    );

    // Rename the auto-generated first submenu (same slug = no duplicate)
    add_submenu_page($slug, __('Dashboard', 'gent'), __('Dashboard', 'gent'), $cap, $slug, fn() => gent_load_page('dashboard'));

    $submenus = [
        ['General Settings',  'gent-general-settings',  'general-settings'],
        ['WhatsApp Settings', 'gent-whatsapp-settings', 'whatsapp-settings'],
        ['Order Management',  'gent-order-management',  'order-settings'],
        ['Customer Control',  'gent-customer-control',  'customer-control'],
        ['Popup Manager',     'gent-popup-manager',     'popup-manager'],
        ['Notifications',     'gent-notifications',     'notifications'],
        ['Logs',              'gent-logs',              'logs'],
        // ['Tools',             'gent-tools',             'tools'],
    ];

    foreach ($submenus as [$title, $menu_slug, $page]) {
        add_submenu_page($slug, __($title, 'gent'), __($title, 'gent'), $cap, $menu_slug, fn() => gent_load_page($page));
    }
}

function gent_load_page(string $page): void {
    $file = __DIR__ . "/pages/{$page}.php";
    file_exists($file) ? require $file : wp_die("Page not found: {$page}");
}
