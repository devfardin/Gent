<?php

namespace Fardin\Gent;

use Elementor\Core\Page_Assets\Loader;
use Fardin\Gent\Admin\AdminBase;

if (!defined('ABSPATH')) {
    exit;
}

class Gent
{
    use \Fardin\Gent\App\Traits\Singletion;



    public function init()
    {
        $this->define_constants();
        add_action('plugins_loaded', [$this, 'init_plugin']);
    }

    public function define_constants()
    {
        define('ELE_ADDMIN_VERSION', '1.0.0');
        define('ELE_ADDONS_PATH', plugin_dir_path(__DIR__));
        define('ELE_ADDONS_URL', plugin_dir_url(__DIR__));
        define('ELE_ADDONS_TEXT_DOMAIN', load_plugin_textdomain('Gent', false, dirname(plugin_basename(__FILE__)) . '/languages'));
    }

    public function init_plugin()
    {
        $this->includes();
        $this->init_hooks();
    }
    public function includes()
    {
        //    App\Widgets\Base::instance()->init();
        Widgets\Base::instance()->init();
        Templates\TemplatesBase::instance()->init();
        Shortcodes\ShortcodesBase::instance()->init();
        Admin\AdminBase::instance()->init();
    }

    public function init_hooks()
    {
        load_plugin_textdomain('Gent', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }


}