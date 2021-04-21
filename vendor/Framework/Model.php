<?php
    namespace Framework;

    abstract class Model{
        protected $table;
        public $dates = true;
        protected $primary = 'id';
        protected $data = [];

        function __construct($values = []) {
            foreach($values as $key => $value){
                $this->{$key} = $value;
            }
        }

        public function save() {
            $db = \Framework\DB::getInstance();
            if(empty($this->data)) {
                return -1; // no data to save !
            }

            $statement = "REPLACE INTO ".$this->table." (";
            foreach($this->data as $column => $type) {
                if($column != $this->primary)
                    $statement .= $column.", ";
            }

            $statement = substr($statement, 0, -2);
            $statement .= ") VALUES(";

            foreach($this->data as $d => $c) {
                if($d != $this->primary)
                    $statement .= "?, ";
            }

            $statement = substr($statement, 0, -2);
            $statement .= ")";

            $stmt = $db->prepare($statement);
            $data = [];
            foreach($this->data as $column => $type) {
                if(isset($this->$column)){
                    if($column != $this->primary)
                        array_push($data, $this->$column);
                } else {
                    if($column != $this->primary){
                        if(DB::getType($type) == 's') {
                            array_push($data, '');
                        } else {
                            array_push($data, 0);
                        }
                    }
                }
                    
            }


            $stmt->execute($data);
        }

        public static function RawSelect($query) {
            $class = get_called_class();
            $model = new $class();

            $db = \Framework\DB::getInstance();
            $statement = "SELECT * FROM ".$model->table." ".$query;

            $stmt = $db->prepare($statement);
            $stmt->execute();

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            if(!$result) {
                return false;
            }
            return $result;
        }

        public function delete() {
            if(isset($this->{$this->primary})) {
                $db = \Framework\DB::getInstance();
                $statement = "DELETE FROM ".$this->table." WHERE ".$this->primary." = ? LIMIT 1";
                $stmt = $db->prepare($statement);
                $data = [$this->{$this->primary}];
                $stmt->execute($data);
            }
        }

        public static function Find($key) {
            // get_called_class();
            $class = get_called_class();

            $model = new $class();

            $db = \Framework\DB::getInstance();
            $statement = "SELECT * FROM ".$model->table." WHERE ".$model->primary." = ? LIMIT 1";
            $stmt = $db->prepare($statement);
            $data = [$key];
            $stmt->execute($data);
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            if(!$result) {
                return false;
            }
            foreach($result[0] as $key => $value){
                $model->{$key} = $value;
            }

            return $model;
        }

        public static function Select($conditions) {
            $class = get_called_class();
            $model = new $class();

            if(empty($conditions)) {
                return $model();
            }

            $db = \Framework\DB::getInstance();
            $statement = "SELECT * FROM ".$model->table." WHERE ";

            foreach($conditions as $column) {
                if(sizeof($column) == 2) {
                    $statement .= $column[0]." = ? AND ";
                } else if(sizeof($column) == 3) {
                    $statement .= $column[0]." ".$column[1]." ? AND ";
                } else {
                    return false;
                }
            }

            $statement = substr($statement, 0, -5);
            $statement .= " LIMIT 1";


            $stmt = $db->prepare($statement);
            $data = [];
            foreach($conditions as $column) {
                if(sizeof($column) == 2) {
                    array_push($data, $column[1]);
                } else if(sizeof($column) == 3) {
                    array_push($data, $column[2]);
                } else {
                    return false;
                }
            }
            $stmt->execute($data);

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            if(!$result) {
                return false;
            }
            foreach($result[0] as $key => $value){
                $model->{$key} = $value;
            }

            return $model;
        }

        public static function All($conditions = []) {
            $class = get_called_class();
            $model = new $class();

            $db = \Framework\DB::getInstance();
            $statement = "SELECT * FROM ".$model->table;
            if(sizeof($conditions) > 0) {
                $statement .= " WHERE ";
            }

            foreach($conditions as $column) {
                if(sizeof($column) == 2) {
                    $statement .= $column[0]." = ? AND ";
                } else if(sizeof($column) == 3) {
                    $statement .= $column[0]." ".$column[1]." ? AND ";
                } else {
                    return false;
                }
            }

            if(sizeof($conditions) > 0) {
                $statement = substr($statement, 0, -5);
            }

            $stmt = $db->prepare($statement);
            $data = [];
            foreach($conditions as $column) {
                if(sizeof($column) == 2) {
                    array_push($data, $column[1]);
                } else if(sizeof($column) == 3) {
                    array_push($data, $column[2]);
                } else {
                    return false;
                }
            }
            $stmt->execute($data);

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            return $result;
        }

        public function Migrate() {
            if(empty($this->data)) return '[Failed] $data array can\'t be empty';
            
            $statement = "DROP TABLE IF EXISTS `".$this->table."`; CREATE TABLE `".$this->table."` ( ";

            foreach($this->data as $column => $type) {
                $statement .= $column." ".$type;
                if($column == $this->primary) {
                    $statement .= " PRIMARY KEY, ";
                } else {
                    $statement .= " , ";
                }
            }

            $statement .= "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);";

            $db = \Framework\DB::getInstance();
            $stmt = $db->prepare($statement);
            if($stmt->execute()) {
                return "[Success] Table created !";
            } else {
                return "[Failed] Failed to execute the statement !";
            }
        }
    };
?>