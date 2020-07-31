<?php
require_once 'core/init.php';


if(Session::exists('home')) {   //A generic place to redirect users for error or success.
    echo '<p>' . Session::flash('home') . '</p>';
}

$user = new User();
if($user->isLoggedIn()) {
?>
    <p>Hello <a href='#'><?php echo escape($user->data()->name); ?></a>!</p>

    <ul>
        <li><a href="logout.php">Log out<a/></li>
        <li><a href="update.php">Update details<a/></li>
        <li><a href="changepassword.php">Cange password<a/></li>
        <li><a href="userpage.php">User Page<a/></li>

    </ul>
<?php
} else {
    echo '<p>Welcome to Web Reminder!</p><p>Would you like to <a href="login.php">log in</a> or <a href="register.php">register?</a></p>';
}



/* 
Purpose: Easily access config)
    (Config.php) end result would be, as an example run
    echo Config::get('mysql/host'); which should output '127.0.0.1' 

EXAMPLE of functionality-
    $users = DB::getInstance()->query('SELECT username FROM users');
    if($users->count()) {
        foreach($users as $user) {
            echo $user->username; // NOTE: "username" = field name in the DB.
        }
    }

    Database querying.

    Database results. public function results() in "DB.php"
        FOR SINGLE RESULTS ----------------------
            $user =DB::getInstance()->get('users', array('username', '=', 'alex'));
            if(!$user->count()) {
                echo 'No user';
            } else {
                echo $user->first()->username; 
            }

        LOOP FOR MULTIPLE RESULTS ------------------
            $user =DB::getInstance()->query("SELECT * FROM users");
            if(!$user->count()) {
                echo 'No user';
            } else {
                foreach($user->results() as $user) {
                    echo $user->username, '<br>';
                }
            }


EXAMPLE OF INSERT FUNCTION IN DB.php.
    $userInsert =DB::getInstance()->insert('users', array(
        'username' => 'Dale',
        'password' => 'password',
        'salt' => 'salt'
    ));

    if($userInsert) {
        // success
    }

EXAMPLE OF UPDATE FUNCTION IN DB.php.
    $userInsert =DB::getInstance()->update('users', 3, array(
        'password' => 'newpassword',
        'name' => 'Dale Garrett'
    ));
 
Flashing
    if(Session::exists('home')) {  
        echo '<p>' . Session::flash('home') . '</p>';
    }

    To sign in particular user- echo Session::get(Config::get('session/session_name')); // this echos out the logged in users login id

Checking signed in users.
Return user details...
    $user = new User(); // current user/ default
    $anotheruser = new User(6); // anouther user

Get user details- 
    echo $user->data()->username;

 */


