<?php
require_once 'core/init.php';

$user = new User(); //Instantiate a new user object.

if(!$user->isLoggedIn()) {  //Check if the user is logged in
    Redirect::to('index.php');
}

if(Input::exists()) {    // Check if input exists.
    if(Token::check(Input::get('token'))) { // CSRF check
        
        $validate = new Validate(); //Instantiate a new Validate object.
        $validation = $validate->check($_POST, array(
            'password_current' => array(
                'required' => true,
                'min' => 6
            ),
            'password_new' => array(
                'required' => true,
                'min' => 6
            ),

            'password_new_again' => array(
                'required' => true,
                'min' => 6,
                'matches' => 'password_new'
            )
        ));

        if($validation->passed()) {
            if(password_verify(Input::get('password_current'), $user->data()->password)) {
               $user->update(array(
                'password' => password_hash(Input::get('password_new'), PASSWORD_DEFAULT)
            )); 

            Session::flash('home', 'Your password has been changed!');
            Redirect::to('index.php');

            } else {
                echo 'Opps. The current password you entered did not match your current password!';
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
    <label for="password_current">Current password</label>
    <input type="password" name="password_current" id="password_current">
    </div>

    <div class="field">
    <label for="password_new">New password</label>
    <input type="password" name="password_new" id="password_new">
    </div>

    <div class="field">
    <label for="password_new_again">New password again</label>
    <input type="password" name="password_new_again" id="password_new_again">
</div>

    <input type="submit" value="Change">
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
</form>