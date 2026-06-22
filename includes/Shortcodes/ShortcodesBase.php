<?php
namespace Fardin\Gent\Shortcodes;
if (!defined('ABSPATH')) {
    exit;
}
class ShortcodesBase {
    use \Fardin\Gent\App\Traits\Singletion;
    
    public function init(){
        add_shortcode('create_shop', [$this, 'our_customShortCode']);
    }
    public function load_dep(){
        
    }
    public function our_customShortCode(){
        echo '<h1 style="text-align:center; color:red;">Shop Page Here</h1>';
    }
}