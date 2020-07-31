<?php
require_once 'core/init.php';

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {     //Checks if the token is correct and supplied as per the form.

        $validate = new Validate();     // Uses the validate class to check if input follows the rules we want to set.
        $validation = $validate->check($_POST, array(
            'username' =>array('required' => true),
            'password' =>array('required' => true)
        ));

        if($validation->passed()) {
            $user = new User();     //Instantiate a new user class object.

            $remember = (Input::get('remember') === 'on') ? true : false;
            $login = $user->login(Input::get('username'), Input::get('password'), $remember); //uses User class 'login' method

            if($login) {    // Check login results and redirects to the proper location.
                Redirect::to('index.php');
            } else {
                echo '<p>Sorry, loggin failed.</p>';
            }
            
        } else {
            foreach($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
}
?>

<form action="" method="post">
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" autocomplete="off">
    </div>

    <div class="field">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" autocomplete="off">
    </div>

    <div class="field">
        <label for="remember">
            <input type="checkbox" name="remember" id="remember"> Remember me
        </label>
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"> <!--Generate a token to frotect from CSRF -->
    <input type="submit" value="log in">
</form>