<?php
class DB {
    private static $_instance = null; //Stores the instance of the DB if available, if not instantiate a DB object from within it and stores it here.
    private $_pdo, 
            $_query, 
            $_error = false, 
            $_results, 
            $_count = 0;


    private function __construct() {
        try {
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . '; dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password')); // Debuging "'; dbname='" to 'dbname='= debug failed
                // TEST CONNECTION: echo "Connected";
        } catch(PDOException $e) { //When error ocur, kills application and outputs an error
                die($e->getMessage());
        }
    }


    public static function getInstance() { //Checks if the called instance has been set or not.
        if(!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
    /*The Singleton Pattern: "main static instance" allows us to get an 
    instance of the DB if it has alwredy been instantiated. Keeps us from needing to connect to the DB on each page. */

    /* PDO- Database wrapper*/
    //NOTE: the underscore in "$_instance" indicates that the object is private.
    //--------------------------------------------------------------------------

    public function query($sql, $params = array()) {
        $this->_error = false; //Returns error to false to avoid reporting preveouse errors.
        if($this->_query = $this->_pdo->prepare($sql)) { //Check if query was prepaired successfully, if so bind paramaters if they exixst. Else, exicute query, return results as an object and update row count in the class.
            $x = 1;
            if(count($params)) {//Check if paramaters exist.
                foreach($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++; 
                }
            }

            if($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();            
            } else {
                $this->_error = true;
            }
        }
        return $this; //Returns the current object..
    }


    //Allows a specific action "selct/delete", define table, define specific field with specific value. 
    public function action($action, $table, $where = array()) {  //To Do #2: take into account if we only want to retrive all records from users.
        if(count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=');

            $field =    $where[0];
            $operator = $where[1];
            $value =    $where[2];

            if(in_array($operator, $operators)) {  // check weather the operator is inside the defined array.
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";  
              
                if(!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
        return false;
    }



    public function get($table, $where) { //To Do #2: take into account if we only want to retrive all records from users.
        return $this->action('SELECT *', $table, $where);
    }

    public function delete($table, $where) {
        return $this->action('DELETE', $table, $where);
    }



    //-------------------------------------------------------------------    
    public function insert($table, $fields = array()) {
            $keys = array_keys($fields);
            $values = null;
            $x = 1;

            foreach($fields as $field) {
                $values .= '?';
                if($x < count($fields)) {
                    $values .= ', '; 
                }
                $x++;
            }

        $sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})"; //echo = "INSERT INTO users (`username`, `password`)"

            if(!$this->query($sql, $fields)->error()) { //------------------troubleshooting 7/17 12:48, added ! to !$this
                return true;
            }
        return false;
    }


    public function update($table, $id, $fields) {
        $set = '';
        $x = 1;

        foreach($fields as $name => $value) {
            $set .= "{$name} = ?";
            if($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }   

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        if(!$this->query($sql, $fields)->error()) { //---------------------add ! ???
            return true;
        }

        return false;
    }

    //-------------------------------------------------------------------    

    //-------------------------------------------------------------------    
    public function results() {
        return $this->_results; 
    }


    public function first() {
        return $this->results()[0];
    }

    
    //-------------------------------------------------------------------
    public function error() { //Chain this error method in 'index.php'.
        return $this->_error;
    }


    public function count() {
        return $this->_count;
    }
    /*Query function with two arguments. Query string and 
    an array of paramaters. Adds security to query by binding paramaters and removing SQL injection risks. */
    
}