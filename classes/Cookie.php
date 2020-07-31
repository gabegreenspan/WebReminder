<?php
class Cookie {
    public static function exists($name) {
        return (isset($_COOKIE[$name])) ? true : false;
    } //Check if a cookie exists.

    public static function get($name) {
        return $_COOKIE[$name];
    } //Get the value of a cookie

    public static function put($name, $value, $expiry) {
        if(setcookie($name, $value, time() + $expiry, '/')) {   // Expire time must be appended to the current time listed in seconds, and must have a path '/'.
            return true;
        }
        return false;
    } // Create cookie

    public static function delete($name) {
        self::put($name, '', time() - 1);
    } // name a cookie, reset it with an empty string, set the current time - 1.
}