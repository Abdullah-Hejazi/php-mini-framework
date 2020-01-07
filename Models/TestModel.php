<?php
    namespace Models;

    use Framework\Model;

    class TestModel extends Model {
        /* defines the name of the table for this Model */
        protected $table = 'tests';

        /* 
            all the data that the Model has, if you apply tables from the console, 
            it will retrieve the data from this array as well 
        */
        protected $data = [
            'id'                =>      'INT'
        ];
    };
?>