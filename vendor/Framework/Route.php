<?php
    namespace Framework;

    class Route{
        static public $routes = [
            'GET'   =>  [],
            'POST'  =>  []
        ];

        static public $middlewares = [];
        
        public static $queue = 0;

        public static function Get($route, $function) {
            Route::$routes['GET'][$route] = $function;
        }

        public static function Post($route, $function) {
            Route::$routes['POST'][$route] = $function;
        }

        public static function RegisterMiddleware($middleware) {
            array_push(Route::$middlewares, $middleware);
        }
    };

    

?>