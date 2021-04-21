<?php
    namespace Framework;

    class Console{
        protected $boilerPlates = [
            ['Middleware', 'Middlewares'],
            ['Controller', 'Controllers'],
            ['Route-middleware', 'Middlewares'],
            ['Model', 'Models']
        ];

        protected $commands = [
            ['create', 2],
            ['help', 0],
            ['migrate', 0]
        ];

        public $output = "";

        function __construct($args, $count) {
            if($count < 2) {
                $result = "No command provided ! \n";
                $result .= "List of commands : \n";
                foreach($this->commands as $c) {
                    $result .= " > ".$c[0]."\n";
                }

                $this->output = $result;
            } else {
                $command = $this->getObject($args[1], $this->commands);
                if(is_array($command) && !empty($command)) {
                    if($count == $command[1]+2){
                        $method = $command[0];
                        $this->$method($args);
                    } else {
                        $this->output = "Invalid number of arguments !";
                    }
                } else {
                    $result = "Command doesn't exist ! \n";
                    $result .= "List of commands : \n";
                    foreach($this->commands as $c) {
                        $result .= " > ".$c[0]."\n";
                    }

                    $this->output = $result;
                }
            }
        }

        function create($values) {
            $object = $values[2];
            $value = $values[3];
            $boiler = $this->getObject($object, $this->boilerPlates);
            if(is_array($boiler) && !empty($boiler)) {
                $dest = INITIAL_DIR . '/'.$boiler[1].'/'.$value.'.php';
                $data = file_get_contents('vendor/Framework/BoilerPlates/'.$boiler[0].'.php');

                $data = str_replace('CodeBoilerPlatePlaceHolder', $value, $data);

                file_put_contents($dest, $data);

                $this->output = $boiler[0]." Has been created Successfully ";
                return;
            } else {
                $result = "No such ".$object. "Available \n";
                $result .= "Available objects : \n";
                foreach($this->boilerPlates as $b) {
                    $result .= "\t=> ".$b[0]."\n";
                }

                $this->output = $result;
                return;
            }
        }

        function getObject($object, $arr) {
            foreach($arr as $a) {
                if(sizeof($a) == 2 && strtolower($a[0]) == strtolower($object)) return $a;
            }

            return NULL;
        }

        function migrate($values) {
            $dir = new \DirectoryIterator('./Models/');
            $answer = readline("This will delete all the data !!!\nAre you sure you want to migrate ?(Yes / No): \n");
            if(strtolower($answer) == 'yes') {
                foreach ($dir as $fileinfo) {
                    if (!$fileinfo->isDot()) {
                        $modelName = '\\Models\\'.str_replace('.php', '', $fileinfo->getFilename());
                        $model = new $modelName();
                        $this->output = $model->Migrate();
                    }
                }
            }
            
        }
    };
?>