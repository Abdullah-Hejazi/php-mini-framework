<?php
    namespace Framework;

    class Middleware{
        public function next() {
            Route::$queue++;

            if(isset(Route::$middlewares[Route::$queue])) {
                $middleware = '\\Middlewares\\'.Route::$middlewares[Route::$queue];
                $mw = new $middleware();
                return $mw->handle();
            } else {
                return NULL;
            }
        }
    };
?>