<?php
class Hash {
    
    public static function unique() {   
        return hash('sha256', mt_rand());
      
    }
}

    //This method creates a unique hash by hashing a random number. 