<?php
namespace Fardin\Gent\Templates;
if (!defined('ABSPATH')) {
    exit;
}

class Footer
{
    use \Fardin\Gent\App\Traits\Singletion;
    public function init()
    {
        add_action('init', [$this, 'override_footer']);
    }

    public function override_footer()
    {
        remove_action('kadence_footer', 'Kadence\footer_markup', 10);
        add_action('kadence_footer', [$this, 'custom_footer'], 10);
    }

    public function custom_footer(){
        ?>
        <h2>Footer Area</h2>

    <?php
    }
}