<?php
class Session { 
    public static function exists($name) {  // Check if a partucular session exists.
        return (isset($_SESSION[$name])) ? true : false; // If token is set Return true, else return false.
    }

    public static function put($name, $value) {
        return $_SESSION[$name] = $value;   //Returns the name and value of the session
    }

    public static function get($name) { //Get a particular value
        return $_SESSION[$name];
    }

    public static function delete($name) {   // Delete a token.
        if(self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    public static function flash($name, $string = '') { //EXAMPLE: user registers, user is redirected to another page and a message flashes saying 'registration successful', then message dissapears on the next refresh.
        if(self::exists($name)) {
            $session = self::get($name); //NOTE- We use 'self' in the scope resolution operator because we are using a static method.
            self::delete($name);    //delete session
            return $session;    //return session
        } else {
            self::put($name, $string);  //otherwise we set the data.
        }
    }
}

//CSRF Protection. This portion establishes the Session class for Token functionality.
//Flashing. Flash message to a user and message is removed upon page refresh. 