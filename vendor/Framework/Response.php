<?php
    namespace Framework;
    use \Framework\Route;

    class Response{
        protected $controller;
        protected $method;
        protected $request;

        public function next() {
            $object = new $this->controller();
            $handler = $this->method;
            return $object->$handler();
        }

        public function __construct($request) {
            $this->request = $request;
            $route = explode('?', $_SERVER['REQUEST_URI'])[0];
            if(!array_key_exists($route, Route::$routes[$_SERVER['REQUEST_METHOD']])) {
                echo "Error ! Route doesn't exist, 404";
                die();
            }
    
            $c = explode('@', '\\Controllers\\'.Route::$routes[$_SERVER['REQUEST_METHOD']][$route]);
            $this->controller = $c[0];
            $this->method = $c[1];
        }

        public function Start() {
            $c = new $this->controller();
            foreach($c->middlewares as $middleware) {
                Route::RegisterMiddleware($middleware);
            }

            return $this->ExecuteMiddlewares();
        }

        public function ExecuteMiddlewares() {
            if(empty(Route::$middlewares)) {
                return $this->ExecuteController();
            } else {
                $middleware = '\\Middlewares\\'.Route::$middlewares[0];
                $mw = new $middleware();
                $response = $mw->handle($this->request);
                if(Route::$queue == sizeof(Route::$middlewares)) {
                    return $this->ExecuteController();
                } else {
                    return $response;
                }
            }
        }

        public function ExecuteController() {
            $c = new $this->controller();

            $methodName = $this->method;
            return $c->handle($methodName, $this->request);
        }

        public static function json($array, $code=200, $headers = []) {
            $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
            header($protocol.' '.$code.' '.'MiniFramework Response'); // last argument is the message !
            header('Content-Type: application/json');

            foreach($headers as $header) {
                header($header);
            }

            return json_encode($array, true);
        }
    };

?>