<?php
namespace Fardin\Gent\Features;
if(!defined('ABSPATH') ){
    exit;
}
class FeaturesBase{
    use \Fardin\Gent\App\Traits\Singletion;
    public function init(){
        WhatsApp::instance();

    }
}