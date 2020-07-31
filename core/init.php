<?php
session_start();

/* Global array of settings. Helps keep consistancy and makes it easy to set things up. */
$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'db' => 'reminder_schema'    
    ),

    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 6600

    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token' // Part:12- Adding the token to the session config. This insures the token has the same name all the time.
    )
);

/* spl Function allows you to pass a function thats run every time a class is accessed. Argument list takes
the desired class name and requires it.*/
spl_autoload_register(function($class) {
    require_once 'classes/' . $class . '.php';
});


/* Functions used */
require_once 'functions/sanitize.php';

if (!Session::exists(Config::get('session/session_name')) && Cookie::exists(Config::get('remember/cookie_name'))) { // reversed these checks
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

    if($hashCheck->count()) {
        $user = new User($hashCheck->first()->user_id); //Specifies $user in construct__($user) function in user.php
        $user->login();
    } 

}   //Grab the cookie value, look it up in the DB, grab the user_id and log the user in by that user_id. (in user_session table.)



/*
if 
$db = new DB(); 
function inserts DB() into the $class variable and checks it.
*/