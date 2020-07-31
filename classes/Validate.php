<?php
class Validate {
    private $_passed = false,
            $_errors = array(),
            $_db = null;
    
    public function __construct() { //Construct function called when the Validate class is instantiated.
        $this->_db = DB::getInstance();

    }

    public function check($source, $items = array()) { // Pass in the data we want to loop thru and check the array of rules.
        foreach($items as $item => $rules) { // List thru the items and there respective array of rules and check them againced the source that we provided and ad to erros as we go. Source='register.php'/$validation [Items(username, password, password_again, name) array of rules of each item].
            foreach($rules as $rule => $rule_value) {
                
                $value = trim($source[$item]); // trim removes specified characters from both sides of a string. 
                $item = escape($item);  //escape method defined in Sanitize.php

                if($rule === 'required' && empty($value)) {  // Check if input is entered. If so checks if input meets rule requirements.
                    $this->addError("{$item} is required"); //TO DO:  'register.php' (validation class): Issue- $item is going to equal the field name of that value. Add the ability to define in the validator class, the name that should be displayed instead of the actual field name. ('name' => 'Username',) 
                }  
                else if(!empty($value)) {    
                    switch($rule) {
                        case 'min': // Rule: string length must be greater than or equal to the minimume.
                            if(strlen($value) < $rule_value) {
                                $this->addError("{$item} must be a minimum of {$rule_value} characters.");
                            }
                        break;
                        case 'max':
                            if(strlen($value) > $rule_value) {
                                $this->addError("{$item} must be a maximum of {$rule_value} characters.");
                            }

                        break;
                        case 'matches':
                            if($value != $source[$rule_value]) {    // We use source[$rule_value] because we are refering to anouther fields value.
                                $this->addError("{$rule_value} must match {$item}");
                            }

                        break;
                        case 'unique':  // Uses the DB wrapper.
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if($check->count()) {
                                $this->addError("{$item} already exists.");
                            }
                            
                        break;
                    }
                }
            }
        }

        if(empty($this->_errors)) {
            $this->_passed = true;
            
        }
        return $this;
    }

    private function addError($error) { // Adds errors to the error array.
        $this->_errors[] = $error;
    }

    public function errors() {
        return $this->_errors;
    }

    public function passed() {
        return $this->_passed;
    }
    /*Form validation.
        class= Validate: PROPERTIES: Detect wether it is passed or not. Check for errors, store
            the errors and output them. Able to creat an instance of the database (in the construct function).
    */
}