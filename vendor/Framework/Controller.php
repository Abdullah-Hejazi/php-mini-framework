<?php
    namespace Framework;

    class Controller{
        public $middlewares = [];
        public $queue = 0;
        
        public function handle($method, $request) {
            if(empty($this->middlewares)) {
                return $this->$method($request);
            } else {
                $middleware = '\\Middlewares\\'.$this->middlewares[0];
                $mw = new $middleware();
                $mw->controller = &$this;
                $response = $mw->handle();
                if($this->queue == sizeof($this->middlewares)) {
                    return $this->$method($request);
                } else {
                    return $response;
                }
            }
        }
    };
?>