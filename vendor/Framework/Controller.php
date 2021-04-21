<?php
    namespace Framework;

    class Controller{
        public $middlewares = [];
        public $queue = 0;
        
        public function handle($method, $request) {
            return $this->$method($request);
        }
    };
?>