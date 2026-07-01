<?php
namespace Fardin\Gent\Frontend;
if (!defined('ABSPATH')) {
    exit;
}
class FrontendBase
{
    use \Fardin\Gent\App\Traits\Singletion;


    public function init()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue()
    {
        wp_enqueue_style('frontend-css', GENT_URL . 'assets/css/main.css');
        wp_enqueue_script('frontend-js', GENT_URL . 'assets/js/frontend.js', ['jquery']);
    }
}
