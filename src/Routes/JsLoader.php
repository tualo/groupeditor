<?php
namespace Tualo\Office\GroupEditor\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\Basic\RouteSecurityHelper;

class JsLoader implements IRoute{
    public static function register(){
        BasicRoute::add('/jsgroupeditor/(?P<file>[\w.\/\-]+).js',function($matches){
            RouteSecurityHelper::serveSecureStaticFile(
                $matches['file'] . '.js',
                dirname(__DIR__, 1) . '/js/lazy/',
                ['js'],
                ['application/javascript']
            );/*            
            App::contenttype('application/javascript');
            readfile( dirname(__DIR__,1).'/js/lazy/'.$matches['file'].'.js' );
            exit();
            */
        },['get'],false);

    }
}