<?php
    namespace Middlewares;
    use Framework\RouteMiddleware;

    class CodeBoilerPlatePlaceHolder extends RouteMiddleware{
        // handle() always called when middleware is executed !
        public function handle() {
            /* 
                your code here 
                return $this->next(); means the middleware will pass successfully to the next
                any other return will not pass the data to the next middleware, and will output the content of the return instead
            */

            return $this->next();
        }
    };
?>