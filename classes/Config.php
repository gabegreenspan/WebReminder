<?php
class Config {
    public static function get($path = null) { /* NOTE: path = the path thats passed in*/
        if($path) {
            $config = $GLOBALS['config'];
            $path = explode('/', $path); //explode takes a character you want to explode by and gives an array back.

            
            foreach($path as $bit) {
                if(isset($config[$bit])) {
                    $config = $config[$bit];
                }
            }

            return $config; // to do: make this check to see if what is requested accually exists.
        }

        return false;
    }
}

/* P6
Config class allows you to draw any option we want from "Config()" that is defined in "init.php" and allows it
 to be used with options we want to add to the init config.
*/