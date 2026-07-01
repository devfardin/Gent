<?php
namespace Fardin\Gent\Admin;

if (!defined('ABSPATH'))
    exit;

class AdminBase
{
    use \Fardin\Gent\App\Traits\Singletion;

    public function init()
    {
        require_once __DIR__ . '/menu.php';
        add_action('admin_menu', 'gent_register_admin_menu');
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets(string $hook): void
    {
        if (strpos($hook, 'gent') === false)
            return;
        wp_enqueue_style('gent-admin', GENT_URL . 'assets/css/admin.css', [], '1.0.0');
        wp_enqueue_media();
        wp_enqueue_script('gent-admin', GENT_URL . 'assets/js/admin.js', ['jquery', 'media-upload'], '1.0.0', true);
    }
}
