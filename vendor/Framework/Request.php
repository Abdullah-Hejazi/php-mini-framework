<?php
    namespace Framework;

    class Request{
        public $route;
        public $data;

        function __construct() {
            $this->route = $_SERVER['REQUEST_URI'];
            $this->url = $_SERVER['HTTP_HOST'];
            $this->data = array_merge($_GET, $_POST);
        }

        public function validate($array = []) {
            $result = true;

            foreach($array as $name => $validations) {
                $rules = explode('|', $validations);
                foreach($rules as $rule) {
                    preg_match_all('/[^[,|)|(]*]*\b/', $rule, $matches);
                    if(sizeof($matches[0]) >= 2) {
                        $method = $matches[0][0];
                        $result = $result && $this->$method($name, $matches[0]);
                    } else {
                        $result = $result && $this->$rule($name);
                    }
                }
            }

            return $result;
        }

        public function redirect($page) {
            header("Location: " . $page);
            die();
        }

        public function required($name) {
            return (isset($this->data[$name]) && !empty($this->data[$name]));
        }

        public function number($name) {
            return (isset($this->data[$name]) && is_numeric($this->data[$name]));
        }

        public function email($name) {
            if(!isset($this->data[$name])) {
                return false;
            }
            
            if(filter_var($this->data[$name], FILTER_VALIDATE_EMAIL)) {
                return true;
            }

            return false;
        }

        public function min($name, $values) {
            return (isset($this->data[$name]) && strlen($this->data[$name]) >= $values[2]);
        }

        public function max($name, $values) {
            return (isset($this->data[$name]) && strlen($this->data[$name]) <= $values[2]);
        }
    };

?>