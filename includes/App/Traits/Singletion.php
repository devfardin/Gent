<?php 

namespace Fardin\Gent\App\Traits;
if(!defined("ABSPATH")){
    exit;
}
trait Singletion {
    private static $instance;

    public static function instance(){
        if(!self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }
}