<?php
class User {
    private $_db,
            $_data, //Where the users data is stored.
            $_sessionName, // is set by __construct to use in the application to save us from using config::get every time.
            $_cookieName,
            $_isLoggedIn;

    public function __construct($user = null) { 
        $this->_db = DB::getInstance(); //Set db property to DB::getInstance() (To make use of the DB)
    
        $this->_sessionName = Config::get('session/session_name'); //grabbing the session/session_name from "init.php" and setting it to $this->_sessionName
        $this->_cookieName = Config::get('remember/cookie_name');
       
        if(!$user) { //Check if a user is logged in
            if(Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);
                
            }       
                if($this->find($user)) {
                        $this->_isLoggedIn = true;  
                }  else {
                    //proccess logout.
                }      
                   
            } else {
                $this->find($user);
            }  
            
        }
    
    public function update($fields = array(), $id = null) {

        if(!$id && $this->isLoggedIn()) {
            $id = $this->data()->id;
        }

        if(!$this->_db->update('users', $id, $fields)) {
            throw new Exception('There was a problem updating your information.');
        }
    }   //Call the update method for the DB




    public function create($fields = array()) { // Ability to creat a user.
        if(!$this->_db->insert('users', $fields)) {
            throw new Exception('There was a problem creating your account.');
        }
    }

    public function find($user = null) {  //Ability to find a user by there id, not just there username
        if($user) {
           $field = (is_numeric($user)) ? 'id' : 'username'; 
           $data = $this->_db->get('users', array($field, '=', $user)); //Grabbing the data from database to use the methods within our DB wrapper.
        
           if($data->count()) {
            $this->_data = $data->first(); //Taking the first and only result.
            return true;
            }
        }
        return false;
        
    }   //Currently allowing numeric usernames. If we want to restrict usernames to alphanumeric, we would have to define it in the validation class
        //TEST: print_r($this->_data);  //returns all of the selected users data. can be used to check credentials.
    

    public function login($username = null, $password = null, $remember = false) { //Checks if the username and password has been supplied and that they exist in the DB..
         
        if(!$username && !$password && $this->exists()) { //If no username and password is defined and this user exists, log user in.
            Session::put($this->_sessionName, $this->data()->id);
        } else {
            $user = $this->find($username); //Find the user.
            
            if($user) {
                if(password_verify($password, $this->data()->password)) { //Verifies password.
                    Session::put($this->_sessionName, $this->data()->id); //Puts the session in with the user id ()
                    
                    if($remember) {  //Generate a hash, check to see if the hash already exists in the DB. If not insert hash into the DB. This should occur every time the user visits the page.
                        $hash = Hash::unique(); //Generate a unique hash
                        $hashCheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id));    //Check for an existing hash in the DB.

                        if(!$hashCheck->count()) { //If no hash is in the DB, insert one.
                            $this->_db->insert('users_session', array(
                                'user_id' => $this->data()->id,
                                'hash' => $hash
                            )); 
                        } else {   
                            $hash = $hashCheck->first()->hash; 
                        }

                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry')); //Sets cookie with the expire time set in config.php
                    } 
                    
                    return true;
                }
            }   //Looks up the user name entered. If username exists in the DB, checks the entered password to the stored password for that user. If true, logs them in and sets up a session for that user. 
        }
        return false; 
    }

    public function exists() {
        return (!empty($this->_data)) ? true : false;
    }   //Checks to see if the data exists in this data array.

    public function logout() {
        
        $this->_db->delete('users_session', array('user_id', '=', $this->data()->id));
        
        Session::delete($this->_sessionName);
        Session::delete($this->_cookieName);

    }

    public function data() {   
        return $this->_data;
    }//EZ reference function.. Returns ($_data), which is stored in the variable at the top and is assigned the users data retrieved in the "find" function.

    public function isLoggedIn() {
        return $this->_isLoggedIn;
    }

    public function db() {   
        return $this->_db;
    }

}


