<?php
namespace Tualo\Office\GroupEditor\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;

class JsLoader implements IRoute{
    public static function register(){
        BasicRoute::add('/jsgroupeditor/(?P<file>[\w.\/\-]+).js',function($matches){
            App::contenttype('application/javascript');
            readfile( dirname(__DIR__,1).'/js/lazy/'.$matches['file'].'.js' );
            exit();
            
        },['get'],false);

    }
}