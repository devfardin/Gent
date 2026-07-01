<?php
namespace Fardin\Gent\Templates;
if (!defined('ABSPATH')) {
    exit;
}
class TemplatesBase
{
    use \Fardin\Gent\App\Traits\Singletion;
    public function init()
    {
        add_filter('single_template', [$this, 'load_plugin_single_template']);
        $this->load_dep();
    }

    public function load_dep(){
    }

    public function load_plugin_single_template(string $single_template): string
    {
        global $post;
        $file = plugin_dir_path(__FILE__) . 'single-' . $post->post_type . '.php';
        return file_exists($file) ? $file : $single_template;
    }

}