<?php
    namespace Controllers;
    use Framework\Controller;
    use Framework\View;

    class TestController extends Controller {
        // define all middlewares that will only work on this route
        // leave empty if this route has no middlewares
        public $middlewares = [];

        /* define your functions here that will be executed when a route hits the function */
        public function index() {
        	// sending a variable names data with value = World
        	return View::Render('index.php', [
        		'data'	=>	'World'
        	]);
        }
    };
?>