<?php
    namespace Framework;

    class RouteMiddleware{
        public $controller;
        public function next() {
            $this->controller->queue++;
            if(isset($this->controller->middlewares[$this->controller->queue])) {
                $middleware = '\\Middlewares\\'.$this->controller->middlewares[$this->controller->queue];
                $mw = new $middleware();
                $mw->controller = $this->controller;
                return $mw->handle();
            } else {
                return NULL;
            }
        }
    };
?>