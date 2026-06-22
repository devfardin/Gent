<?php
namespace Fardin\Gent\Widgets;

if (!defined("ABSPATH")) {
    exit;
}

class Base
{
    use \Fardin\Gent\App\Traits\Singletion;

    public function init()
    {
        add_action('elementor/widgets/register', [$this, "register_new_widgets"]);
        
        add_action("wp_enqueue_scripts", [$this, 'enqueue_scripts']);

        add_action('elementor/frontend/after_register_styles', [$this, "register_scripts"]);

    }
    public function register_new_widgets($widgets_manager) {
        $widgets_manager->register(BasicWidget::instance());
    }
    public function register_scripts()
    {
        wp_register_style('ele_addon_style', ELE_ADDONS_URL . 'assets/css/main.css', array(), ELE_ADDMIN_VERSION, 'all');
    }
    public function enqueue_scripts()
    {
        wp_enqueue_style('ele_addon_style');
    }
} 